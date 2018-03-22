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
        global $CFG,$DB;


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
        $mform->setType('salle', PARAM_TEXT);

        $mform->addElement('text','c_atelier',get_string('c_atelier','dllc'));
        $mform->setType('c_atelier', PARAM_TEXT);
        $NIVEAU = array(
            'interm' => 'Intermediaire',
            'avance' => 'Avancés',
            'element' => 'Elementaire'
        );
        $mform->addElement('select', 'niveau', get_string('niveau', 'dllc'), $NIVEAU);

        $TYPE_ATELIER = array(
            'toeic' => 'Prepatation TOEICS',
            'atelierponc' => 'Ateliers Ponctuels',
            'groupediss' => 'Groupes de Discussions'
        );
        $mform->addElement('select', 'ateliers', get_string('ateliers', 'dllc'), $TYPE_ATELIER);


        $mform->addElement('date_time_selector', 'dateheuredebut', get_string('dateheuredebut', 'dllc'));

        $mform->addElement('date_time_selector', 'dateheurefin', get_string('dateheurefin', 'dllc'));

        $mform->addElement('text','nbplacedispo',get_string('nbplacedispo','dllc'));
        $mform->setType('nbplacedispo',PARAM_INT);

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }
}
