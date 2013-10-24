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

class RefreshToken extends Token
{
    /** @var string */
    private $refreshToken;

    public function __construct($issueTime, $refreshToken)
    {
        $this->setIssueTime($issueTime);
        $this->setRefreshToken($refreshToken);
    }

    public function setRefreshToken($refreshToken)
    {
        if (!is_string($refreshToken)) {
              throw new TokenException("refresh_token needs to be a string");
        }
        if (0 >= strlen($refreshToken)) {
            throw new TokenException("refresh_token needs to be non-empty");
        }
        $this->refreshToken = $refreshToken;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    public function equals(RefreshToken $that)
    {
        if ($this->getIssueTime() !== $that->getIssueTime()) {
            return false;
        }
        if ($this->getRefreshToken() !== $that->getRefreshToken()) {
            return false;
        }

        return true;
    }

    public static function fromArray(array $data)
    {
        foreach (array('issue_time', 'refresh_token') as $key) {
            if (!array_key_exists($key, $data)) {
                throw new TokenException(sprintf("required key '%s' missing", $key));
            }
        }

        return new self($data['issue_time'], $data['refresh_token']);
    }

    public function toArray()
    {
        return array(
            "refresh_token" => $this->getRefreshToken(),
            "issue_time" => $this->getIssueTime()
        );
    }
}
