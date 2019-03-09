<?php

namespace PlumTreeSystems\UserBundle\Exception;

use PlumTreeSystems\UserBundle\Exception\UserException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2017-11-22
 * Time: 18:16
 */


class RoleDoesNotExistException extends NotFoundHttpException
{
    public function __construct($message = "Role is not defined")
    {
        parent::__construct($message);
    }
}
