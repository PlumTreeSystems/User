<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-05-02
 * Time: 17:44
 */

namespace PlumTreeSystems\UserBundle\Service;

use DateTime;
use Exception;
use PlumTreeSystems\UserBundle\Model\TokenizeableInterface;
use PlumTreeSystems\UserBundle\Model\TokenManagerInterface;

class JWTManager implements TokenManagerInterface
{

    private $alg = 'HS256';

    private $typ = 'JWT';

    protected $secret;

    private $expiryDuration;

    /**
     * JWTManager constructor.
     * @param string $secret
     * @param string $expiryDuration
     */
    public function __construct(string $secret, string $expiryDuration)
    {
        $this->secret = $secret;
        $this->expiryDuration = $expiryDuration;
    }

    /**
     * Creates token from payload
     * @param TokenizeableInterface $user
     * @return string
     * @throws Exception
     */
    public function createToken(TokenizeableInterface $user): string
    {
        $payloadData = [
            'user' => $user->getToken(),
            'expiresOn' => $this->getExpiryDate()
        ];
        $payload = $this->encodeData($this->getHeader()).'.'.$this->encodeData($payloadData);
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
     * @return string
     * @throws Exception
     */
    protected function getExpiryDate() {
        $dur = $this->expiryDuration;
        if ($dur !== '-1') {
            $dateTime = new DateTime($dur);
            return $dateTime->format(DateTime::ISO8601);
        } else {
            return '-1';
        }
    }

    /**
     * @param string $token
     * @return bool
     * @throws Exception
     */
    public function isExpired(string $token): bool
    {
        $payload = $this->getPayload($token);
        if (key_exists('expiresOn', $payload)) {
            if ($payload['expiresOn'] === '-1') {
                return false;
            }
            $expiresOn = new DateTime($payload['expiresOn']);
            $now = new DateTime();
            $expiresOn = $expiresOn->getTimestamp();
            $now = $now->getTimestamp();
            if ($expiresOn < $now) {
                return true;
            }
        }
        return false;
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

    protected function getHeader()
    {
        return [
            'alg' => $this->alg,
            'typ' => $this->typ
        ];
    }

    protected function encodeData($data): string
    {
        return base64_encode(json_encode($data));
    }

    protected function encodeSignature($data, $secret): string
    {
        return base64_encode(hash_hmac('sha256', $data, $secret));
    }
}
