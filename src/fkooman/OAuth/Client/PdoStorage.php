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

class PdoStorage extends BaseStorage implements StorageInterface
{
    /** @var PDO */
    private $db;

    /** @var bool */
    private $cleanSlate;

    public function __construct(PDO $db)
    {
        parent::__construct();

        $this->db = $db;

        $stmt = $this->db->prepare(
            'SELECT data FROM tokens WHERE user_id = :user_id'
        );
        $stmt->bindValue(':user_id', $context->getUserId(), PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (false === $result) {
            $this->cleanSlate = true;
        } else {
            $this->storage = json_decode($result['data']);
            $this->cleanSlate = false;
        }
    }

    public function __destruct()
    {
        if ($cleanSlate) {
            // insert
        } else {
            // update
        }
    }
}
