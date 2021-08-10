<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Exception;

use Acme\PhpExtension\Helper\ClassHelper;

class InputValidationException extends CmRuntimeException
{
    /** @var array<string, string> */
    private $errorList = [];

    /** @var array<string, string> */
    private $metadata = [];

    public function addError(string $fieldName, string $errorMessage): void
    {
        $this->errorList[$fieldName] = $errorMessage;
    }

    public function hasErrors(): bool
    {
        return count($this->errorList) > 0;
    }

    public function getError(string $key): ?string
    {
        return $this->errorList[$key] ?? null;
    }

    public function addMetadata(string $key, string $value): void
    {
        $this->metadata[$key] = $value;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'metadata' => array_merge(
                $this->metadata,
                [
                    'exception' => ClassHelper::extractCanonicalClassName(static::class),
                    'code' => $this->getCode(),
                ]
            ),
            'errors' => $this->errorList,
        ];
    }
}
