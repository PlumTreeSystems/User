<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-02
 * Time: 20:51
 */

namespace PlumTreeSystems\UserBundle\Model;

interface TokenizeableInterface
{
    public function getToken(): string;
}
