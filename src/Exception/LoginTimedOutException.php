<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-04
 * Time: 14:30
 */

namespace PlumTreeSystems\UserBundle\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginTimedOutException extends HttpException
{

    public function __construct(
        int $statusCode = 401,
        string $message = "Failed to login too many times, please try again later.",
        \Exception $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }
}
