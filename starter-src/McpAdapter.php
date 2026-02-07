<?php

declare(strict_types=1);

namespace WpMcpAdapterStarter;

use Throwable;

final class McpAdapter
{
    private AbilityRegistry $registry;

    public function __construct(AbilityRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function getCapabilities(): array
    {
        $tools = [];
        foreach ($this->registry->all() as $ability) {
            $tools[] = [
                'name' => $ability->getName(),
                'description' => $ability->getDescription(),
                'inputSchema' => $ability->getInputSchema(),
            ];
        }

        return [
            'name' => 'wp-mcp-adapter-starter',
            'version' => '0.1.0',
            'description' => 'Starter MCP adapter exposing WordPress abilities via REST.',
            'tools' => $tools,
        ];
    }

    public function runTool(string $name, array $args): array
    {
        $ability = $this->registry->get($name);
        if (!$ability) {
            return [
                'ok' => false,
                'error' => [
                    'code' => 'unknown_tool',
                    'message' => sprintf('Unknown tool: %s', $name),
                ],
            ];
        }

        try {
            return [
                'ok' => true,
                'result' => $ability->run($args),
            ];
        } catch (Throwable $throwable) {
            return [
                'ok' => false,
                'error' => [
                    'code' => 'handler_error',
                    'message' => $throwable->getMessage(),
                ],
            ];
        }
    }
}
