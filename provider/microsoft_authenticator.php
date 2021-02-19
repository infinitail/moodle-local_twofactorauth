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

namespace local\twofactorauth\provider;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/base_provider.php';

/**
 * Microsoft Auehtnticator use exact same algo with Google Authenticator
 */
class microsoft_authenticator extends base_provider {
    public function get_provider_id()
    {
        return 2;
    }

    public function get_provider_name()
    {
        return 'microsoft_authenticator';
    }

    public function display_setup_form($user_id)
    {

        $html = parent::display_setup_form($user_id);

        $html .= '<form method="POST" action="">';
        $html .= '  <div class="form-group">';
        $html .= '    <label for="otp-code">';
        $html .=        get_string('providersetupformmessage_microsoft_authenticator', 'local_twofactorauth');
        $html .= '    </label>';
        $html .= '    <input type="text" class="form-control" id="otp-code" name="otpcode" placeholder="0000000" />';
        $html .= '  </div>';
        $html .= '  <button type="submit" class="btn btn-primary">';
        $html .=      get_string('verifycode', 'local_twofactorauth');
        $html .= '  </button>';
        $html .= '  <input type="hidden" name="sesskey" value="'.sesskey().'">';
        $html .= '</form>';

        return $html;
    }

    public function generate_secret()
    {
        $Authenticator = new \PHPGangsta_GoogleAuthenticator();
        return $Authenticator->createSecret();
    }

    public function display_secret($secret)
    {
        global $CFG, $SITE;

        // Generate QR Image URL
        // OTP code format - https://github.com/aspnet/AspNetCore.Docs/blob/master/aspnetcore/security/authentication/identity-configuration/sample/Areas/Identity/Pages/Account/Manage/EnableAuthenticator.cshtml.cs
        $encoded_site_name = urlencode($SITE->fullname);
        $code = base64_encode("otpauth://totp/{$encoded_site_name}:{$CFG->wwwroot}?secret={$secret}&digits=6");
        $qrurl  = "{$CFG->wwwroot}/local/twofactorauth/qr.php?code={$code}";

        $html  = '<h2>' . get_string('qrcode', 'local_twofactorauth') . '</h2>';
        $html .= '<img src="'.$qrurl.'"><div>Secret: '.$secret.'</div>';
        return $html;
    }

    public function generate_code($user_id, $secret)
    {
        global $CFG;

        $Authenticator = new \PHPGangsta_GoogleAuthenticator();
        $code = $Authenticator->getCode($secret);
        return $code;
    }

    public function verify_code($user_id, $code)
    {
        $secret = $this->load_secret($user_id);

        $Authenticator = new \PHPGangsta_GoogleAuthenticator();
        return $Authenticator->verifyCode($secret, $code, 120/30);
    }
}