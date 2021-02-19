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
 * Admin settings
 *
 * @package     local_twofactorauth
 * @copyright   infinitail
 * @license     http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */
defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    /* TODO: create user administration page
    // Add menu to admin > users
    $ADMIN->add('users', new admin_externalpage(
        'twofactorauth_status',
        get_string('pluginname', 'local_twofactorauth'),
        $CFG->wwwroot.'/local/twofactorauth/userstatus.php',
        'moodle/site:config'
    ));
    */

    if (!$ADMIN->fulltree) {
        // Add plugin config page
        $ADMIN->add('localplugins', new admin_externalpage(
            'twofactorauth_config',
            get_string('pluginname', 'local_twofactorauth'),
            $CFG->wwwroot.'/admin/settings.php?section=local_twofactorauth',
            'moodle/site:config'
        ));
    } else {
        $settings = new admin_settingpage('local_twofactorauth', get_string('pluginname', 'local_twofactorauth'));
        $ADMIN->add('localplugins', $settings);

        /* general setting section */
        $settings->add(new admin_setting_heading('local_twofactorauth_general', '', get_string('generalsettings', 'local_twofactorauth')));

        $settings->add(new admin_setting_configselect(
            'local_twofactorauth/enable_twofactorauth',
            get_string('enabletwofactorauth', 'local_twofactorauth'),
            get_string('enabletwofactorauthdesc', 'local_twofactorauth'),
            0,
            [0=>'No', 1=>'Yes']
        ));

        /* Provider select section */
        $settings->add(new admin_setting_heading('local_twofactorauth_services', '', get_string('providers', 'local_twofactorauth')));

        $settings->add(new admin_setting_configselect(
            'local_twofactorauth/allow_otp_app',
            get_string('allowotpapp', 'local_twofactorauth'),
            get_string('allowotpappdesc', 'local_twofactorauth'),
            1,
            [0=>'No', 1=>'Yes']
        ));

        $settings->add(new admin_setting_configselect(
            'local_twofactorauth/allow_email',
            get_string('allowmailonetimecode', 'local_twofactorauth'),
            get_string('allowmailonetimecodedesc', 'local_twofactorauth'),
            1,
            [0=>'No', 1=>'Yes']
        ));

        $settings->add(new admin_setting_configselect(
            'local_twofactorauth/enable_email_default',
            get_string('enableemaildefault', 'local_twofactorauth'),
            get_string('enableemaildefaultdesc', 'local_twofactorauth'),
            1,
            [0=>'No', 1=>'Yes']
        ));

        /* Apply target section */
        $settings->add(new admin_setting_heading('local_twofactorauth_applytarget', '', get_string('applytarget', 'local_twofactorauth')));

        $settings->add(new admin_setting_configselect(
            'local_twofactorauth/allow_non_admin',
            get_string('allowtononadmin', 'local_twofactorauth'),
            get_string('allowtononadmindesc', 'local_twofactorauth'),
            1,
            [0=>'No', 1=>'Yes']
        ));

        $settings->add(new admin_setting_configselect(
            'local_twofactorauth/force_to_use_target',
            get_string('forcetousetarget', 'local_twofactorauth'),
            get_string('forcetousetargetdesc', 'local_twofactorauth'),
            0,
            [
                0 => get_string('notforcetoanyone', 'local_twofactorauth'),
                1 => get_string('forcetoadmins', 'local_twofactorauth'),
                2 => get_string('forcetoeveryone', 'local_twofactorauth'),
            ]
        ));

        /* TODO: Add trusted device function
        $settings->add(new admin_setting_configselect(
            'local_twofactorauth/trust_device',
            get_string('trustdevice', 'local_twofactorauth'),
            get_string('trustdevicedesc', 'local_twofactorauth'),
            1,
            [0=>'No', 1=>'Yes']
        ));
        */

        $settings->add(new admin_setting_configtextarea(
            'local_twofactorauth/exclude_ip',
            get_string('excludeip', 'local_twofactorauth'),
            get_string('excludeipdesc', 'local_twofactorauth'),
            "10.0.0.0/8\n172.16.0.0/12\n192.168.0.0/16"
        ));
    }
}