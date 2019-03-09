<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-02
 * Time: 21:07
 */

namespace PlumTreeSystems\UserBundle\Model;

interface TokenEntityLoaderInterface
{
    public function loadTokenizeableEntity(string $token): TokenizeableInterface;
}
