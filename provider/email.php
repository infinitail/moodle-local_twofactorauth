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
require_once __DIR__.'/../../../lib/phpmailer/moodle_phpmailer.php';

class email extends base_provider {
    const ENABLED = '1';

    public function get_provider_id()
    {
        return 3;
    }

    public function get_provider_name()
    {
        return 'email';
    }

    public function pre_check(int $user_id)
    {
        $User = \core_user::get_user($user_id);

        if (empty($User->email) || $User->emailstop === self::ENABLED) {
            return false;
        }

        list($local_part, $domain) = explode('@', $User->email);
        if ($domain === 'localhost.localdomain') {
            return false;
        }

        return true;
    }

    public function display_pre_check_error_message()
    {
        $html  = '<div class="alert alert-danger">';
        $html .= get_string('invalidemail', 'local_twofactorauth');
        $html .= '</div>';
        return $html;
    }

    public function display_setup_form($user_id)
    {
        $success = $this->transmit_code($user_id);

        $User = \core_user::get_user($user_id);

        if ($success) {
            $html  = '<div class="alert alert-info">';
            $html .= get_string('providersetupinstruction_email', 'local_twofactorauth', $User->email);
            $html .= '</div>';
        } else {
            $html  = '<div class="alert alert-warning">';
            $html .= get_string('providersetupinstructionerror_email', 'local_twofactorauth', $User->email) . '<br />';
            $html .= $Mailer->ErrorInfo;
            $html .= '</div>';
        }

        $html .= '<form method="POST" action="">';
        $html .= '  <div class="form-group">';
        $html .= '    <label for="otp-code">';
        $html .=        get_string('providersetupformmessage_email', 'local_twofactorauth');
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

    public function transmit_message($user_id)
    {
        $User = \core_user::get_user($user_id);

        return get_string('transmit_code_email', 'local_twofactorauth', $User->email);
    }

    public function transmit_code($user_id)
    {
        global $CFG;
        $enable_email_default = get_config('local_twofactorauth', 'enable_email_default');

        $secret = $this->load_secret($user_id);

        if ($secret === false && $enable_email_default === self::ENABLED) {
            // Set secret if not present
            $secret = $this->generate_secret();
            $recordid = $this->save_secret($user_id, $secret);
        }

        $otpcode = $this->generate_code($user_id, $secret);

        // send email
        $User = \core_user::get_user($user_id);
        $Mailer = get_mailer();
        $Mailer->setFrom($CFG->noreplyaddress);
        $Mailer->addAddress($User->email);
        $Mailer->Subject = get_string('providersetupemailsubject', 'local_twofactorauth', $CFG->wwwroot);
        $Mailer->Body = get_string('providersetupemailbody', 'local_twofactorauth', ['wwwroot'=>$CFG->wwwroot, 'otpcode'=>$otpcode]);

        return $Mailer->send();
    }

    public function display_secret(string $secret)
    {
        return '';
    }

    public function generate_secret()
    {
        $Authenticator = new \PHPGangsta_GoogleAuthenticator();
        return $Authenticator->createSecret();
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
        return $Authenticator->verifyCode($secret, $code, 600/30);
    }

    public function is_enabled_account(int $user_id)
    {
        $record = $this->get_record($user_id);

        if (!empty($record->secret) && !empty($record->verified)) {
            return true;
        }

        // Force enable if configured to use default and valid email domain
        $enable_email_default = get_config('local_twofactorauth', 'enable_email_default');
        $User = \core_user::get_user($user_id);
        list($local_part, $domain) = explode('@', $User->email);
        if ($enable_email_default === self::ENABLED && $domain !== 'localhost.localdomain') {
            return true;
        }

        return false;
    }
}
