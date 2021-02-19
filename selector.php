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
 * Show 2FA selector page after regular authentication.
 *
 * @package     local_twofactorauth
 * @copyright   infinitail
 * @license     http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

require_once __DIR__.'/../../config.php';
require_once __DIR__.'/locallib.php';

$selectedprovider = optional_param('provider', null, PARAM_ALPHANUMEXT);
$otpcode          = optional_param('otpcode', null, PARAM_RAW);

if (local\twofactorauth\locallib::is_2fa_required() === false || !empty($USER->twofactorauth)) {
    redirect(new moodle_url('/'));      // already 2FAed
}

if (empty($selectedprovider)) {
    // Default, display 2FA selection
    // Get current user's 2FA config status
    $providers = local\twofactorauth\locallib::get_providers();

    $html = '';
    foreach ($providers as $provider) {
        include_once __DIR__."/provider/{$provider}.php";

        $classname = "\\local\\twofactorauth\\provider\\{$provider}";
        if (!class_exists($classname)) {
            continue;
        }
        $Provider = new $classname;
        if (!$Provider->is_allowed_account($USER->id) || !$Provider->is_enabled_account($USER->id)) {
            continue;
        }

        //$html .= '<div class="alert ">';
        //$html .= get_string($Provider->get_provider_name(), 'local_twofactorauth');
        //$html .= '<button class=btn ptn-primary>' . $Provider->get_provider_name() . '</button>';
        //$html .= '</div>';

        $html .= '<div class="form-check">';
        $html .= '  <input class="form-check-input" type="radio" name="provider" id="' . $Provider->get_provider_name() . '" value="' . $Provider->get_provider_name() . '">';
        $html .= '  <label class="form-check-label" for="' . $Provider->get_provider_name() . '">';
        $html .=      get_string($Provider->get_provider_name(), 'local_twofactorauth');
        $html .= '  </label>';
        $html .= '</div>';
    }

    if (empty($html)) {
        // No configured provider
        $PAGE->set_context(context_system::instance());
        $PAGE->set_url(new moodle_url('/local/twofactorauth/selector.php'));
        $PAGE->set_title($SITE->fullname);
        $PAGE->set_heading(get_string('selectprovider', 'local_twofactorauth'));
        echo $OUTPUT->header();
        echo '<div class="alert alert-danger">';
        echo '<h4 class="alert-heading">' . get_string('noavailableprovider', 'local_twofactorauth') . '</h4>';
        echo '<p>' . get_string('noavailableproviderdesc', 'local_twofactorauth') . '</p>';
        echo '</div>';
        echo $OUTPUT->footer();
        die();
    }

    $PAGE->set_context(context_system::instance());
    $PAGE->set_url(new moodle_url('/local/twofactorauth/selector.php'));
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading(get_string('selectprovider', 'local_twofactorauth'));
    echo $OUTPUT->header();
    echo '<p>' . get_string('selectproviderdesc', 'local_twofactorauth') . '</p>';
    echo '<form method="POST" action="">';
    echo '<div class="form-group">';
    echo $html;
    echo '</div>';
    echo '<button type="submit" class="btn btn-primary mb-2">' . get_string('usethisprovider', 'local_twofactorauth') . '</button>';
    echo '<input type="hidden" name="sesskey" value="' . sesskey() . '" />';
    echo '</form>';
    echo $OUTPUT->footer();
    die();
}

require_sesskey();

// Check provider class
include_once __DIR__."/provider/{$selectedprovider}.php";

$classname = "\\local\\twofactorauth\\provider\\{$selectedprovider}";
if (!class_exists($classname)) {
    error('providerclassnotexist', 'local_twofactorauth');
}
$Provider = new $classname;
if (!$Provider->is_allowed_account($USER->id) || !$Provider->is_enabled_account($USER->id)) {
    error('providerisnotavailable', 'local_twofactorauth');
}

if (!empty($otpcode) && $Provider->verify_code($USER->id, $otpcode)) {
    // OTP code verification success
    $USER->twofactorauth = true;
    $Provider->expire_code($USER->id, $otpcode);

    $PAGE->set_context(context_system::instance());
    $PAGE->set_url(new moodle_url('/local/twofactorauth/selector.php'));
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading(get_string('twofactorauthentication', 'local_twofactorauth'));
    echo $OUTPUT->header();
    echo '<div class="alert alert-success">';
    echo '<h1>' . get_string('challengetwofactorauth', 'local_twofactorauth') . '</h1>';
    echo '<p>';
    echo get_string('verificationsuccess', 'local_twofactorauth');
    echo '</p>';
    echo '</div>';
    echo '<form method="GET" action="' . new moodle_url('/') . '">';
    echo '<button type="submit" class="btn btn-primary mb-2">' . get_string('continue') . '</button>';
    echo '</form>';
    echo $OUTPUT->footer();
    die();
} else {
    // Provider not selected or verification failure
    $PAGE->set_context(context_system::instance());
    $PAGE->set_url(new moodle_url('/local/twofactorauth/selector.php'));
    $PAGE->set_title($SITE->fullname);
    $PAGE->set_heading(get_string('twofactorauthentication', 'local_twofactorauth'));
    echo $OUTPUT->header();
    echo '<h1>' . get_string('challengetwofactorauth', 'local_twofactorauth') . '</h1>';
    if ($Provider->transmit_code($USER->id)) {
        echo '<p>';
        echo get_string('inputotpcode', 'local_twofactorauth');
        echo '</p>';
        echo '<form method="POST" action="">';
        if ($transmit_message = $Provider->transmit_message($USER->id)) {
            echo '<div class="alert alert-info">' . $transmit_message . '</div>';
        }
        echo '<div class="form-group">';
        echo '<label for="input-otp-code">' . get_string('otpcode', 'local_twofactorauth') . '</label>';
        echo '<input type="text" class="form-control" id="input-otp-code" name="otpcode" placeholder="000000">';
        echo '</div>';
        echo '<button type="submit" class="btn btn-primary mb-2">' . get_string('submit') . '</button>';
        echo '<input type="hidden" name="provider" value="' . $selectedprovider. '" />';
        echo '<input type="hidden" name="sesskey" value="' . sesskey() . '" />';
        echo '</form>';
    } else {
        echo '<div class="alert alert-danger">';
        echo get_string('otpcodetransmiterror', 'local_twofactorauth');
        echo '</div>';
    }
    echo $OUTPUT->footer();
    die();
}

