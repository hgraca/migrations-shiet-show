<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity\Ulid;

use Symfony\Component\Uid\Ulid as SymfonyUlid;

final class UlidGenerator
{
    /** @var ?callable(): string */
    private static $customGenerator;

    public static function generate(): string
    {
        $customGenerator = self::$customGenerator;

        return $customGenerator ? $customGenerator() : self::defaultGenerator();
    }

    /**
     * @param callable(): string $customGenerator
     */
    public static function overrideDefaultGenerator(callable $customGenerator): void
    {
        self::$customGenerator = $customGenerator;
    }

    public static function reset(): void
    {
        self::$customGenerator = null;
    }

    public static function validate(string $ulid): bool
    {
        return SymfonyUlid::isValid($ulid);
    }

    private static function defaultGenerator(): string
    {
        return (string) new SymfonyUlid();
    }
}
