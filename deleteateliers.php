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
    $return = course_get_url($course, $cm->sectionnum, array('sr' => $sectionreturn));

}
?>
<html>
<head>
    <title>Liste des participants</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

    <div class="container">

        <?php


        try {
            course_delete_module($cm->id);
        } catch (moodle_exception $e) {
            echo $e;
        }
        dllc_delete_instance($dllc->id);


        ?>

        <h4>L'atelier du  <?=userdate($dllc->dateheuredebut)?>  a ete bien supprimé et les etudiants ont ete notifé par email </h4>





    </div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>