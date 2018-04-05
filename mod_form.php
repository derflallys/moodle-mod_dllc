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
 * The main dllc configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_dllc
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 *
 * @package    mod_dllc
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_dllc_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     * @throws HTML_QuickForm_Error
     */
    public function definition() {
        global $CFG;


        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('dllcname', 'dllc'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'dllcname', 'dllc');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Adding the rest of dllc settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.


        $mform->addElement('header', 'dllcfieldset', get_string('dllcfieldset', 'dllc'));

        $mform->addElement('text','salle',get_string('salle','dllc'));
        $mform->addRule('salle', null, 'required', null, 'client');
        $mform->setType('salle', PARAM_TEXT);

        $mform->addElement('text','c_atelier',get_string('c_atelier','dllc'));
        $mform->addRule('c_atelier', null, 'required', null, 'client');


        $mform->setType('c_atelier', PARAM_TEXT);
        $NIVEAU = array(
            'interm' => 'Intermediaire',
            'avance' => 'Avancés',
            'element' => 'Elementaire'
        );
        $mform->addElement('select', 'niveau', get_string('niveau', 'dllc'), $NIVEAU);
        $mform->addRule('niveau', null, 'required', null, 'client');

        $TYPE_ATELIER = array(
            'toeic' => 'Prepatation TOEICS',
            'atelierponc' => 'Ateliers Ponctuels',
            'groupediss' => 'Groupes de Discussions'
        );
        $mform->addElement('select', 'ateliers', get_string('ateliers', 'dllc'), $TYPE_ATELIER);
        $mform->addRule('ateliers', null, 'required', null, 'client');


        $mform->addElement('date_time_selector', 'dateheuredebut', get_string('dateheuredebut', 'dllc'));
        $mform->addRule('dateheuredebut', null, 'required', null, 'client');

        $mform->addElement('date_time_selector', 'dateheurefin', get_string('dateheurefin', 'dllc'));
        $mform->addRule('dateheurefin', null, 'required', null, 'client');

        $mform->addElement('text','nbplacedispo',get_string('nbplacedispo','dllc'));
        $mform->setType('nbplacedispo',PARAM_INT);
        $mform->addRule('nbplacedispo', null, 'required', null, 'client');

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }

    public function check_overlap_dllc($data)
    {
        global $DB,$COURSE;
        $courseid =  $COURSE->id;
        $listateliers = get_array_of_activities($courseid);

        $insert = true;
        foreach ($listateliers as $atelier) {

            if($atelier->mod=== 'dllc')
            {
                if($atelier->name != false)
                {

                    $cm = get_coursemodule_from_id('dllc', $atelier->cm, 0, false, MUST_EXIST);

                    $olddllc  = $DB->get_record('dllc', array('id' => $cm->instance), '*', MUST_EXIST);

                    if($olddllc->dateheuredebut<=$data['dateheuredebut'] && $olddllc->dateheurefin>=$data['dateheurefin'] && $olddllc->salle===$data['salle'])
                    {
                        $insert = false;
                        break;

                    }


                }
            }
        }

        return $insert;
    }

    public function validation($data, $files)
    {

        $errors = parent::validation($data, $files);
        if($data['dateheuredebut'] && $data['dateheurefin'])
        {
            if($data['dateheuredebut']<time())
            {
                $errors['dateheuredebut'] =get_string('timepast','dllc');

            }
            if($data['dateheuredebut']==$data['dateheurefin'])
            {
                $errors['dateheurefin'] =get_string('timeequals','dllc');
            }
            if( $data['dateheuredebut']>$data['dateheurefin'])
            {
                $errors['dateheurefin'] =get_string('timerule','dllc');
            }


        }

        if($data['dateheuredebut'] && $data['dateheurefin'])
        {
            $time =$data['dateheurefin']-$data['dateheuredebut'];
            $datediff = array(
                'year'        => $time / 31556926 % 12,
                'week'        =>$time/ 604800 % 52,
                'day'        => $time/ 86400 % 7,
                'hour'        => $time / 3600 % 24,
                'minute'    => $time/ 60 % 60,
                'second'    =>$time % 60
            );


            if($datediff['day']!=0)
            {
                $errors['dateheuredebut'] =get_string('datenotmatch','dllc');
            }


        }


        if(isset($data['nbplacedispo']))
        {
            if ($data['nbplacedispo']<=0) {
                $errors['nbplacedispo'] =get_string('err_numeric','dllc');

            }
        }

        if(isset($data['nbplacedispo']))
        {
            if ( strval($data['nbplacedispo']) != strval(intval($data['nbplacedispo'])) ) {
                $errors['nbplacedispo'] =get_string('err_numeric','dllc');

            }
        }


        if($data['salle'])
        {
            if (strval($data['salle']) != strval(intval($data['salle'])) && !preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $data['salle']) ) {
                $errors['salle'] =get_string('err_alphanumeric','dllc');

            }
        }

        global $DB,$COURSE;
        $courseid =  $COURSE->id;
        $listateliers = get_array_of_activities($courseid);

        $insert = true;

        foreach ($listateliers as $atelier) {

            if($atelier->mod=== 'dllc')
            {
                if($atelier->name != false)
                {

                    $cm = get_coursemodule_from_id('dllc', $atelier->cm, 0, false, MUST_EXIST);

                    $olddllc  = $DB->get_record('dllc', array('id' => $cm->instance), '*', MUST_EXIST);



                    if($olddllc->id != $data['instance']  && $olddllc->dateheuredebut<=$data['dateheuredebut'] && $olddllc->dateheurefin>=$data['dateheurefin'] && strcmp($olddllc->salle,$data['salle'])==0 )
                    {

                        $insert = false;
                        break;

                    }


                }
            }
        }
        if(!$insert)
        {
            $errors['salle'] =get_string('atelieroverlap','dllc');
        }



        return $errors;
    }


}
