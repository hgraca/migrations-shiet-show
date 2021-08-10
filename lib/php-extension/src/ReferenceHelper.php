<?php

declare(strict_types=1);

namespace Acme\PhpExtension;

use function is_object;
use function uniqid;

final class ReferenceHelper
{
    public static function isSame(&$itemA, &$itemB): bool
    {
        if ($itemA !== $itemB) {
            return false;
        }

        if (is_object($itemA)) {
            return $itemA === $itemB;
        }

        // Save original values of the items under comparison
        $originalItemA = $itemA;
        $originalItemB = $itemB;

        // Change itemB
        $newValueOfItemB = uniqid();
        $itemB = $newValueOfItemB;

        // Verify if itemA changed when we changed item B, in which case both variables reference the same item.
        $itemAChangedWhenItemBChanged = $itemA === $newValueOfItemB;

        // Put the items original values into place
        $itemA = $originalItemA;
        $itemB = $originalItemB;

        return $itemAChangedWhenItemBChanged;
    }
}
