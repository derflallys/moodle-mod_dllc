<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26/03/18
 * Time: 00:26
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
global $DB;

$id_user = required_param('iduser', PARAM_INT);
$idgroup = required_param('idgroup', PARAM_INT);
$nbparticipants = required_param('nbparticipants', PARAM_INT);
$cmid = required_param('cmid', PARAM_INT);
if($cmid)
{
    $dllc  = $DB->get_record('dllc', array('id' => $cmid), '*', MUST_EXIST);
}
?>
<html>
<head>
    <title>Se desinscrire</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <h1>Desinscription de l'atelier <?=userdate($dllc->dateheuredebut)?> </h1>
    <?php
    try {

            if(!groups_is_member($idgroup,$id_user))
            {
                echo '<div class="alert alert-info" role="alert">
                      Vous n\'etes pas inscrit ! 
                    </div>';
            }
            else
            {

                groups_remove_member($idgroup, $id_user);
                dllc_update_nbparticipants($dllc,$nbparticipants+1);
                echo '<div class="alert alert-success" role="alert">
                    Vous n\'etes plus inscrit à l\'atelier ! 
                </div>' ;

            }


    } catch (coding_exception $e) {
        echo '<div class="alert alert-danger" role="alert">
              Erreur lors de votre desinscription , veillez ressayer plutard
            </div>';
    } catch (dml_exception $e) {
        echo '<div class="alert alert-danger" role="alert">
              Erreur lors de votre desinscription , veillez ressayer plutard update
            </div>';
    }
    ?>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
