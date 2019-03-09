<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-02
 * Time: 17:44
 */

namespace PlumTreeSystems\UserBundle\Service;

use PlumTreeSystems\UserBundle\Model\TokenizeableInterface;
use PlumTreeSystems\UserBundle\Model\TokenManagerInterface;

class JWTManager implements TokenManagerInterface
{

    private $alg = 'HS256';
    private $typ = 'JWT';
    private $secret = '';

    /**
     * Creates token from payload
     * @param TokenizeableInterface $user
     * @return string
     */
    public function createToken(TokenizeableInterface $user): string
    {
        $payload = $this->encodeData($this->getHeader()).'.'.$this->encodeData($user->getToken());
        $signature = $this->encodeSignature($payload, $this->secret);
        return $payload.'.'.$signature;
    }

    /**
     * @param string $token
     * @return boolean
     */
    public function isValid(string $token): bool
    {
        $parts = explode('.', $token);
        if (sizeof($parts) != 3) {
            return false;
        }
        $signature = $this->encodeSignature($parts[0].'.'.$parts[1], $this->secret);
        return $signature === $parts[2];
    }

    /**
     * Extracts payload from token
     * @param string $token
     * @return mixed
     */
    public function getPayload(string $token)
    {
        $parts = explode('.', $token);
        return json_decode(base64_decode($parts[1]), true);
    }

    private function getHeader()
    {
        return [
            'alg' => $this->alg,
            'typ' => $this->typ
        ];
    }

    private function encodeData($data): string
    {
        return base64_encode(json_encode($data));
    }

    private function encodeSignature($data, $secret): string
    {
        return base64_encode(hash_hmac('sha256', $data, $secret));
    }
}
