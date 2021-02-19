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
 * Display QR code
 *
 * @package     local_twofactorauth
 * @copyright   infinitail
 * @license     http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

require_once __DIR__.'/../../config.php';
require_once __DIR__.'/vendor/autoload.php';

use Endroid\QrCode\QrCode;

if (!isloggedin()) {
    die();
}

global $CFG;

$code = required_param('code', PARAM_RAW);
$rawcode = base64_decode($code);

$qrCode = new QrCode($rawcode);
header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();
