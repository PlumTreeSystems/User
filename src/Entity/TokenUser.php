<?php
/**
 * Created by PhpStorm.
 * User: marius
 * Date: 2018-02-05
 * Time: 17:41
 */

namespace PlumTreeSystems\UserBundle\Entity;

use DateTime;
use PlumTreeSystems\UserBundle\Exception\TokenTypeDoesNotExistException;
use PlumTreeSystems\UserBundle\Model\TokenizeableInterface;

abstract class TokenUser extends User implements TokenizeableInterface
{
    const TOKEN_TYPE_INVITE = 'INVITE';

    /*
     * @var string
     */
    private $authToken;

    private $tempToken;

    private $tempTokenType;

    /**
     * @var DateTime
     */
    private $tempTokenCreationDate;
    /**
     * TokenUser constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getToken(): string
    {
        //TODO replace this
        return $this->email;
    }

    public function getTempToken()
    {
        return $this->tempToken;
    }

    public function setTempToken($tempToken)
    {
        $this->tempToken = $tempToken;
    }

    /**
     * @return mixed
     */
    public function getTempTokenType()
    {
        return $this->tempTokenType;
    }

    /**
     * @param mixed $tempTokenType
     * @throws TokenTypeDoesNotExistException
     */
    public function setTempTokenType($tempTokenType)
    {
        $availableTokens = $this->defineTokens();
        if (!(in_array($tempTokenType, $availableTokens))) {
            throw new TokenTypeDoesNotExistException();
        }
        $this->tempTokenType = $tempTokenType;
    }

    /**
     * @return mixed
     */
    public function getTempTokenCreationDate()
    {
        return $this->tempTokenCreationDate;
    }

    /**
     * @param mixed $tempTokenCreationDate
     */
    public function setTempTokenCreationDate($tempTokenCreationDate)
    {
        $this->tempTokenCreationDate = $tempTokenCreationDate;
    }

    protected function defineTokens()
    {
        return [
            self::TOKEN_TYPE_INVITE
        ];
    }
}
