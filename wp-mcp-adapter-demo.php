<?php

/**
 * Plugin Name: WP MCP Adapter Demo
 * Description: Minimal Model Context Protocol adapter demo for WordPress.
 * Version: 0.1.0
 * Author: VictorStack AI
 * License: GPLv2 or later
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    return;
}

$autoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoload)) {
    require_once $autoload;
}

use WpMcpAdapterDemo\McpAdapter;

add_action('rest_api_init', function (): void {
    $adapter = new McpAdapter();

    register_rest_route('mcp/v1', '/capabilities', [
        'methods' => 'GET',
        'callback' => function () use ($adapter) {
            return $adapter->getCapabilities();
        },
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('mcp/v1', '/tool', [
        'methods' => 'POST',
        'callback' => function (WP_REST_Request $request) use ($adapter) {
            $name = (string) $request->get_param('name');
            $args = (array) $request->get_param('args');
            return $adapter->runTool($name, $args);
        },
        'permission_callback' => '__return_true',
        'args' => [
            'name' => [
                'required' => true,
                'type' => 'string',
            ],
            'args' => [
                'required' => false,
                'type' => 'object',
            ],
        ],
    ]);
});
