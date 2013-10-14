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

class AccessTokenTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessToken()
    {
        $accessToken = new AccessToken(
            array(
                'client_config_id' => 'foo',
                'user_id' => 'foouser',
                'scope' => array('foo', 'bar'),
                'issue_time' => 123456789,
                'access_token' => 'foo_access_token',
                'token_type' => 'bearer',
                'expires_in' => 3600
            )
        );
        $this->assertEquals(
            array(
                'client_config_id' => 'foo',
                'user_id' => 'foouser',
                'scope' => array('foo', 'bar'),
                'issue_time' => 123456789,
                'access_token' => 'foo_access_token',
                'token_type' => 'bearer',
                'expires_in' => 3600
            ),
            $accessToken->toArray()
        );
    }
}
