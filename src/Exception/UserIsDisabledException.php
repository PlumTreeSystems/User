<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-02-06
 * Time: 13:22
 */

namespace PlumTreeSystems\UserBundle\Exception;

use Symfony\Component\Security\Core\Exception\AccountStatusException;

class UserIsDisabledException extends AccountStatusException
{
    public function __construct($message = "User is disabled")
    {
        parent::__construct($message);
    }

    public function getMessageKey()
    {
        return "User is disabled";
    }
}
