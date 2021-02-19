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

namespace local\twofactorauth;
require_once __DIR__.'/../../config.php';

class locallib
{
    /**
     * Get all 2FA Providers
     * TODO: generate automatically
     *
     * @param void
     * @return array
     */
    public static function get_providers()
    {
        return [
            4 => 'otp_app',
            3 => 'email',
        ];
    }

    /**
     * Check 2FA is required, and 2FA is completed
     *
     * @param void
     * @return bool
     */
    public static function check_2fa_compliance()
    {
        if (self::is_2fa_required() === false || !empty($USER->twofactorauth)) {
            return true;
        }

        return false;
    }

    /**
     * Check specified user's 2FA configuration status
     *
     * @param int $user_id
     * @param int $provider_id
     * @return bool
     */
    public static function is_2fa_configured_user(int $user_id, int $provider_id=null)
    {
        global $DB;

        if (is_null($provider_id)) {
            $records = $DB->get_records('local_2fa_users', ['userid' => $user_id]);
        } else {
            $records = $DB->get_records('local_2fa_users', ['userid' => $user_id, 'providerid' => $provider_id]);
        }

        if (!empty($records)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Check current situation that the login user is required to 2FA
     *
     * @param void
     * @return bool
     */
    public static function is_2fa_required()
    {
        global $CFG, $USER;
        //return false;   // DEBUG

        // '$CFG->disable2fa = true' disable 2FA login check for emergency
        if (!empty($CFG->disable2fa)) {
            return false;
        }

        // 2FA function is only effective for login users
        if (!isloggedin() || isguestuser()) {
            return false;
        }

        // 2FA already completed
        if (!empty($USER->twofactorauth)) {
            return false;
        }

        $pluginconfig = get_config('local_twofactorauth', null);

        // check 2FA plugin is enabled
        if (!$pluginconfig->enable_twofactorauth) {
            return false;
        }

        // check exclude ip
        if (remoteip_in_list($pluginconfig->exclude_ip)) {
            return false;
        }

        // not allowed to regular user
        if (!$pluginconfig->allow_non_admin && !is_siteadmin($USER)) {
            return false;
        }

        // Check 2FA target user role
        if (!self::is_2fa_configured_user($USER->id)) {
            switch ($pluginconfig->force_to_use_target) {
                default:
                case 0:
                    // not force to anyone
                    return false;
                    break;

                case 1:
                    // force to admin - check admin or not
                    if (!is_siteadmin($USER)) {
                        return false;
                    }
                    break;

                case 2:
                    // force to everyone
                    break;
            }
        }

        return true;
    }
}