<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-02-05
 * Time: 18:09
 */

namespace PlumTreeSystems\UserBundle\Repository;

class TokenUserRepository extends \Doctrine\ORM\EntityRepository
{
//    public function getByToken($token)
//    {
//        $qb = $this->getEntityManager()->createQueryBuilder();
//        $querry = $qb
//            ->select('tu')
//            ->from(TokenUser::class, 'tu')
//            ->where('tu.token = ?1')
//            ->setParameter(1, $token)
//            ->getQuery();
//        return $querry->execute();
//    }
}
