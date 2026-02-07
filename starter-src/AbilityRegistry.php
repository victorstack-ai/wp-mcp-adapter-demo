<?php

declare(strict_types=1);

namespace WpMcpAdapterStarter;

use InvalidArgumentException;

final class AbilityRegistry
{
    /** @var array<string, Ability> */
    private array $abilities = [];

    public function register(Ability $ability): void
    {
        $name = $ability->getName();
        if (isset($this->abilities[$name])) {
            throw new InvalidArgumentException(sprintf('Ability already registered: %s', $name));
        }

        $this->abilities[$name] = $ability;
    }

    public function get(string $name): ?Ability
    {
        return $this->abilities[$name] ?? null;
    }

    /**
     * @return Ability[]
     */
    public function all(): array
    {
        return array_values($this->abilities);
    }
}
