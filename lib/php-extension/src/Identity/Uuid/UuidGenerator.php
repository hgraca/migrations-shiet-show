<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Identity\Uuid;

use Ramsey\Uuid\Uuid as RamseyUuid;

final class UuidGenerator
{
    /** @var ?callable(): string */
    private static $customGenerator;

    public static function generate(): Uuid
    {
        $customGenerator = static::$customGenerator;

        $uuidString = $customGenerator ? $customGenerator() : self::defaultGenerator();

        return new Uuid($uuidString);
    }

    public static function generateAsString(): string
    {
        $customGenerator = static::$customGenerator;

        return $customGenerator ? $customGenerator() : self::defaultGenerator();
    }

    /**
     * @param callable(): string $customGenerator
     */
    public static function overrideDefaultGenerator(callable $customGenerator): void
    {
        static::$customGenerator = $customGenerator;
    }

    public static function reset(): void
    {
        self::$customGenerator = null;
    }

    public static function validate(string $uuid): bool
    {
        return RamseyUuid::isValid($uuid);
    }

    private static function defaultGenerator(): string
    {
        return RamseyUuid::uuid4()->toString();
    }
}
