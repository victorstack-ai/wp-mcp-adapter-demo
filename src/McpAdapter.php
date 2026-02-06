<?php

declare(strict_types=1);

namespace WpMcpAdapterDemo;

final class McpAdapter
{
    public function getCapabilities(): array
    {
        return [
            'name' => 'wp-mcp-adapter-demo',
            'version' => '0.1.0',
            'description' => 'Minimal MCP adapter demo exposing WordPress data via REST.',
            'tools' => [
                [
                    'name' => 'echo',
                    'description' => 'Echo back the provided text.',
                    'inputSchema' => [
                        'type' => 'object',
                        'properties' => [
                            'text' => [
                                'type' => 'string',
                                'description' => 'Text to echo.',
                            ],
                        ],
                        'required' => ['text'],
                    ],
                ],
                [
                    'name' => 'list_posts',
                    'description' => 'List recent published posts.',
                    'inputSchema' => [
                        'type' => 'object',
                        'properties' => [
                            'limit' => [
                                'type' => 'integer',
                                'description' => 'Number of posts to return (default 5).',
                            ],
                        ],
                    ],
                ],
                [
                    'name' => 'get_post',
                    'description' => 'Fetch a post by ID.',
                    'inputSchema' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => [
                                'type' => 'integer',
                                'description' => 'Post ID.',
                            ],
                        ],
                        'required' => ['id'],
                    ],
                ],
            ],
        ];
    }

    public function runTool(string $name, array $args): array
    {
        switch ($name) {
            case 'echo':
                return [
                    'ok' => true,
                    'result' => [
                        'text' => (string) ($args['text'] ?? ''),
                    ],
                ];

            case 'list_posts':
                return $this->listPosts($args);

            case 'get_post':
                return $this->getPost($args);

            default:
                return [
                    'ok' => false,
                    'error' => [
                        'code' => 'unknown_tool',
                        'message' => sprintf('Unknown tool: %s', $name),
                    ],
                ];
        }
    }

    private function listPosts(array $args): array
    {
        if (!function_exists('get_posts')) {
            return $this->wpUnavailable();
        }

        $limit = isset($args['limit']) ? (int) $args['limit'] : 5;
        $posts = get_posts([
            'numberposts' => $limit > 0 ? $limit : 5,
            'post_status' => 'publish',
        ]);

        $items = [];
        foreach ($posts as $post) {
            $items[] = [
                'id' => $post->ID,
                'title' => $post->post_title,
                'slug' => $post->post_name,
                'date' => $post->post_date,
            ];
        }

        return [
            'ok' => true,
            'result' => [
                'posts' => $items,
            ],
        ];
    }

    private function getPost(array $args): array
    {
        if (!function_exists('get_post')) {
            return $this->wpUnavailable();
        }

        $id = isset($args['id']) ? (int) $args['id'] : 0;
        if ($id <= 0) {
            return [
                'ok' => false,
                'error' => [
                    'code' => 'invalid_id',
                    'message' => 'Post ID must be a positive integer.',
                ],
            ];
        }

        $post = get_post($id);
        if (!$post) {
            return [
                'ok' => false,
                'error' => [
                    'code' => 'not_found',
                    'message' => 'Post not found.',
                ],
            ];
        }

        return [
            'ok' => true,
            'result' => [
                'id' => $post->ID,
                'title' => $post->post_title,
                'content' => $post->post_content,
                'slug' => $post->post_name,
                'date' => $post->post_date,
            ],
        ];
    }

    private function wpUnavailable(): array
    {
        return [
            'ok' => false,
            'error' => [
                'code' => 'wp_unavailable',
                'message' => 'WordPress functions are not available in this context.',
            ],
        ];
    }
}
