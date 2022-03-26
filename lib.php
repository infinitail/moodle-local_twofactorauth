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

require_once __DIR__.'/locallib.php';

// Avoid problem after installation...
if (!is_file(__DIR__.'/vendor/autoload.php')) {
    print_error('runcomposer', 'local_twofactorauth');
}

/**
 * My profile page customization
 * Add user 2FA setting links
 */
function local_twofactorauth_myprofile_navigation(\core_user\output\myprofile\tree $tree, $user, $iscurrentuser) {
    global $DB, $USER;

    // Display not allowed if non admin user want to see other user.
    if (!is_siteadmin($USER) && !$iscurrentuser) {
        return;
    }

    $pluginsettings = get_config('local_twofactorauth');

    if ($pluginsettings->enable_twofactorauth &&
        (is_siteadmin($user) || (!is_siteadmin($user) && $pluginsettings->allow_non_admin))) {
        $category = new core_user\output\myprofile\category(
            'local_twofactorauth',
            get_string('myprofilecategoryname', 'local_twofactorauth')
        );
        $tree->add_category($category);

        // Display user's status and configuration links.
        $providers = local\twofactorauth\locallib::get_providers();

        foreach ($providers as $id=>$provider) {
            if ($pluginsettings->{"allow_{$provider}"}) {
                $record = $DB->get_record('local_2fa_users', ['userid'=>$user->id, 'providerid'=>$id]);
                $status = (!empty($record) && !empty($record->verified))
                    ? '<span class="badge badge-success">' . get_string('enabled', 'local_twofactorauth') . '</span>'
                    : '<span class="badge badge-secondary">' . get_string('disabled', 'local_twofactorauth') . '</span>';
                $node = new core_user\output\myprofile\node(
                    'local_twofactorauth',
                    "{$provider}_node",
                    get_string($provider, 'local_twofactorauth'),
                    null,
                    new moodle_url('/local/twofactorauth/provider.php', ['uid'=>$user->id, 'provider'=>$provider]),
                    get_string('status') . ': ' . $status
                );
                $tree->add_node($node);
            }
        }

        /* TODO: Add trusted device function
        // Trust device and avoid two factor authenticaton
        $node = new core_user\output\myprofile\node(
            'local_twofactorauth',
            'trustdevice',
            get_string('trustdevice', 'local_twofactorauth'),
            null,
            new moodle_url('/local/twofactorauth/trustdevice.php', ['uid'=>$user->id]),
            get_string('status')
        );
        $tree->add_node($node);
        */
    }
}

/**
 * Check two factor auth status and display form
 * (HACK navigation menu display function)
 */
function local_twofactorauth_extend_navigation(\global_navigation $nav) {
    global $CFG, $USER, $PAGE;
    //return; // DEBUG

    if (local\twofactorauth\locallib::is_2fa_required() === true) {
        // Skip if in provider selector page for avoid infinite loop
        $url = parse_url(new moodle_url('/local/twofactorauth/selector.php'));
        if ($_SERVER['REQUEST_URI'] !== $url['path']) {
            // Display 2FA provider selector
            redirect(new moodle_url('/local/twofactorauth/selector.php'));
        }
    }
}
