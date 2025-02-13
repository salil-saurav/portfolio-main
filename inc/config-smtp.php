<?php

if (!defined('ABSPATH')) exit;

add_action('phpmailer_init', 'configure_smtp');

function configure_smtp($phpmailer)
{
    if (!can_configure_smtp()) {
        return;
    }

    $smtp_settings = get_smtp_settings();
    apply_smtp_settings($phpmailer, $smtp_settings);
}

function can_configure_smtp(): bool
{
    return function_exists('get_field')
        && get_field('smtp_email', 'option')
        && get_field('smtp_password', 'option');
}

function get_smtp_settings(): array
{
    return [
        'host' => get_field('smtp_host', 'option'),
        'email' => get_field('smtp_email', 'option'),
        'sender_name' => get_field('smtp_sender_name', 'option'),
        'port' => get_field('smtp_port', 'option'),
        'password' => get_field('smtp_password', 'option'),
    ];
}

function apply_smtp_settings($phpmailer, array $settings): void
{
    $phpmailer->isSMTP();
    $phpmailer->Host = $settings['host'];
    $phpmailer->SMTPAuth = true;
    $phpmailer->Username = $settings['email'];
    $phpmailer->Password = $settings['password'];
    $phpmailer->SMTPSecure = 'tls';
    $phpmailer->Port = $settings['port'];
    $phpmailer->setFrom($settings['email'], $settings['sender_name']);
}
