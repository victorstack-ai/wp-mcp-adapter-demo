<?php

declare(strict_types=1);

namespace WpMcpAdapterStarter\Abilities;

use WpMcpAdapterStarter\Ability;
use WpMcpAdapterStarter\AbilityRegistry;

final class SiteSummaryAbility
{
    public static function register(AbilityRegistry $registry): void
    {
        $registry->register(new Ability(
            'site_summary',
            'Return basic site information such as name and description.',
            [
                'type' => 'object',
                'properties' => [
                    'include_url' => [
                        'type' => 'boolean',
                        'description' => 'Whether to include the site URL.',
                    ],
                ],
            ],
            function (array $args): array {
                $includeUrl = (bool) ($args['include_url'] ?? false);

                $name = function_exists('get_bloginfo') ? (string) get_bloginfo('name') : 'WordPress Site';
                $description = function_exists('get_bloginfo')
                    ? (string) get_bloginfo('description')
                    : 'A WordPress MCP adapter starter site.';

                $payload = [
                    'name' => $name,
                    'description' => $description,
                ];

                if ($includeUrl) {
                    $payload['url'] = function_exists('get_bloginfo') ? (string) get_bloginfo('url') : '';
                }

                return $payload;
            }
        ));
    }
}
