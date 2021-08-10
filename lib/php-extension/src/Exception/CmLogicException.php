<?php

declare(strict_types=1);

namespace Acme\PhpExtension\Exception;

use LogicException;

/**
 * Exception that represents an error in the program logic.
 * If this exception is thrown, we have a bug!
 * This kind of exceptions should directly lead to a fix in your code.
 *
 * In other words, these are exceptions that can happen, and should be
 * caught somewhere in our code and dealt with elegantly.
 * For example, when a user tries to register with a user name that already
 * exists, the persistence mechanism could throw a specific logic exception
 * which would be caught somewhere and show a nice error message to the user.
 *
 * This exception is in the PhpExtension, which means that it can be used in several projects of the same vendor.
 * Therefore, by catching this exception we might be catching an exception of another library created by
 * the same vendor.
 *
 * @see http://php.net/manual/en/class.logicexception.php
 */
class CmLogicException extends LogicException implements CmExceptionInterface
{
    use CmExceptionTrait;
}
