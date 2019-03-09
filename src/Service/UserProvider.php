<?php
/**
 * Created by PhpStorm.
 * User: matas
 * Date: 2017-01-16
 * Time: 11:51
 */

namespace PlumTreeSystems\UserBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use PlumTreeSystems\UserBundle\Entity\TokenUser;
use PlumTreeSystems\UserBundle\Entity\User;
use PlumTreeSystems\UserBundle\Exception\UserNotFoundException;
use PlumTreeSystems\UserBundle\Exception\WrongUserTypeException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private $class;

    /**
     * @var UserManager
     */
    private $manager;

    /**
     * UserManager constructor.
     * @param $class
     * @param UserManager $manager
     */
    public function __construct($class, UserManager $manager)
    {
        $this->manager = $manager;
        $this->class = $class;
    }


    public function loadUserByUsername($username)
    {
        $user = $this->manager->loadUserByUsername($username);
        if ($user) {
            return $user;
        }
        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof UserInterface) {
            throw new UnsupportedUserException(
                sprintf('Expected an instance of User, but got "%s".', get_class($user))
            );
        }
        if (null === $reloadedUser = $this
                ->manager->loadUserByUsername($user->getUsername())) {
            throw new UsernameNotFoundException(
                sprintf('User with email "%s" could not be reloaded.', $user->getUsername())
            );
        }
        return $reloadedUser;
    }

    public function supportsClass($class)
    {
        return $class == $this->class;
    }
}
