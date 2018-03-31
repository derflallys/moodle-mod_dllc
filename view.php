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
 * Prints a particular instance of dllc
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_dllc
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace dllc with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $USER;
$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // ... dllc instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('dllc', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $dllc  = $DB->get_record('dllc', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($n) {
    $dllc  = $DB->get_record('dllc', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $dllc->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('dllc', $dllc->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

$event = \mod_dllc\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $dllc);
$event->trigger();

// Print the page header.

$PAGE->set_url('/mod/dllc/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($dllc->name));
$PAGE->set_heading(format_string($course->fullname));



/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('dllc-'.$somevar);
 */
// Output starts here.
$PAGE->set_periodic_refresh_delay(10);
echo $OUTPUT->header();
$context = context_module::instance($cm->id);



?>

    <div >
        <h1><?=$dllc->name?> du <?=userdate($dllc->dateheuredebut)?></h1>
        <?php


        // Conditions to show the intro can change to look for own settings or whatever.
        if ($dllc->intro) {
            echo $OUTPUT->box(format_module_intro('dllc', $dllc, $cm->id), 'generalbox mod_introbox', 'dllcintro');
        }
        ?>

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Date Debut</th>
                <th scope="col">Date Fin</th>
                <th scope="col">Type Ateliers</th>
                <th scope="col">Chargé D'atelier</th>
                <th scope="col">Salle</th>
                <th scope="col">Niveau</th>
                <th scope="col">Nombre de Place Disponible</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th scope="row"></th>
                <td><?=userdate($dllc->dateheuredebut)?></td>
                <td><?=userdate($dllc->dateheurefin)?></td>
                <td><?=get_string($dllc->ateliers,'dllc')?></td>
                <td><?=$dllc->c_atelier?></td>
                <td><?=$dllc->salle?></td>
                <td><?=get_string($dllc->niveau,'dllc')?></td>
                <td><?=$dllc->nbplacedispo?></td>
                <?php
try {
    $roles = get_user_roles($context, $USER->id);
    foreach ($roles as $role) {
        $rolestr[] = role_get_name($role, $context);
    }
    $rolestr = implode(', ', $rolestr);
    //echo "The users roles are {$rolestr} in course {$cm->id}";

    if (strcmp($rolestr,"Étudiant")!=0) {
        $params['idgroup'] = $dllc->idgroup;
        $params['cmid'] = $cm->instance;
        $link = new moodle_url('/mod/dllc/listparticipants.php',$params);
        $action =  new popup_action('click',$link,"Participants",array('height' => 800, 'width' => 900));
        ?>

         <td>
             <?php
             echo $OUTPUT->action_link($link, 'Voir la liste des participants', $action,array('title' => 'liste des participants '.userdate($dllc->dateheuredebut)));
             ?>
         </td>

        <?php
    }
    else
    {
        ?>
        <td>
            <?php
            $params['iduser'] = $USER->id;
            $params['idgroup'] = $dllc->idgroup;
            $params['nbparticipants'] = $dllc->nbplacedispo;
            $params['cmid'] = $cm->instance;

            if(!groups_is_member($dllc->idgroup,$USER->id))
            {
                $link = new moodle_url('/mod/dllc/inscription.php',$params);
                $action =  new popup_action('click',$link,"Inscription");
                echo $OUTPUT->action_link($link, 'M\'inscrire', $action,array('title' => 'Inscription Atelier du '.userdate($dllc->dateheuredebut)));

            }
            else
            {
                $link = new moodle_url('/mod/dllc/unregister.php',$params);
                $action =  new popup_action('click',$link,"Desinscrition");
                echo $OUTPUT->action_link($link, 'Se desinscrire', $action,array('title' => 'Desinscription Atelier du '.userdate($dllc->dateheuredebut)));


            }
        ?>
        </td>
                <?php
    }
    } catch (coding_exception $e) {
        echo $e;
    }
 ?>

            </tr>
            </tbody>
        </table>
    </div>

<?php

// Finish the page.
echo $OUTPUT->footer();
?>
