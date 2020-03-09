<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-02
 * Time: 16:53
 */

namespace PlumTreeSystems\UserBundle\Model;

interface TokenManagerInterface
{
    public function createToken(TokenizeableInterface $user): string;
    public function isValid(string $token): bool;
    public function getPayload(string $token);
    public function isExpired(string $token): bool;
}
