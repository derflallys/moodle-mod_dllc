<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13/03/18
 * Time: 21:17
 */

defined('MOODLE_INTERNAL') || die();


class mod_dllc_renderer extends plugin_renderer_base {

    public function admin_accueil() {
        $str = '<div class="container"><button type="button" class="btn btn-primary">Primary</button></div>';
        return $str;
    }
}