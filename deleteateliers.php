<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 01/04/18
 * Time: 16:45
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once('../../course/lib.php');
global $DB;


$idgroup = required_param('idgroup', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
$id = required_param('id', PARAM_INT);

if($cmid)
{
    $dllc  = $DB->get_record('dllc', array('id' => $cmid), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_id('dllc', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $return = course_get_url($course);

}

            $params['id'] = $id;
            $link = new moodle_url('/mod/dllc/deleteateliers.php',$params);

            $PAGE->set_url($link);
            course_delete_module($cm->id);

            dllc_delete_instance($dllc->id);
            redirect($return);


        ?>




