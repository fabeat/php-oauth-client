<?php

/**
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Lesser General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace fkooman\OAuth\Client;

class AccessToken extends Token
{
    /** @var string */
    private $accessToken;

    /** @var string */
    private $tokenType;

    /** @var int */
    private $expiresIn;

    public function __construct($issueTime, $accessToken, $tokenType, $expiresIn = null)
    {
        $this->setIssueTime($issueTime);
        $this->setAccessToken($accessToken);
        $this->setTokenType($tokenType);
        $this->setExpiresIn($expiresIn);
    }

    public function setAccessToken($accessToken)
    {
        if (!is_string($accessToken)) {
            throw new TokenException("access_token needs to be a string");
        }
        if (0 >= strlen($accessToken)) {
            throw new TokenException("access_token needs to be non-empty");
        }
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setTokenType($tokenType)
    {
        if (!is_string($tokenType)) {
            throw new TokenException("token_type needs to be a string");
        }
        if (0 >= strlen($tokenType)) {
            throw new TokenException("token_type needs to be non-empty");
        }
        // Google uses "Bearer" instead of "bearer", so we need to lowercase it...
        if (!in_array(strtolower($tokenType), array("bearer"))) {
            throw new TokenException("unsupported token type");
        }
        $this->tokenType = $tokenType;
    }

    public function getTokenType()
    {
        return $this->tokenType;
    }

    public function setExpiresIn($expiresIn)
    {
        if (null !== $expiresIn) {
            if (!is_int($expiresIn)) {
                throw new TokenException("expires_in should be an integer");
            }
            if (0 >= $expiresIn) {
                throw new TokenException("expires_in should be positive");
            }
        }
        $this->expiresIn = $expiresIn;
    }

    public function getExpiresIn()
    {
        return $this->expiresIn;
    }

    public function equals(AccessToken $that)
    {
        if ($this->getIssueTime() !== $that->getIssueTime()) {
            return false;
        }
        if ($this->getAccessToken() !== $that->getAccessToken()) {
            return false;
        }
        if ($this->getTokenType() !== $that->getTokenType()) {
            return false;
        }
        if ($this->getExpiresIn() !== $that->getExpiresIn()) {
            return false;
        }

        return true;
    }

    public function toArray()
    {
        $toArray = array(
            "issue_time" => $this->getIssueTime(),
            "access_token" => $this->getAccessToken(),
            "token_type" => $this->getTokenType(),
        );
        if (null !== $this->getExpiresIn()) {
            $toArray['expires_in'] = $this->getExpiresIn();
        }

        return $toArray;
    }

    public static function fromArray(array $data)
    {
        foreach (array('issue_time', 'access_token', 'token_type') as $key) {
            if (!array_key_exists($key, $data)) {
                throw new TokenException(sprintf("required key '%s' missing", $key));
            }
        }
        $expiresIn = array_key_exists('expires_in', $data) ? $data['expires_in'] : null;

        return new self(
            $data['issue_time'],
            $data['access_token'],
            $data['token_type'],
            $expiresIn
        );
    }
}
