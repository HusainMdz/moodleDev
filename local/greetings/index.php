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
 * @package    local_greetings
 * @copyright  2026 Husain husain@test.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');

require_login();

$url = new moodle_url('/local/greetings/index.php', []);

// Url setup.
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

// Layout management.
$PAGE->set_pagelayout('standard');

// Tab(browser tab) title.
$PAGE->set_title(get_string('pluginname', 'local_greetings'));


// Title on the page.
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

// The start of body content.
echo $OUTPUT->header();


// Data output.
//  1. save the data in variable.
//  2. make mustache template, to display the data in a formatted way.
//  3. pass the data to template.
//  4. render the template (render_from_template).
$usergreeting = 'Greetings, ' . fullname($USER);
$templatedata = ['usergreeting' => $usergreeting];
echo $OUTPUT->render_from_template('local_greetings/greeting_msg', $templatedata);

// End of body content.
echo $OUTPUT->footer();
