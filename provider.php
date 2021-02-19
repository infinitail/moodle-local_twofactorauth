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
 * 2FA configration for user.
 *
 * @package     local_twofactorauth
 * @copyright   infinitail
 * @license     http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

require_once __DIR__.'/../../config.php';
require_once __DIR__.'/locallib.php';

global $USER;

$uid            = required_param('uid', PARAM_INT);
$provider       = required_param('provider', PARAM_ALPHANUMEXT);
$otpcode        = optional_param('otpcode', '', PARAM_ALPHANUMEXT);
$deleteprovider = optional_param('deleteprovider', '0', PARAM_INT);

if (!isloggedin()) {
    print_error('requireloginerror', 'moodle');
}

if (!is_siteadmin($USER)) {
    $uid = $USER->id;
}

$User = \core_user::get_user($uid);
if (empty($User) || $uid === 1 || $User->deleted === 1) {
    print_error('nousersfound', 'moodle');
}

if ($provider === 'base_provider' || !is_file(__DIR__."/provider/{$provider}.php")) {
    print_error('nosuchprovider', 'local_twofactorauth');
}

require_once __DIR__."/provider/{$provider}.php";
$classname = "\\local\\twofactorauth\\provider\\{$provider}";
if (!class_exists($classname)) {
    print_error('providerclassnotfound', 'local_twofactorauth');
}

$Provider = new $classname;
if (!$Provider->is_allowed_account($uid)) {
    print_error('notalloweduser', 'local_twofactorauth');
}

// Pre check
if (!$Provider->pre_check($uid)) {
    $html = $Provider->display_pre_check_error_message();
    goto OUTPUT;
}

$secret = $Provider->load_secret($uid);

$html = '';
// Detect setup completed or not
if ($secret === false) {
    // Start setup
    $secret = $Provider->generate_secret();
    $Provider->save_secret($uid, $secret);

    $html .= '<div>';
    $html .= $Provider->display_setup_form($uid);
    $html .= '</div>';

    $html .= $Provider->display_secret($secret);
} else {
    if ($Provider->is_enabled_account($uid)) {
        // Setup is already completed
        if ($deleteprovider) {
            // TODO: Check 2FA status for prevent avoid 2FA by delete current setting.

            $Provider->delete_account($uid);
            $html .= '<div class="alert alert-success">';
            $html .= '  <p>' . get_string('providerconfigdeleted', 'local_twofactorauth'). '</p>';
            $html .= '  <a class="btn btn-secondary" href="' . new moodle_url('/user/profile.php', ['id' => $uid]) . '">';
            $html .=      get_string('returntoprofile', 'local_twofactorauth');
            $html .= '  </a>';
            $html .= '</div>';
        } else {
            // Display current setting
            //$html .= $Provider->display_installed_message($uid);
            $html  = $Provider->display_installed_message();
            $html .= $Provider->display_secret($secret);
            $html .= '<form method="POST" action="">';
            $html .= '  <button class="btn btn-danger">' . get_string('deleteproviderconfig', 'local_twofactorauth') . '</button>';
            $html .= '  <input type="hidden" name="sesskey" value="'.sesskey().'">';
            $html .= '  <input type="hidden" name="userid" value="' . $uid . '">';
            $html .= '  <input type="hidden" name="provider" value="' . $provider . '">';
            $html .= '  <input type="hidden" name="deleteprovider" value="1">';
            $html .= '</form>';
        }
    } else {
        // Setup is in progress
        if (!empty($otpcode)) {
            require_sesskey();
            // Verify posted OTP code for complete setup.
            $verify = $Provider->verify_code($uid, $otpcode);

            if ($verify === true) {
                // Verify OK
                $html = $Provider->display_setup_verify_success($uid);

                $Provider->expire_code($uid, $otpcode);
                $Provider->verify_account($uid);
            } else {
                // Verify error
                $html  = $Provider->display_setup_verify_error();
                $html .= '<div>';
                $html .=   $Provider->display_setup_form($uid);
                $html .= '</div>';

                $html .= $Provider->display_secret($secret);
            }
        } else {
            // Reload veirication request Page
            $html .= '<div>';
            $html .=   $Provider->display_setup_form($uid);
            $html .= '</div>';

            $html .= $Provider->display_secret($secret);
        }
    }
}

// HTML OUTPUT
OUTPUT:
$PAGE->set_context(context_system::instance());
$PAGE->set_url(new moodle_url('/local/twofactorauth/provider.php', ['uid' => $uid, 'provider' => $provider]));
$PAGE->set_title(get_string('twofactorauthentication', 'local_twofactorauth'));
$PAGE->set_heading(get_string('yourtwofactorauthenticationconfigration', 'local_twofactorauth'));
$heading = get_string($Provider->get_provider_name(), 'local_twofactorauth');
echo $OUTPUT->header();
echo "<h1>{$heading}</h1>";
echo $html;
echo $OUTPUT->footer();
