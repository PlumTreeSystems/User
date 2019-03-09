<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-02-08
 * Time: 15:21
 */

namespace PlumTreeSystems\UserBundle\Security;

use PlumTreeSystems\UserBundle\Entity\User;
use PlumTreeSystems\UserBundle\Exception\UserIsDisabledException;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    /**
     * Checks the user account before authentication.
     *
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user)
    {
        if ($user instanceof User) {
            if ($user->isDisabled()) {
                throw new UserIsDisabledException();
            }
        }
        return;
    }

    /**
     * Checks the user account after authentication.
     *
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user)
    {
        return;
    }
}
