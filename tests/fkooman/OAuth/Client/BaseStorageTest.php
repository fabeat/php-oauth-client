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

use fkooman\OAuth\Common\Scope;

class BaseStorageTest extends \PHPUnit_Framework_TestCase
{
    /** @var fkooman\OAuth\Client\StorageInterface */
    private $s;

    private $accessTokens;
    private $refreshTokens;
    private $states;

    public function setUp()
    {
        //$this->s = new BaseStorage();
        $db = new \PDO("sqlite:/tmp/client.sqlite");
        $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->s = new PdoStorage($db);

        $this->accessTokens = array();
        $this->refreshTokens = array();
        $this->states = array();

        $this->accessTokens[] = new AccessToken(
            array(
                'client_config_id' => 'foo_client',
                'user_id' => 'foo@example.org',
                'scope' => array('foo', 'bar'),
                'issue_time' => 1357913579,
                'token_type' => 'bearer',
                'access_token' => 'foo_access_token_value',
                'expires_in' => 3600
            )
        );
        $this->accessTokens[] = new AccessToken(
            array(
                'client_config_id' => 'foo_client',
                'user_id' => 'bar@example.org',
                'scope' => array('foo', 'baz'),
                'issue_time' => 1234567890,
                'token_type' => 'bearer',
                'access_token' => 'bar_access_token_value',
                'expires_in' => 3600
            )
        );
        $this->accessTokens[] = new AccessToken(
            array(
                'client_config_id' => 'bar_client',
                'user_id' => 'foo@example.org',
                'scope' => array('foo', 'bar'),
                'issue_time' => 1468024580,
                'token_type' => 'bearer',
                'access_token' => 'foo_bar_access_token_value',
                'expires_in' => 3600
            )
        );

        $this->refreshTokens[] = new RefreshToken(
            array(
                'client_config_id' => 'foo_client',
                'user_id' => 'foo@example.org',
                'scope' => array('foo', 'bar'),
                'issue_time' => time() - 100,
                'refresh_token' => 'refresh_token_value'
            )
        );

        $this->states[] = new State(
            array(
                'client_config_id' => 'foo_client',
                'user_id' => 'foo@example.org',
                'scope' => array('foo', 'bar'),
                'issue_time' => time() - 100,
                'state' => 'state_value'
            )
        );

        foreach ($this->accessTokens as $token) {
            $this->s->storeAccessToken($token);
        }

        foreach ($this->refreshTokens as $token) {
            $this->s->storeRefreshToken($token);
        }

        foreach ($this->states as $token) {
            $this->s->storeState($token);
        }
    }

    public function testGetExistingAccessToken()
    {
        $accessToken = $this->s->getAccessToken('foo_client', new Context("foo@example.org", array("foo", "bar")));
        $this->assertEquals(0, $accessToken->getScope()->compareTo(new Scope(array('foo', 'bar'))));
        $this->assertEquals("bearer", $accessToken->getTokenType());
        $this->assertEquals("foo_access_token_value", $accessToken->getAccessToken());
    }

    public function testGetNonExistingAccessToken()
    {
        $this->assertFalse($this->s->getAccessToken('foo_client', new Context("foo@example.org", array("foo", "baz"))));
        $this->assertFalse($this->s->getAccessToken('foo_client', new Context("na@example.org", array("foo", "bar"))));
        $this->assertFalse($this->s->getAccessToken('baz_client', new Context("foo@example.org", array("foo", "bar"))));
    }

    public function testDeleteAccessToken()
    {
        $accessToken = $this->s->getAccessToken('foo_client', new Context("foo@example.org", array("foo", "bar")));
        $this->assertInstanceOf('fkooman\OAuth\Client\AccessToken', $accessToken);
        $this->assertTrue($this->s->deleteAccessToken($accessToken));
        $accessToken = $this->s->getAccessToken('foo_client', new Context("foo@example.org", array("foo", "bar")));
        $this->assertFalse($accessToken);
    }

    public function testDeleteNonExistingAccessToken()
    {
        $this->assertFalse(
            $this->s->deleteAccessToken(
                new AccessToken(
                    array(
                        'client_config_id' => 'foo_client',
                        'user_id' => 'foo@example.org',
                        'scope' => array('foo', 'baz'),
                        'issue_time' => 1357913579,
                        'token_type' => 'bearer',
                        'access_token' => 'foo_access_token_value',
                        'expires_in' => 3600
                    )
                )
            )
        );
    }

    public function testGetExistingRefreshToken()
    {
        $refreshToken = $this->s->getRefreshToken('foo_client', new Context("foo@example.org", array("foo", "bar")));
        $this->assertEquals(0, $refreshToken->getScope()->compareTo(new Scope(array("foo", "bar"))));
        $this->assertEquals("refresh_token_value", $refreshToken->getRefreshToken());
    }

    public function testGetNonExistingRefreshToken()
    {
        $this->assertFalse(
            $this->s->getRefreshToken(
                'foo_client',
                new Context("foo@example.org", array("foo", "baz"))
            )
        );
    }

    public function testDeleteRefreshToken()
    {
        $refreshToken = $this->s->getRefreshToken('foo_client', new Context("foo@example.org", array("foo", "bar")));
        $this->assertInstanceOf('fkooman\OAuth\Client\RefreshToken', $refreshToken);
        $this->assertTrue($this->s->deleteRefreshToken($refreshToken));
        $refreshToken = $this->s->getRefreshToken('foo_client', new Context("foo@example.org", array("foo", "bar")));
        $this->assertFalse($refreshToken);
    }
}
