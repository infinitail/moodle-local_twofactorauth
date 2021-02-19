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

/**
 * Extend this abstract and name 'TwoFactorAuthProvider'
 */
abstract class base_provider
{
    /**
     * Get unique provider idnumber
     *
     * @param null
     * @return unsigned tinyint (1-255)
     */
    abstract public function get_provider_id();

    /**
     * Get unique provider name
     *
     * @praram void
     * @return string (varchar 64)
     */
    abstract public function get_provider_name();

    /**
     * Get specified user's record
     *
     * @param int $user_id
     * @return stdClass
     */
    final public function get_record(int $user_id)
    {
        global $DB;

        $where = [
            'userid'     => $user_id,
            'providerid' => $this->get_provider_id(),
        ];

        return $DB->get_record('local_2fa_users', $where);
    }

    /**
     * Is this plugin enabled by site admin?
     *
     * @param void
     * @return bool
     */
    final public function is_site_enabled()
    {
        $is_plugin_enabled = get_config('local_twofactorauth', 'enable_twofactorauth');
        $is_provider_enabled = get_config('local_twofactorauth', 'allow_'.$this->get_provider_name());

        if ($is_plugin_enabled && $is_provider_enabled) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Is allowed to use for this user?
     *
     * @param int $user_id
     * @return bool
     */
    final public function is_allowed_account(int $user_id)
    {
        if ($this->is_site_enabled() !== true) {
            return false;
        }

        $User = \core_user::get_user($user_id);
        if (is_siteadmin($User)) {
            return true;
        }

        $allow_non_admin = get_config('local_twofactorauth', 'allow_non_admin');
        if ($allow_non_admin == true) {
            return true;
        }

        return false;
    }

    /**
     * Is enabled by this user?
     * Check OTP secret and verified colmun value
     *
     * @param int $user_id
     * @return bool
     */
    public function is_enabled_account(int $user_id)
    {
        $record = $this->get_record($user_id);

        if (!empty($record->secret) && !empty($record->verified)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Add entry 2FA setting for this user. But not active.
     *
     * @param int $user_id
     * @return bool
     */
    final public function verify_account(int $user_id)
    {
        global $DB;

        $record = $this->get_record($user_id);

        $data = [
            'id'       => $record->id,
            'verified' => 1,
        ];
        return $DB->update_record_raw('local_2fa_users', $data, false);
    }

    /**
     * Delete 2FA setting from this user
     *
     * @param int $user_id
     * @return bool
     */
    final public function delete_account(int $user_id)
    {
        global $DB;

        $where = [
            'userid'     => $user_id,
            'providerid' => $this->get_provider_id(),
        ];
        $DB->delete_records('local_2fa_users', $where);

        return true;
    }

    /**
     * Check User status before initialize
     *
     * @param int $user_id
     * @return bool
     */
    public function pre_check(int $user_id)
    {
        return true;
    }

    /**
     * Display pre check error message
     *
     * @param void
     * @return string
     */
    public function display_pre_check_error_message()
    {
        return '';
    }

    /**
     * Display message if selected 2FA provider is already configured for user
     *
     * @param void
     * @return string
     */
    public function display_installed_message()
    {
        $html  = '<div class="alert alert-info">';
        $html .= get_string('provideralreadyinstalled', 'local_twofactorauth',
            get_string($this->get_provider_name(), 'local_twofactorauth'));
        $html .= '</div>';

        return $html;
    }

    /**
     * Display setup method and input form
     *
     * @param int $user_id
     * @return string
     */
    public function display_setup_form(int $user_id)
    {
        $html  = '<div class="alert alert-info">';
        $html .= get_string('providersetupinstruction_'.$this->get_provider_name(), 'local_twofactorauth');
        $html .= '</div>';

        return $html;
    }


    /**
     * Display OTP code veirication success while setup
     *
     * @param int $user_id
     * @return string
     */
    public function display_setup_verify_success(int $user_id)
    {
        $html  = '<div class="alert alert-success">';
        $html .= '  <p>' . get_string('providerverifysuccess_'.$this->get_provider_name(), 'local_twofactorauth') . '</p>';
        $html .= '  <a class="btn btn-secondary" href="' . new \moodle_url('/user/profile.php', ['id' => $user_id]) . '">';
        $html .=      get_string('returntoprofile', 'local_twofactorauth');
        $html .= '  </a>';
        $html .= '</div>';

        return $html;
    }


    /**
     * Display OTP code veirication error while setup
     *
     * @param void
     * @return string
     */
    public function display_setup_verify_error()
    {
        $html  = '<div class="alert alert-danger">';
        $html .= get_string('providerverifyerror_'.$this->get_provider_name(), 'local_twofactorauth');
        $html .= '</div>';

        return $html;
    }

    /**
     * Generate secret for initialize
     *
     * @param void
     * @return string
     */
    abstract public function generate_secret();

    /**
     * Display HTML, QR code or something
     *
     * @param string $secret
     * @return string $html
     */
    public function display_secret(string $secret)
    {
        $html = $secret;

        return $html;
    }

    /**
     * Save secret to DB
     * TODO: Encrypt secret
     *
     * @param int $user_id
     * @param string $secret
     * @return bool|int
     */
    final public function save_secret(int $user_id, string $secret)
    {
        global $DB;

        $record = $this->get_record($user_id);

        if (!empty($record)) {
            $this->delete_account($user_id);
        }

        $data = [
            'userid'       => $user_id,
            'providerid'   => $this->get_provider_id(),
            'providername' => $this->get_provider_name(),
            'secret'       => $secret,
            'regtime'      => time(),
        ];
        return $DB->insert_record('local_2fa_users', $data, true, false);
    }

    /**
     * Load secret from DB
     * TODO: Encrypt secret
     *
     * @param int $user_id
     * @return bool|string
     */
    final public function load_secret(int $user_id)
    {
        global $DB;

        $record = $this->get_record($user_id);

        if (!empty($record->secret)) {
            return $record->secret;
        } else {
            return false;
        }
    }

    /**
     * Display message when OTP code is transmitted
     *
     * @param int $user_id
     * @return bool
     */
    public function transmit_message(int $user_id)
    {
        return '';
    }


    /**
     * Transmit generated OTP code via email or something
     *
     * @param int $user_id
     * @return bool
     */
    public function transmit_code(int $user_id)
    {
        // Simple stub
        return true;
    }

    /**
     * Generate OTP code
     *
     * @param int $user_id
     * @param string $secret
     * @return bool|string
     */
    abstract public function generate_code(int $user_id, string $secret);

    /**
     * Save generated OTP code to DB
     *
     * @param int $user_id
     * @param string $code
     * @return void
     */
    final public function save_code(int $user_id, string $code)
    {
        global $DB;

        $record = $this->get_record($user_id);

        $data = [
            'id'               => $record->id,
            'lastotp'          => $code,
            'lastoptissuetime' => time(),
            'lastotpusetime'   => null,
        ];
        $DB->update_record('local_2fa_users', $data, false);
    }

    /**
     * Get last generated OTP code from DB
     *
     * @param int $user_id
     * @return bool|string
     */
    final public function load_code(int $user_id)
    {
        global $DB;

        $record = $this->get_record($user_id);

        if (!empty($record->lastotp) && empty($record->lastotpusetime)) {
            return $record->lastotp;
        } else {
            return false;
        }
    }

    /**
     * Verify OTP code
     *
     * @param int $user_id
     * @param string $code
     * @return bool|string
     */
    abstract public function verify_code(int $user_id, string $code);

    /**
     * Mark last generated OTP code as already used for prevent replay-attack
     *
     * @param int $user_id
     * @param string $code
     * @return void
     */
    final public function expire_code(int $user_id, string $code)
    {
        global $DB;

        $record = $this->get_record($user_id);

        $data = [
            'id'               => $record->id,
            'lastotp'          => $code,
            'lastotpusetime'   => time(),
        ];
        $DB->update_record('local_2fa_users', $data, false);
    }

}
