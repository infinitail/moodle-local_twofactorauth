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
 * User list for check 2FA status.
 *
 * @package     local_twofactorauth
 * @copyright   infinitail
 * @license     http://www.gnu.org/copyleft/gpl.html gnu gpl v3 or later
 */

/**
 * Currently, this is orphan
 * TODO: Add user administration page
 */

ini_set('display_errors', 1);
require_once __DIR__.'/../../config.php';
require_once __DIR__.'/locallib.php';

$providers = local\twofactorauth\locallib::get_providers();

if (!is_siteadmin($USER)) {
    print_error('nopermissions', 'error');
}

$users = $DB->get_records('user', []);

//var_dump($users);

$table = new html_table();
$table->attributes['class'] = 'admintable generaltable';
$table->head = array_merge(['username', 'user id'], $providers);
$table->colclasses[] = 'centeralign';
$table->id = "users";

foreach ($users as $user) {
    $row = [];

    //$row[] = $user->username;
    $row[] = fullname($user, true);
    $row[] = $user->id;
    //$row[] = html_writer::link('aaaaa', $OUTPUT->pix_icon('i/email', 'email'));
    foreach ($providers as $id=>$provider) {
        $cell  = $locallib->is_2fa_configured_user($user->id, $id);
        $cell .= html_writer::link('aaaaa', $OUTPUT->pix_icon('t/delete', 'delete'));

        $row[] = $cell;
    }

    $table->data[] = $row;
}

echo $OUTPUT->header();

echo html_writer::start_tag('div', array('class'=>'no-overflow'));
echo html_writer::table($table);
echo html_writer::end_tag('div');
echo $OUTPUT->paging_bar($usercount, $page, $perpage, $baseurl);

echo $OUTPUT->footer();