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

use PDO;

class PdoStorage extends BaseStorage implements StorageInterface
{
    /** @var PDO */
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAccessToken($clientConfigId, Context $context)
    {
        $stmt = $this->db->prepare(
            'SELECT data FROM access_tokens WHERE user_id = :user_id'
        );
        $stmt->bindValue(':user_id', $context->getUserId(), PDO::PARAM_STR);
        $stmt->execute();
        $this->storage['access_token'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return parent::getAccessToken($clientConfigId, $context);
    }

    public function storeAccessToken(AccessToken $accessToken)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO access_tokens (user_id, data) VALUES(:user_id, :data)'
        );
        $stmt->bindValue(':user_id', $accessToken->getUserId(), PDO::PARAM_STR);
        $stmt->bindValue(':data', json_encode($accessToken->toArray()), PDO::PARAM_STR);
        $stmt->execute();

        return 1 === $stmt->rowCount();
    }

    public function deleteAccessToken(AccessToken $accessToken)
    {
        $stmt = $this->db->prepare(
            'DELETE FROM access_tokens WHERE user_id = :user_id AND data = :data'
        );
        $stmt->bindValue(':user_id', $accessToken->getUserId(), PDO::PARAM_STR);
        $stmt->bindValue(':data', json_encode($accessToken->toArray()), PDO::PARAM_STR);
        $stmt->execute();

        return 1 === $stmt->rowCount();
    }

    public function getRefreshToken($clientConfigId, Context $context)
    {
        $stmt = $this->db->prepare(
            'SELECT data FROM refresh_tokens WHERE user_id = :user_id'
        );
        $stmt->bindValue(':user_id', $context->getUserId(), PDO::PARAM_STR);
        $stmt->execute();
        $this->storage['refresh_token'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return parent::getRefreshToken($clientConfigId, $context);
    }

    public function storeRefreshToken(RefreshToken $refreshToken)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO refresh_tokens (user_id, data) VALUES(:user_id, :data)'
        );
        $stmt->bindValue(':user_id', $refreshToken->getUserId(), PDO::PARAM_STR);
        $stmt->bindValue(':data', json_encode($refreshToken->toArray()), PDO::PARAM_STR);
        $stmt->execute();

        return 1 === $stmt->rowCount();
    }

    public function deleteRefreshToken(RefreshToken $refreshToken)
    {
        $stmt = $this->db->prepare(
            'DELETE FROM refresh_tokens WHERE user_id = :user_id AND data = :data'
        );
        $stmt->bindValue(':user_id', $refreshToken->getUserId(), PDO::PARAM_STR);
        $stmt->bindValue(':data', json_encode($refreshToken->toArray()), PDO::PARAM_STR);
        $stmt->execute();

        return 1 === $stmt->rowCount();
    }

    public function getState($clientConfigId, $stateValue)
    {
        $stmt = $this->db->prepare(
            'SELECT data FROM states WHERE state = :state'
        );
        $stmt->bindValue(':state', $stateValue, PDO::PARAM_STR);
        $stmt->execute();
        $this->storage['state'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return parent::getState($clientConfigId, $stateValue);
    }

    public function storeState(State $state)
    {
        $stmt = $this->db->prepare(
            'INSERT INTO states (user_id, data) VALUES(:user_id, :data)'
        );
        $stmt->bindValue(':user_id', $state->getUserId(), PDO::PARAM_STR);
        $stmt->bindValue(':data', json_encode($state->toArray()), PDO::PARAM_STR);
        $stmt->execute();

        return 1 === $stmt->rowCount();
    }

    public function deleteState(State $state)
    {
        $stmt = $this->db->prepare(
            'DELETE FROM states WHERE user_id = :user_id AND data = :data'
        );
        $stmt->bindValue(':user_id', $state->getUserId(), PDO::PARAM_STR);
        $stmt->bindValue(':data', json_encode($state->toArray()), PDO::PARAM_STR);
        $stmt->execute();

        return 1 === $stmt->rowCount();
    }
}
