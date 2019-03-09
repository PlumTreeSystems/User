<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-02
 * Time: 21:21
 */

namespace PlumTreeSystems\UserBundle\Security;

use PlumTreeSystems\UserBundle\Exception\UserNotFoundException;
use PlumTreeSystems\UserBundle\Service\UserManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenUserProvider implements UserProviderInterface
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var UserManager
     */
    private $manager;

    /**
     * ApiUserProvider constructor.
     * @param $class
     * @param $manager
     */
    public function __construct($class, $manager)
    {
        $this->class = $class;
        $this->manager = $manager;
    }

    public function loadUserByUsername($token)
    {
        $user = $this->manager->loadTokenizeableEntity($token);
        if (!$user) {
            throw new UserNotFoundException();
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    public function supportsClass($class)
    {
        return $class == $this->class;
    }
}
