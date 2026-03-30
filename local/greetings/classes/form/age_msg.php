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

namespace local_greetings\form;

/**
 * Class age_msg
 *
 * @package    local_greetings
 * @copyright  2026 YOUR NAME <your@email.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class age_msg extends \moodleform
{

    public function definition()
    {
        $msg = $this->_form;
        $msg->addElement('text', 'age', get_string('yourage', 'local_greetings'));
        $msg->setType('age', PARAM_INT);
        //rulse for the vlidations
        $msg->addRule('age', 'You must enter a number', 'required', null, 'client'); // add this filed is required
        $msg->addRule('age', 'Must be a number', 'numeric', null, 'client'); // this will add tiny filed in this filed that will only allow the numbers to be submitted
        $msg->addRule('age', 'Age cannot be negative', 'nonzero', null, 'client'); // this make it positive numbers
        $msg->addElement('submit', 'submitMessage', get_string('submit'));
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        if ($data['age'] < 0) {
            $errors['age'] = "age is not correct";
        }

        return $errors;
    }
}
