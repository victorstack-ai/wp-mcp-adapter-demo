# WP MCP Adapter Demo

Minimal WordPress plugin that exposes a tiny Model Context Protocol-style adapter over the WordPress REST API. It is intentionally small: a capabilities endpoint plus a single tool runner with three tools.

## What It Provides

- `GET /wp-json/mcp/v1/capabilities` returns tool metadata.
- `POST /wp-json/mcp/v1/tool` runs a tool.

Tools included:

- `echo` — returns the provided text.
- `list_posts` — returns recent published posts.
- `get_post` — returns a post by ID.

## Install

1. Copy this folder into `wp-content/plugins/wp-mcp-adapter-demo`.
2. Activate **WP MCP Adapter Demo** in WordPress.

## Usage

Fetch capabilities:

```bash
curl -s https://example.test/wp-json/mcp/v1/capabilities | jq
```

Run the echo tool:

```bash
curl -s https://example.test/wp-json/mcp/v1/tool \
  -X POST \
  -H 'Content-Type: application/json' \
  -d '{"name":"echo","args":{"text":"Hello MCP"}}' | jq
```

List posts:

```bash
curl -s https://example.test/wp-json/mcp/v1/tool \
  -X POST \
  -H 'Content-Type: application/json' \
  -d '{"name":"list_posts","args":{"limit":3}}' | jq
```

Get a post:

```bash
curl -s https://example.test/wp-json/mcp/v1/tool \
  -X POST \
  -H 'Content-Type: application/json' \
  -d '{"name":"get_post","args":{"id":42}}' | jq
```

## MCP Descriptor Example

See `mcp.json` for a minimal descriptor that mirrors the capabilities endpoint.

## Starter pattern (extensible Ability registry)

This repo also contains the **starter** pattern (formerly the separate `wp-mcp-adapter-starter` repo), unified here to avoid maintaining two repos:

- `starter-src/` — AbilityRegistry, McpAdapter, and SiteSummaryAbility (extensible pattern).
- `starter-cli/` — example Node CLI client.
- `mcp-starter.json` — sample MCP client config for the starter.

Use the main plugin for a minimal demo; use the starter pattern when you want to register multiple abilities via a registry.

## Notes

- This demo is intentionally small. It does not implement authentication, rate limiting, or user authorization.
- For production usage, add proper permission checks and consider tool-level validation.
