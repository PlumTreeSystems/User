<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-02-06
 * Time: 13:22
 */

namespace PlumTreeSystems\UserBundle\Exception;

class TokenTypeDoesNotExistException extends UserException
{
    public function __construct($message = "Token Type is not defined")
    {
        parent::__construct($message);
    }
}
