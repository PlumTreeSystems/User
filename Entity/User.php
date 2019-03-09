<?php

namespace PlumTreeSystems\UserBundle\Entity;

use PlumTreeSystems\UserBundle\Model\TokenizeableInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use PlumTreeSystems\UserBundle\Exception\RoleDoesNotExistException;

/**
 * User
 */
abstract class User implements UserInterface, EquatableInterface, \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $plainPassword = "";

    /**
     * @var string
     */
    protected $roles;

    /**
     * @var bool
     */
    private $disabled;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->setRoles([]);
        $this->setDisabled(false);
    }


    /**
     * Return unique user Id
     *
     * @return string
     */
    abstract public function getId(): string;

    /**
     * Set unique user Id
     *
     * @param string $id
     */
    abstract public function setId(string $id);

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->setPassword('');
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->disabled;
    }

    /**
     * @param bool $disabled
     */
    public function setDisabled(bool $disabled)
    {
        $this->disabled = $disabled;
    }



    protected function defineRoles()
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN
        ];
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return json_decode($this->roles);
    }

    public function setRoles(array $roles)
    {
        $availableRoles = $this->defineRoles();
        foreach ($roles as $role) {
            if (!(in_array($role, $availableRoles))) {
                throw new RoleDoesNotExistException();
            }
        }
        $this->roles = json_encode($roles);
    }

    public function addRole(string $role)
    {
        $availableRoles = $this->defineRoles();
        if (in_array($role, $availableRoles)) {
            $currentRoles = $this->getRoles();
            $currentRoles[] = $role;
            $this->setRoles($currentRoles);
        } else {
            throw new RoleDoesNotExistException();
        }
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = '';
    }

    /**
     * The equality comparison should neither be done by referential equality
     * nor by comparing identities (i.e. getId() === getId()).
     *
     * However, you do not need to compare every attribute, but only those that
     * are relevant for assessing whether re-authentication is required.
     *
     * Also implementation should consider that $user instance may implement
     * the extended user interface `AdvancedUserInterface`.
     *
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isEqualTo(UserInterface $user)
    {
        return $this->getUsername() == $user->getUsername();
    }

    public function serialize()
    {
        $userData = [
            'email'=>$this->getUsername(),
            'id'=>$this->getId(),
            'roles'=>$this->getRoles()
        ];
        return serialize($userData);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->setEmail($data['email']);
        $this->setId($data['id']);
        $this->setRoles($data['roles']);
    }
}
