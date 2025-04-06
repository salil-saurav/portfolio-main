<?php
/**
 * HTML, CSS, and JS Minification functionality
 *
 * @package WordPress-Starter
 * @version 1.1.0
 */

namespace WPStarter\Optimization;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * HTML Minifier Class
 * 
 * Handles HTML, inline CSS and JS minification during page rendering
 */
class HTML_Minifier {
    /**
     * Configuration options
     *
     * @var array
     */
    private static $config = [
        'minify_html' => true,
        'minify_inline_css' => true,
        'minify_inline_js' => true,
        'remove_comments' => true,
        'preserve_conditional_comments' => true,
        'version_assets' => true,
    ];

    /**
     * Initialize the minifier
     *
     * @param array $config Optional. Configuration to override defaults.
     * @return void
     */
    public static function init($config = []) {
        // Merge user config with defaults
        if (!empty($config) && is_array($config)) {
            self::$config = array_merge(self::$config, $config);
        }

        // Only hook if minification should happen
        if (self::should_minify()) {
            add_action('template_redirect', [self::class, 'start_buffer'], 1);
            add_action('shutdown', [self::class, 'end_buffer'], 0);
            
            if (self::$config['version_assets']) {
                add_filter('style_loader_src', [self::class, 'version_assets'], 10, 2);
                add_filter('script_loader_src', [self::class, 'version_assets'], 10, 2);
            }
        }
    }

    /**
     * Configure the minifier
     *
     * @param array $config Configuration options
     * @return void
     */
    public static function configure($config) {
        if (!empty($config) && is_array($config)) {
            self::$config = array_merge(self::$config, $config);
        }
    }

    /**
     * Start output buffering
     *
     * @return void
     */
    public static function start_buffer() {
        ob_start([self::class, 'minify']);
    }

    /**
     * End output buffering safely
     *
     * @return void
     */
    public static function end_buffer() {
        if (ob_get_level() > 0) {
            ob_end_flush();
        }
    }

    /**
     * Minify HTML content
     *
     * @param string $buffer The HTML content to minify
     * @return string The minified HTML content
     */
    public static function minify($buffer) {
        // Only process HTML
        if (!is_string($buffer) || empty($buffer)) {
            return $buffer;
        }

        try {
            // Save any conditional comments if configured
            $conditionals = [];
            if (self::$config['preserve_conditional_comments']) {
                if (preg_match_all('/<!--\[if[^\]]*?\]>.*?<!\[endif\]-->/is', $buffer, $matches)) {
                    $conditionals = $matches[0];
                    // Replace conditionals with placeholders
                    $buffer = preg_replace_callback('/<!--\[if[^\]]*?\]>.*?<!\[endif\]-->/is', 
                        function($match) {
                            return '<!-- WPSTARTER_CONDITIONAL_' . md5($match[0]) . ' -->';
                        }, 
                        $buffer
                    );
                }
            }

            // Minify HTML if enabled
            if (self::$config['minify_html']) {
                $buffer = self::minify_html($buffer);
            }

            // Minify inline CSS if enabled
            if (self::$config['minify_inline_css']) {
                $buffer = preg_replace_callback('/<style\b[^>]*>(.*?)<\/style>/is', 
                    function($matches) {
                        $content = $matches[1];
                        // Don't minify if it contains expressions that might break
                        if (strpos($content, 'calc(') !== false || strpos($content, '@keyframes') !== false) {
                            return $matches[0];
                        }
                        return '<style>' . Minifier::minify_css($content) . '</style>';
                    }, 
                    $buffer
                );
            }

            // Minify inline JavaScript if enabled
            if (self::$config['minify_inline_js']) {
                $buffer = preg_replace_callback('/<script\b([^>]*)>(.*?)<\/script>/is',
                    function($matches) {
                        $attributes = $matches[1];
                        $content = $matches[2];
                        
                        // Don't minify if it's an external script or type isn't javascript
                        if (strpos($attributes, 'src=') !== false || 
                            (strpos($attributes, 'type=') !== false && 
                             !preg_match('/type=(["\'])(?:text|application)\/(?:javascript|ecmascript)\1/i', $attributes))) {
                            return $matches[0];
                        }
                        
                        return "<script {$attributes}>" . Minifier::minify_js($content) . '</script>';
                    }, 
                    $buffer
                );
            }

            // Restore conditional comments if we preserved them
            if (!empty($conditionals)) {
                foreach ($conditionals as $conditional) {
                    $placeholder = '<!-- WPSTARTER_CONDITIONAL_' . md5($conditional) . ' -->';
                    $buffer = str_replace($placeholder, $conditional, $buffer);
                }
            }

            return $buffer;
        } catch (\Exception $e) {
            // Log error if WP_DEBUG is enabled
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('HTML Minifier Error: ' . $e->getMessage());
            }
            // Return original content if minification fails
            return $buffer;
        }
    }

    /**
     * Minify HTML content
     *
     * @param string $buffer HTML content
     * @return string Minified HTML
     */
    private static function minify_html($buffer) {
        $search = [
            '/\>[^\S ]+/s',     // Remove whitespace after tags
            '/[^\S ]+\</s',     // Remove whitespace before tags
            '/(\s)+/s',         // Remove multiple whitespace sequences
        ];

        $replace = [
            '>',
            '<',
            '\\1',
        ];

        // Remove HTML comments if enabled, but preserve IE conditionals if configured
        if (self::$config['remove_comments']) {
            $buffer = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $buffer);
        }

        return preg_replace($search, $replace, $buffer);
    }

    /**
     * Version assets to prevent caching issues
     *
     * @param string $src Asset URL
     * @param string $handle Asset handle
     * @return string Modified URL
     */
    public static function version_assets($src, $handle) {
        if (empty($src) || is_admin()) {
            return $src;
        }

        // Don't add version if already versioned
        if (strpos($src, 'ver=') !== false) {
            return $src;
        }

        // Add theme version or WordPress version
        $version = wp_get_theme()->get('Version');
        if (empty($version)) {
            $version = get_bloginfo('version');
        }

        return add_query_arg('ver', $version, $src);
    }

    /**
     * Check if minification should be performed
     *
     * @return bool
     */
    private static function should_minify() {
        // Don't minify for admin pages
        if (is_admin()) {
            return false;
        }

        // Don't minify for AJAX requests
        if (wp_doing_ajax()) {
            return false;
        }

        // Don't minify if SCRIPT_DEBUG is enabled
        if (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) {
            return false;
        }

        // Don't minify in customize preview
        if (is_customize_preview()) {
            return false;
        }

        // Allow developers to disable minification
        return apply_filters('wpstarter_should_minify', true);
    }
}

/**
 * Asset Minifier Class
 * 
 * Handles static CSS and JS file minification
 */
class Minifier {
    /**
     * Configuration options
     *
     * @var array
     */
    private static $config = [
        'backup_original' => true,
        'overwrite_existing' => false,
        'css_aggressive' => false,
        'js_aggressive' => false,
    ];

    /**
     * Initialize the minifier with configuration
     *
     * @param array $config Configuration options
     * @return void
     */
    public static function configure($config) {
        if (!empty($config) && is_array($config)) {
            self::$config = array_merge(self::$config, $config);
        }
    }

    /**
     * Minify JavaScript code
     *
     * @param string $js JavaScript code
     * @return string Minified code
     */
    public static function minify_js($js) {
        if (empty($js)) {
            return '';
        }

        try {
            // Preserve important multi-line comments that might be license information
            $important_comments = [];
            preg_match_all('/\/\*![\s\S]*?\*\//m', $js, $matches);
            
            if (!empty($matches[0])) {
                foreach ($matches[0] as $i => $comment) {
                    $placeholder = "/* IMPORTANT_COMMENT_{$i} */";
                    $important_comments[$placeholder] = $comment;
                    $js = str_replace($comment, $placeholder, $js);
                }
            }

            // Remove single line comments
            $js = preg_replace('/\/\/.*$/m', '', $js);
            
            // Remove multi-line comments
            $js = preg_replace('/\/\*(?!.*?\*\/).*?\*\//s', '', $js);
            
            // Remove whitespace
            $js = preg_replace('/\s+/', ' ', $js);
            
            // More aggressive optimization if configured
            if (self::$config['js_aggressive']) {
                // Remove spaces around operators and separators
                $js = preg_replace('/\s*([=+\-*\/%<>!&|:;,()])\s*/', '$1', $js);
                
                // Remove unnecessary semicolons
                $js = preg_replace('/;+\}/', '}', $js);
            }
            
            // Restore important comments
            foreach ($important_comments as $placeholder => $comment) {
                $js = str_replace($placeholder, $comment, $js);
            }
            
            return trim($js);
        } catch (\Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('JS Minification Error: ' . $e->getMessage());
            }
            return $js; // Return original if minification fails
        }
    }

    /**
     * Minify CSS code
     *
     * @param string $css CSS code
     * @return string Minified code
     */
    public static function minify_css($css) {
        if (empty($css)) {
            return '';
        }

        try {
            // Preserve important comments (e.g., licenses)
            $important_comments = [];
            preg_match_all('/\/\*![\s\S]*?\*\//m', $css, $matches);
            
            if (!empty($matches[0])) {
                foreach ($matches[0] as $i => $comment) {
                    $placeholder = "/* IMPORTANT_COMMENT_{$i} */";
                    $important_comments[$placeholder] = $comment;
                    $css = str_replace($comment, $placeholder, $css);
                }
            }
            
            // Remove comments
            $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
            
            // Remove whitespace
            $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);
            
            // Remove unnecessary spaces
            $css = preg_replace('/\s+/', ' ', $css);
            $css = preg_replace('/\s*([\{\}:;,])\s*/', '$1', $css);
            
            // Remove trailing semicolons
            $css = str_replace(';}', '}', $css);
            
            // More aggressive optimization if configured
            if (self::$config['css_aggressive']) {
                // Shorten hex color codes from #ffffff to #fff where possible
                $css = preg_replace('/#([a-f0-9])\1([a-f0-9])\2([a-f0-9])\3/i', '#$1$2$3', $css);
                
                // Remove units from zero values (0px -> 0)
                $css = preg_replace('/(?<!\d)0(?:em|ex|px|pt|pc|in|cm|mm|%)/i', '0', $css);
                
                // Remove leading zeros from decimal values (.5 instead of 0.5)
                $css = preg_replace('/0\.([0-9]+)/', '.$1', $css);
            }
            
            // Restore important comments
            foreach ($important_comments as $placeholder => $comment) {
                $css = str_replace($placeholder, $comment, $css);
            }
            
            return trim($css);
        } catch (\Exception $e) {
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('CSS Minification Error: ' . $e->getMessage());
            }
            return $css; // Return original if minification fails
        }
    }

    /**
     * Process assets in directories
     *
     * @param string $assets_dir Directory containing assets
     * @param array $options Processing options
     * @return array Statistics of processed files
     */
    public static function process_assets($assets_dir, $options = []) {
        // Merge options with defaults
        $options = array_merge([
            'js_dir' => '/js',
            'css_dir' => '/css',
            'recursive' => false,
            'file_mask' => '*',
        ], $options);

        $stats = [
            'js_processed' => 0,
            'css_processed' => 0,
            'js_size_before' => 0,
            'js_size_after' => 0,
            'css_size_before' => 0,
            'css_size_after' => 0,
            'errors' => [],
        ];

        // Process JS files
        $js_dirs = $options['recursive'] 
            ? self::recursive_glob($assets_dir . $options['js_dir'], $options['file_mask'] . '.js') 
            : glob($assets_dir . $options['js_dir'] . '/' . $options['file_mask'] . '.js');

        if (!empty($js_dirs)) {
            foreach ($js_dirs as $js_file) {
                try {
                    // Skip already minified files and map files
                    if (strpos($js_file, '.min.js') !== false || strpos($js_file, '.map.js') !== false) {
                        continue;
                    }

                    $js_code = file_get_contents($js_file);
                    $original_size = strlen($js_code);
                    $stats['js_size_before'] += $original_size;

                    $minified_js = self::minify_js($js_code);
                    $minified_size = strlen($minified_js);
                    $stats['js_size_after'] += $minified_size;

                    $minified_js_file = str_replace('.js', '.min.js', $js_file);
                    
                    // Check if we should overwrite existing minified files
                    if (!file_exists($minified_js_file) || self::$config['overwrite_existing']) {
                        file_put_contents($minified_js_file, $minified_js);
                        $stats['js_processed']++;
                    }
                } catch (\Exception $e) {
                    $stats['errors'][] = "Error processing {$js_file}: " . $e->getMessage();
                    continue;
                }
            }
        }

        // Process CSS files
        $css_dirs = $options['recursive'] 
            ? self::recursive_glob($assets_dir . $options['css_dir'], $options['file_mask'] . '.css') 
            : glob($assets_dir . $options['css_dir'] . '/' . $options['file_mask'] . '.css');

        if (!empty($css_dirs)) {
            foreach ($css_dirs as $css_file) {
                try {
                    // Skip already minified files and map files
                    if (strpos($css_file, '.min.css') !== false || strpos($css_file, '.map.css') !== false) {
                        continue;
                    }

                    $css_code = file_get_contents($css_file);
                    $original_size = strlen($css_code);
                    $stats['css_size_before'] += $original_size;

                    $minified_css = self::minify_css($css_code);
                    $minified_size = strlen($minified_css);
                    $stats['css_size_after'] += $minified_size;

                    $minified_css_file = str_replace('.css', '.min.css', $css_file);
                    
                    // Check if we should overwrite existing minified files
                    if (!file_exists($minified_css_file) || self::$config['overwrite_existing']) {
                        file_put_contents($minified_css_file, $minified_css);
                        $stats['css_processed']++;
                    }
                } catch (\Exception $e) {
                    $stats['errors'][] = "Error processing {$css_file}: " . $e->getMessage();
                    continue;
                }
            }
        }

        // Calculate savings as percentage
        if ($stats['js_size_before'] > 0) {
            $stats['js_savings'] = round(($stats['js_size_before'] - $stats['js_size_after']) / $stats['js_size_before'] * 100, 2);
        }
        
        if ($stats['css_size_before'] > 0) {
            $stats['css_savings'] = round(($stats['css_size_before'] - $stats['css_size_after']) / $stats['css_size_before'] * 100, 2);
        }

        return $stats;
    }

    /**
     * Recursively find files in directories
     *
     * @param string $base_dir Base directory
     * @param string $pattern File pattern
     * @param int $flags Glob flags
     * @return array Files found
     */
    private static function recursive_glob($base_dir, $pattern, $flags = 0) {
        $files = glob(rtrim($base_dir, '/') . '/' . $pattern, $flags);
        
        foreach (glob(rtrim($base_dir, '/') . '/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::recursive_glob($dir, $pattern, $flags));
        }
        
        return $files;
    }
}

/**
 * Asset Optimizer Class
 * 
 * Main class for optimizing assets including file concatenation and automatic compression
 */
class Asset_Optimizer {
    /**
     * Initialize the optimizer with default actions
     *
     * @return void
     */
    public static function init() {
        // Enable HTML minification with default settings
        HTML_Minifier::init();
        
        // Hook into theme setup to process assets if needed
        add_action('after_setup_theme', [self::class, 'maybe_process_assets']);
    }

    /**
     * Check if assets should be processed and do so if needed
     *
     * @return void
     */
    public static function maybe_process_assets() {
        // Only process assets during development or when forced
        if ((defined('WP_DEBUG') && WP_DEBUG) || (isset($_GET['optimize_assets']) && current_user_can('manage_options'))) {
            $theme_dir = get_template_directory();
            $assets_dir = apply_filters('wpstarter_assets_dir', $theme_dir . '/assets');
            
            self::process_theme_assets($assets_dir);
        }
    }

    /**
     * Process theme assets
     *
     * @param string $assets_dir Directory containing assets
     * @param array $options Processing options
     * @return array Processing statistics
     */
    public static function process_theme_assets($assets_dir, $options = []) {
        $options = array_merge([
            'recursive' => true,
            'file_mask' => '*',
        ], $options);
        
        return Minifier::process_assets($assets_dir, $options);
    }
    
    /**
     * Register admin menu for asset optimization
     *
     * @return void
     */
    public static function register_admin_menu() {
        add_management_page(
            'Asset Optimization',
            'Asset Optimizer',
            'manage_options',
            'wpstarter-asset-optimizer',
            [self::class, 'admin_page']
        );
    }
    
    /**
     * Admin page for asset optimization
     *
     * @return void
     */
    public static function admin_page() {
        // Admin UI code would go here - implement as needed
        echo '<div class="wrap">';
        echo '<h1>Asset Optimizer</h1>';
        
        // Process assets if requested
        if (isset($_POST['process_assets']) && check_admin_referer('wpstarter_optimize_assets')) {
            $theme_dir = get_template_directory();
            $assets_dir = apply_filters('wpstarter_assets_dir', $theme_dir . '/assets');
            
            $stats = self::process_theme_assets($assets_dir);
            
            echo '<div class="notice notice-success"><p>';
            echo sprintf(
                'Processed %d JS files (saved %.2f%%) and %d CSS files (saved %.2f%%)',
                $stats['js_processed'],
                $stats['js_savings'] ?? 0,
                $stats['css_processed'],
                $stats['css_savings'] ?? 0
            );
            echo '</p></div>';
            
            if (!empty($stats['errors'])) {
                echo '<div class="notice notice-error"><p>';
                echo 'Encountered errors during processing:';
                echo '<ul>';
                foreach ($stats['errors'] as $error) {
                    echo '<li>' . esc_html($error) . '</li>';
                }
                echo '</ul>';
                echo '</p></div>';
            }
        }
        
        // Settings form
        echo '<form method="post">';
        wp_nonce_field('wpstarter_optimize_assets');
        echo '<p><button type="submit" name="process_assets" class="button button-primary">Process Theme Assets</button></p>';
        echo '</form>';
        
        echo '</div>';
    }
}

// Optional hook for admin page
 add_action('admin_menu', ['WPStarter\Optimization\Asset_Optimizer', 'register_admin_menu']);

// Initialize the optimization system
Asset_Optimizer::init();