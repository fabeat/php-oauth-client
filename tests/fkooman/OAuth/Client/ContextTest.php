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

class ContextTest extends \PHPUnit_Framework_TestCase
{
    public function testContextNoScope()
    {
        $context = new Context("foo", "foo@example.org");
        $this->assertEquals("foo", $context->getClientConfigId());
        $this->assertEquals("foo@example.org", $context->getUserId());
        $this->assertEquals(array(), $context->getScope()->toArray());
        $this->assertEquals(
            array(
                "client_config_id" => "foo",
                "user_id" => "foo@example.org",
                "scope" => array()
            ),
            $context->toArray()
        );
    }

    public function testContextWithScope()
    {
        $context = new Context("foo", "foo@example.org", array("foo", "bar"));
        $this->assertEquals("foo", $context->getClientConfigId());
        $this->assertEquals("foo@example.org", $context->getUserId());
        $this->assertEquals(array("foo", "bar"), $context->getScope()->toArray());
        $this->assertEquals(
            array(
                "client_config_id" => "foo",
                "user_id" => "foo@example.org",
                "scope" => array("foo", "bar")
            ),
            $context->toArray()
        );
    }

    public function testContextFromArray()
    {
        $context = Context::fromArray(
            array(
                "client_config_id" => "foo",
                "user_id" => "foo@example.org",
                "scope" => array("foo", "bar")
            )
        );
        $this->assertEquals("foo", $context->getClientConfigId());
        $this->assertEquals("foo@example.org", $context->getUserId());
        $this->assertEquals(array("foo", "bar"), $context->getScope()->toArray());
        $this->assertEquals(
            array(
                "client_config_id" => "foo",
                "user_id" => "foo@example.org",
                "scope" => array("foo", "bar")
            ),
            $context->toArray()
        );
    }
}
