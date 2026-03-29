<?php

namespace local_greetings\form;


defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');


class message_form extends \moodleform
{
    public function definition()
    {
        $mform = $this->_form;
        $mform->addElement('textarea', 'message', get_string('yourmessage', 'local_greetings'));
        $mform->setType('message', PARAM_TEXT);
        $submintlable = get_string('submit');
        $mform->addElement('submit', 'submitmessage', $submintlable);
    }
}
