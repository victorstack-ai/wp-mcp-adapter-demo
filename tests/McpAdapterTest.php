<?php

declare(strict_types=1);

namespace WpMcpAdapterDemo\Tests;

use PHPUnit\Framework\TestCase;
use WpMcpAdapterDemo\McpAdapter;

final class McpAdapterTest extends TestCase
{
    public function testCapabilitiesIncludeTools(): void
    {
        $adapter = new McpAdapter();
        $capabilities = $adapter->getCapabilities();

        self::assertSame('wp-mcp-adapter-demo', $capabilities['name']);
        self::assertArrayHasKey('tools', $capabilities);
        self::assertNotEmpty($capabilities['tools']);
    }

    public function testEchoToolReturnsText(): void
    {
        $adapter = new McpAdapter();
        $response = $adapter->runTool('echo', ['text' => 'Hello']);

        self::assertTrue($response['ok']);
        self::assertSame('Hello', $response['result']['text']);
    }

    public function testUnknownToolReturnsError(): void
    {
        $adapter = new McpAdapter();
        $response = $adapter->runTool('nope', []);

        self::assertFalse($response['ok']);
        self::assertSame('unknown_tool', $response['error']['code']);
    }
}
