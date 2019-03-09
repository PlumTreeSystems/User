<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-03
 * Time: 17:10
 */

namespace PlumTreeSystems\UserBundle\Model;

interface TimeoutableInterface
{
    public function getFailedLoginCount(): int;
    public function clearFailedLoginCount();
    public function incrementFailedLoginCount();
    public function setTimeout();
    public function isTimedOut(): bool;
}
