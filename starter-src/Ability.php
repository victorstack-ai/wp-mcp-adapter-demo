<?php

declare(strict_types=1);

namespace WpMcpAdapterStarter;

use InvalidArgumentException;

final class Ability
{
    private string $name;
    private string $description;
    private array $inputSchema;
    private $handler;

    /**
     * @param callable $handler
     */
    public function __construct(string $name, string $description, array $inputSchema, callable $handler)
    {
        $trimmed = trim($name);
        if ($trimmed === '') {
            throw new InvalidArgumentException('Ability name must not be empty.');
        }

        $this->name = $trimmed;
        $this->description = $description;
        $this->inputSchema = $inputSchema;
        $this->handler = $handler;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getInputSchema(): array
    {
        return $this->inputSchema;
    }

    public function run(array $args): array
    {
        $handler = $this->handler;
        return (array) $handler($args);
    }
}
