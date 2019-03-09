<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-02-06
 * Time: 13:22
 */

namespace PlumTreeSystems\UserBundle\Exception;

class WrongUserTypeException extends UserException
{
    public function __construct($message = "Attempted to retrieve User with Token User method")
    {
        parent::__construct($message);
    }
}
