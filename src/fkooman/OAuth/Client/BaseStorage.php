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

class BaseStorage implements StorageInterface
{
    /** @var array */
    protected $storage;

    public function __construct()
    {
        $this->storage = array(
            'access_token' => array(),
            'refresh_token' => array(),
            'state' => array(),
        );
    }

    public function getAccessToken($clientConfigId, Context $context)
    {
        $returnTokens = array();

        foreach ($this->storage['access_token'] as $t) {
            $token = new AccessToken(json_decode($t, true));
            if ($clientConfigId !== $token->getClientConfigId()) {
                continue;
            }
            if ($context->getUserId() !== $token->getUserId()) {
                continue;
            }
            if (!$token->getScope()->hasScope($context->getScope())) {
                continue;
            }
            $returnTokens[] = $token;
        }

        return $returnTokens;
    }

    public function storeAccessToken(AccessToken $accessToken)
    {
        $this->storage['access_token'][] = json_encode($accessToken->toArray());

        return true;
    }

    public function deleteAccessToken(AccessToken $accessToken)
    {
        foreach ($this->storage['access_token'] as $k => $t) {
            $token = new AccessToken(json_decode($t, true));
            if (0 !== $token->compareTo($accessToken)) {
                continue;
            }
            unset($this->storage['access_token'][$k]);

            return true;
        }

        return false;
    }

    public function getRefreshToken($clientConfigId, Context $context)
    {
        $returnTokens = array();

        foreach ($this->storage['refresh_token'] as $t) {
            $token = new RefreshToken(json_decode($t, true));
            if ($clientConfigId !== $token->getClientConfigId()) {
                continue;
            }
            if ($context->getUserId() !== $token->getUserId()) {
                continue;
            }
            if (!$token->getScope()->hasScope($context->getScope())) {
                continue;
            }
            $returnTokens[] = $token;
        }

        return $returnTokens;
    }

    public function storeRefreshToken(RefreshToken $refreshToken)
    {
        $this->storage['refresh_token'][] = json_encode($refreshToken->toArray());

        return true;
    }

    public function deleteRefreshToken(RefreshToken $refreshToken)
    {
        foreach ($this->storage['refresh_token'] as $k => $t) {
            $token = new RefreshToken(json_decode($t, true));
            if (0 !== $token->compareTo($refreshToken)) {
                continue;
            }
            unset($this->storage['refresh_token'][$k]);

            return true;
        }

        return false;
    }

    public function getState($clientConfigId, Context $context)
    {
        $returnTokens = array();

        foreach ($this->storage['state'] as $t) {
            $token = new State(json_decode($t, true));
            if ($clientConfigId !== $token->getClientConfigId()) {
                continue;
            }
            if ($context->getUserId() !== $token->getUserId()) {
                continue;
            }
            if (!$token->getScope()->hasScope($context->getScope())) {
                continue;
            }

            $returnTokens[] = $token;
        }

        return $returnTokens;
    }

    public function storeState(State $state)
    {
        $this->storage['state'][] = json_encode($state->toArray());

        return true;
    }

    public function deleteState(State $state)
    {
        foreach ($this->storage['state'] as $k => $t) {
            $token = new State(json_decode($t, true));
            if (0 !== $token->compareTo($state)) {
                continue;
            }
            unset($this->storage['state'][$k]);

            return true;
        }

        return false;
    }
}
