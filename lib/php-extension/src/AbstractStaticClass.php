<?php

declare(strict_types=1);

namespace Acme\PhpExtension;

/**
 * This class is just an utility class that helps us to remove duplication from the tests
 * and that's why it can't be instantiated.
 */
abstract class AbstractStaticClass
{
    protected function __construct()
    {
        // All methods should be static, so no need to instantiate any of the subclasses
    }
}
