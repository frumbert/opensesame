<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'auth_none', language 'en', branch 'MOODLE_20_STABLE'
 *
 * @package   auth_opensesame_
 * @copyright 2016 onwards Tim St.Clair  {@link http://github.com/frumbert/}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['auth_opensesame_encryptionkey'] = 'Encryption key';
$string['auth_opensesame_encryptionkey_desc'] = 'Ensure this matches the key on the opening server';

$string['auth_opensesame_hmacsalt'] = 'HMAC Salt';
$string['auth_opensesame_hmacsalt_desc'] = 'Ensure this matches the salt on the opening server';

$string['auth_opensesamedescription'] = 'Uses openssl encrypted user id to log a user on and open a course';

$string['pluginname'] = 'Open Sesame (SSO)';

$string['auth_opensesame_logoffurl'] = 'Logoff Url';
$string['auth_opensesame_logoffurl_desc'] = 'Url to redirect to if the user presses Logoff';
