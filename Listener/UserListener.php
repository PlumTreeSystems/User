<?php
/**
 * Created by PhpStorm.
 * User: matas
 * Date: 2017-01-16
 * Time: 15:45
 */

namespace PlumTreeSystems\UserBundle\Listener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use PlumTreeSystems\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserListener
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AdminUserListener constructor.
     * @param $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        $this->setPassword($user);
    }

    public function preUpdate(User $user, PreUpdateEventArgs $event)
    {
        $this->setPassword($user);
    }

    private function setPassword(User $user)
    {
        if ($user->getPlainPassword()) {
            $encoded = $this->encoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($encoded);
        }
    }
}
