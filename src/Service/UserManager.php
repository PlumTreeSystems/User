<?php
/**
 * Created by PhpStorm.
 * User: matas
 * Date: 2017-01-17
 * Time: 12:29
 */

namespace PlumTreeSystems\UserBundle\Service;

use PlumTreeSystems\UserBundle\Model\TokenEntityLoaderInterface;
use PlumTreeSystems\UserBundle\Model\TokenizeableInterface;
use PlumTreeSystems\UserBundle\Model\TokenManagerInterface;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use PlumTreeSystems\UserBundle\Entity\TokenUser;
use PlumTreeSystems\UserBundle\Entity\User;
use PlumTreeSystems\UserBundle\Exception\TokenExpiredException;
use PlumTreeSystems\UserBundle\Exception\UserException;
use PlumTreeSystems\UserBundle\Exception\UserNotFoundException;
use PlumTreeSystems\UserBundle\Exception\UserWithSameEmailExists;
use PlumTreeSystems\UserBundle\Exception\WrongUserTypeException;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $class;

    private $repository;

    private $manager;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $em
     * @param string $class
     * @param TokenManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $em, string $class, TokenManagerInterface $manager)
    {
        $this->em = $em;
        $this->class = $class;
        $this->repository = $em->getRepository($class);
        $this->manager = $manager;
    }

    private function randomString()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $randstring . $characters[rand(0, strlen($characters)-1)];
        }
        return $randstring;
    }

    public function createUser($email, $password, $role = null)
    {
        $user = new $this->class;
        $user->setPlainPassword($password);
        $user->setEmail($email);
        if ($this->repository->findOneBy(['email' => $email])) {
            throw new UserWithSameEmailExists("Email " . $email . " already in use.");
        }
        if ($role) {
            $user->addRole($role);
        }
        $this->em->persist($user);
        $this->em->flush();
        
        return $user;
    }

    public function updatePassword($email, $password)
    {
        $user = $this->repository->findOneBy(['email' => $email]);
        if (!$user) {
            throw new UserNotFoundException("User with email " . $email . " not found.");
        }
        $user->setPlainPassword($password);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * @param $user TokenUser
     * @param $type
     * @throws UserException
     */
    public function createToken($user, $type)
    {
        if (is_subclass_of($user, TokenUser::class)) {
            if ($type) {
                try {
                    $user->setTempTokenType($type);
                    $user->setTempTokenCreationDate(new \DateTime());
                    $user->setTempToken(md5(uniqid("", true)));
                } catch (UserException $e) {
                    throw $e;
                }
            }
        }
    }

    /**
     * @param $user TokenUser
     * @throws UserException
     */
    public function clearToken($user)
    {
        if (is_subclass_of($user, TokenUser::class)) {
            try {
                $user->setTempTokenCreationDate(null);
                $user->setTempToken(null);
            } catch (UserException $e) {
                throw $e;
            }
        }
    }

    public function loadUserByUsername($username)
    {
        return $this->repository->findOneBy(['email' => $username]);
    }


    /**
     * @param $tokenType
     * @param $token
     * @param DateInterval|null $duration
     * @return object
     * @throws TokenExpiredException
     * @throws UserNotFoundException
     * @throws WrongUserTypeException
     */
    public function loadUserByToken($tokenType, $token, DateInterval $duration = null)
    {
        if (!is_subclass_of($this->class, TokenUser::class)) {
            throw new WrongUserTypeException();
        }

        $user = $this->repository->findOneBy([
            'tokenType' => $tokenType,
            'token' => $token
        ]);

        if ($user) {
            if ($duration && ($duration instanceof DateInterval)) {
                $now = new \DateTime();
                $expiryDate = date_add($user->getTokenCreationDate(), $duration);
                if ($expiryDate < $now) {
                    throw new TokenExpiredException();
                }
            }
            return $user;
        }

        throw new UserNotFoundException('User with queried Token and Token Type does not exist');
    }

    public function loadTokenizeableEntity(string $token): TokenizeableInterface
    {
        $payload = $this->manager->getPayload($token);
        $userIdentifier = $payload['user'];
        /**
         * @var $user TokenizeableInterface
         */
        $user = $this->repository->findOneBy([
            'email' => $userIdentifier,
        ]);
        //TODO check this place, we initially decided to use email as inner token cause they're semi dynamic,
        // when implementing actual token replace this as well
        if ($user) {
            return $user;
        }
        throw new UserNotFoundException();
    }
}
