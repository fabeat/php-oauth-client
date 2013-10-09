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
        foreach ($this->storage['access_token'] as $t) {
            $token = unserialize($t);
            if ($clientConfigId !== $token->getClientConfigId()) {
                continue;
            }
            if ($context->getUserId() !== $token->getUserId()) {
                continue;
            }
            if (!$token->getScope()->hasScope($context->getScope())) {
                continue;
            }

            return $token;
        }

        return false;
    }

    public function storeAccessToken(AccessToken $accessToken)
    {
        array_push($this->storage['access_token'], serialize($accessToken));

        return true;
    }

    public function deleteAccessToken(AccessToken $accessToken)
    {
        foreach ($this->storage['access_token'] as $k => $t) {
            $token = unserialize($t);
            if (!$token->isEqual($accessToken)) {
                continue;
            }
            unset($this->storage['access_token'][$k]);

            return true;
        }

        return false;
    }

    public function getRefreshToken($clientConfigId, Context $context)
    {
        foreach ($this->storage['refresh_token'] as $t) {
            $token = unserialize($t);
            if ($clientConfigId !== $token->getClientConfigId()) {
                continue;
            }
            if ($context->getUserId() !== $token->getUserId()) {
                continue;
            }
            if (!$token->getScope()->hasScope($context->getScope())) {
                continue;
            }

            return $token;
        }

        return false;
    }

    public function storeRefreshToken(RefreshToken $refreshToken)
    {
        array_push($this->storage['refresh_token'], serialize($refreshToken));

        return true;
    }

    public function deleteRefreshToken(RefreshToken $refreshToken)
    {
        foreach ($this->storage['refresh_token'] as $k => $t) {
            $token = unserialize($t);
            if (!$token->isEqual($refreshToken)) {
                continue;
            }
            unset($this->storage['refresh_token'][$k]);

            return true;
        }

        return false;
    }

    public function getState($clientConfigId, $state)
    {
        foreach ($this->storage['state'] as $s) {
            $sessionState = unserialize($s);

            if ($clientConfigId !== $sessionState->getClientConfigId()) {
                continue;
            }
            if ($state !== $sessionState->getState()) {
                continue;
            }

            return $sessionState;
        }

        return false;
    }

    public function storeState(State $state)
    {
        array_push($this->storage['state'], serialize($state));

        return true;
    }

    public function deleteStateForContext($clientConfigId, Context $context)
    {
        foreach ($this->storage['state'] as $k => $s) {
            $state = unserialize($s);
            if ($clientConfigId !== $state->getClientConfigId()) {
                continue;
            }
            if ($context->getUserId() !== $state->getUserId()) {
                continue;
            }
            unset($this->storage['state'][$k]);

            return true;
        }

        return false;
    }

    public function deleteState(State $state)
    {
        foreach ($this->storage['state'] as $k => $s) {
            $sessionState = unserialize($s);
            if (!$sessionState->isEqual($state)) {
                continue;
            }
            unset($this->storage['state'][$k]);

            return true;
        }

        return false;
    }
}
