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
 * @package     local_twofactorauth
 * @copyright   infinitail
 * @license     http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

/**
 * Apply 2FA check to file download
 * To enable this function, add "$CFG->customscripts = '/path/to/customscripts/';" to config.php
 */

// Check 2FA status
require_once __DIR__.'/../locallib.php';
if (local\twofactorauth\locallib::check_2fa_compliance() === false) {
    die('Two Factor Authentication is required!');
}

// Pass to regular function
require_once $CFG->dirroot.'/pluginfile.php';