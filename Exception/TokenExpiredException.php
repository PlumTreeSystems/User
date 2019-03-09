<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-02-06
 * Time: 13:22
 */

namespace PlumTreeSystems\UserBundle\Exception;

use Symfony\Component\HttpKernel\Exception\GoneHttpException;

class TokenExpiredException extends GoneHttpException
{
    public function __construct($message = "The Token is expired.")
    {
        parent::__construct($message);
    }
}
