<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-03
 * Time: 20:04
 */

namespace PlumTreeSystems\UserBundle\Controller;

use PlumTreeSystems\UserBundle\Entity\User;
use PlumTreeSystems\UserBundle\Exception\LoginTimedOutException;
use PlumTreeSystems\UserBundle\Model\TimeoutableInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

abstract class AbstractUserController extends Controller
{
    protected function failLogin(User $user)
    {
        if (!($user instanceof TimeoutableInterface)) {
            return;
        }
        $user->setTimeout();
        $user->incrementFailedLoginCount();

        $this->getDoctrine()->getManager()->flush();
    }

    protected function passLogin(User $user)
    {
        if (!($user instanceof TimeoutableInterface)) {
            return;
        }
        $user->clearFailedLoginCount();
        $this->getDoctrine()->getManager()->flush();
    }

    protected function preLogin(User $user)
    {
        if (!($user instanceof  TimeoutableInterface)) {
            return;
        }
        if ($user->isTimedOut()) {
            throw new LoginTimedOutException();
        } elseif ($user->getFailedLoginCount() >= 5) {
            $user->clearFailedLoginCount();
            $this->getDoctrine()->getManager()->flush();
        }
    }
}
