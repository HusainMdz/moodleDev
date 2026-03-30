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

use core\plugininfo\format;

require_once('../../config.php');
require_once($CFG->dirroot . '/local/greetings/lib.php');

defined('MOODLE_INTERNAL') || die();
require_login();

$url = new moodle_url('/local/greetings/index.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_greetings'));
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

$messageform = new \local_greetings\form\message_form();
$agefrom = new \local_greetings\form\age_msg();

// The start of body content.
echo $OUTPUT->header();


// Data output.
//  1. save the data in variable.
//  2. make mustache template, to display the data in a formatted way.
//  3. pass the data to template.
//  4. render the template (render_from_template).

// $usergreeting = 'Greetings, ' . fullname($USER);
// $usergreeting = get_string('greetingloggedinuser', 'local_greetings', fullname($USER)); // needs to do purge to lang cache, cuse the sting add to lang and lag in the cache dont change

$usergreeting = local_greetings_get_greeting_msg($USER);

$templatedata = ['usergreeting' => $usergreeting];
echo $OUTPUT->render_from_template('local_greetings/greeting_msg', $templatedata);


// display today's date
echo userdate(time(), get_string('strftimedate', 'core_langconfig')) . '<br>';

// display tomorrow's date
$date = new DateTime("tomorrow", core_date::get_server_timezone_object());
$date->setTime(0, 0, 0,);
echo userdate($date->getTimestamp(), get_string('strftimedatefullshort', 'core_langconfig'));

// display a formatted float number
echo '<br>' . format_float(20.00 / 3) . '';

$messageform->display();

if ($data = $messageform->get_data()) {
    $message = required_param('message', PARAM_TEXT);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();

        $DB->insert_record('local_greetings_messages', $record);
    }
}

$messages = $DB->get_records('local_greetings_messages');

$templatedata = ['messages' => array_values($messages)];
echo $OUTPUT->render_from_template('local_greetings/messages', $templatedata);


// $agefrom->display();  -- vlidation resions
if ($data = $agefrom->get_data()) {
    echo $OUTPUT->notification("Form submitted successfully! Age: " . $data->age, 'notifysuccess');
} else {
    $agefrom->display();
}



// End of body content.
echo $OUTPUT->footer();
