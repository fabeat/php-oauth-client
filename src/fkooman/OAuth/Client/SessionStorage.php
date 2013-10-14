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

class SessionStorage extends BaseStorage implements StorageInterface
{
    public function __construct()
    {
        parent::__construct();

        if ('' === session_id()) {
            // no session currently exists, start a new one
            session_start();
        }

        if (isset($_SESSION['php-oauth-client'])) {
            $this->storage = $_SESSION['php-oauth-client'];
        }
    }

    public function __destruct()
    {
        $_SESSION['php-oauth-client'] = $this->storage;
    }
}
