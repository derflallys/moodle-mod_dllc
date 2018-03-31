<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 31/03/18
 * Time: 19:38
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $DB;


$idgroup = required_param('idgroup', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
if($cmid)
{
    $dllc  = $DB->get_record('dllc', array('id' => $cmid), '*', MUST_EXIST);
}
?>
<html>
<head>
    <title>Liste des participants</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h4>Liste des participants de l'atelier <?=userdate($dllc->dateheuredebut)?> </h4>
    <?php
      $listparticipants =  groups_get_members($idgroup, $fields='u.*', $sort='lastname ASC');
        if(count($listparticipants)==0)
        {
            echo '<div class="alert alert-info" role="alert">
                  Il n\'y a pas de participant pour cet atelier .
                </div>';
        }
        else
    {
    ?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nom Participant</th>
            <th scope="col">Prenom Participant</th>
            <th scope="col">Email Participant</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($listparticipants as $participant) {

        ?>
            <tr>
                <th scope="row"></th>
                <td><?= $participant->lastname ?></td>
                <td><?= $participant->firstname ?></td>
                <td><?= $participant->email ?></td>
            </tr>
            <?php
            }
    }
    ?>
            </tbody>
        </table>

</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
