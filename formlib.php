<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/03/18
 * Time: 15:42
 */

class formeditatelier extends moodleform {

    /**
     * Form definition. Abstract method - always override!
     */
    protected function definition()
    {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('text', 'salle', get_string('salle')); // Add elements to your form
        $mform->addElement('text','c_atelier',get_string('c_atelier'));
        $mform->addElement('number','c_atelier',get_string('c_atelier'));
        $mform->addElement('date_time_selector', 'timeopen', get_string('quizopen', 'quiz'),
            self::$datefieldoptions);
    }
}