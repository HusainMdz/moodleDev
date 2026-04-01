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
$context = \context_system::instance();

require_login();
// if (isguestuser()) {
//     throw new moodle_exception('noguest');
// }

$url = new moodle_url('/local/greetings/index.php', []);
$PAGE->set_url($url);
$PAGE->set_context($context);

$PAGE->set_pagelayout('standard');
$PAGE->set_title(get_string('pluginname', 'local_greetings'));
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));

$allowpost = has_capability('local/greetings:postmessages', $context);
$deleteanypost = has_capability('local/greetings:deleteanymessage', $context);

$messageform = new \local_greetings\form\message_form();
$agefrom = new \local_greetings\form\age_msg();


// Handle delete action.
$action = optional_param('action', '', PARAM_TEXT);
if ($action == 'del') {
    require_sesskey();
    require_capability('local/greetings:deleteanymessage', $context);
    $id = required_param('id', PARAM_INT);
    if ($deleteanypost) {
        $DB->delete_records('local_greetings_messages', ['id' => $id]);
    }
    redirect($PAGE->url, 'Greeting deleted successfully', 2);
}


// Handle form submission. with DB insert.
if ($data = $messageform->get_data()) {
    require_capability('local/greetings:postmessages', $context);
    $message = required_param('message', PARAM_RAW);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;

        $DB->insert_record('local_greetings_messages', $record);
    }
}




// Fetch messages with user information.
$userfields = \core_user\fields::for_name()->with_identity($context);
$userfieldssql = $userfields->get_sql('u');

$sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
          FROM {local_greetings_messages} m
     LEFT JOIN {user} u ON u.id = m.userid
      ORDER BY timecreated DESC";

$messages = $DB->get_records_sql($sql);





// The start of body content(only display in here)
echo $OUTPUT->header();


// Data output.
//  1. save the data in variable.
//  2. make mustache template, to display the data in a formatted way.
//  3. pass the data to template.
//  4. render the template (render_from_template).

// $usergreeting = 'Greetings, ' . fullname($USER);
// $usergreeting = get_string('greetingloggedinuser', 'local_greetings', fullname($USER)); // needs to do purge to lang cache, cuse the sting add to lang and lag in the cache dont change

$usergreeting = local_greetings_get_greeting_msg($USER);
// print_r($USER);
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

if ($allowpost) {
    $messageform->display();
}

// Display messages using template.
$templatedata = [
    'messages' => array_values($messages),
    'candeleteany' => $deleteanypost,
];
echo $OUTPUT->render_from_template('local_greetings/messages', $templatedata);

// Display age form and handle submission.
// $agefrom->display();  -- vlidation resions has been removed
if ($data = $agefrom->get_data()) {
    echo $OUTPUT->notification("Form submitted successfully! Age: " . $data->age, 'notifysuccess');
} else {
    $agefrom->display();
}


// End of body content.
echo $OUTPUT->footer();
