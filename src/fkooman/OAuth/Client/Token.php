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

class Token
{
    /** @var int */
    private $issueTime;

    public function setIssueTime($issueTime)
    {
        if (!is_int($issueTime)) {
            throw new TokenException("issue_time must be integer");
        }
        if (0 >= $issueTime) {
            throw new TokenException("issue_time must be positive");
        }
        $this->issueTime = $issueTime;
    }

    public function getIssueTime()
    {
        return $this->issueTime;
    }
}
