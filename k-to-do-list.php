<?php
/*
Plugin Name: Kamil To Do List
Version: 1.0
Description: Plugin to do list
Author: Kamil
Author URI: https://github.com/fe-li/ptdlwp
*/

define( 'KTDL_PATH', plugin_dir_path( __FILE__ ) );
require KTDL_PATH.'model/model.php';

function ktdl_install() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $ktdl_tablename = $prefix . "k_to_do_list";
    $ktdl_db_version = "1.0";

    if($wpdb->get_var("SHOW TABLES LIKE '" . $ktdl_tablename . "'") != $ktdl_tablename) {
        $query = "CREATE TABLE " . $ktdl_tablename . "(
            id int(9) NOT NULL AUTO_INCREMENT,
            zadanie varchar(250) NOT NULL,
            PRIMARY KEY (id)
            )";
        $wpdb->query($query);
    }
}

register_activation_hook(__FILE__, 'ktdl_install');

function ktdl_unistall() {
    global $wpdb;
    $prefix = $wpdb->prefix;
    $ktdl_tablename = $prefix . "k_to_do_list";
    $query = 'DROP TABLE ' . $ktdl_tablename;
    $wpdb->query($query);
}

register_deactivation_hook(__FILE__, 'ktdl_unistall');

function ktdl_plugin_menu() {
    add_menu_page('Kamil To Do List', 'Kamil To Do List', 'administrator', 'ktdl_zad', 'ktdl_display_settings');
    add_submenu_page('ktdl_settings', __('Zadania'), __('Zadania'), 'edit_themes', 'ktdl_zad', 'ktdl_zad');
}
add_action('admin_menu', 'ktdl_plugin_menu');



function ktdl_zad(){
    $model = new ktdl();
     if(isset($_POST['ktdl_zad'])) {
         $model->deleteAll();
         foreach($_POST['ktdl_zad'] as $zad) {
             $model->add(array('zadanie' => $zad['zadanie']));
         }
     }
    $results = $model->getAll();
    echo '<h2>' . __('Zadania') . '</h2>';
    echo '<form action="?page=ktdl_zad" method="post">';
    echo '<table class="form-table" style="width:auto;" cellpadding="10">
        <thead>
        <tr>
        <td>' . __('Zadanie') . '</td><td>' . __('Delete') . '</td>
        </tr>
        </thead>
        <tbody class="items">';
    $i=0;
    foreach ($results as $row) {
        echo '<tr><td><input name="ktdl_zad['.$i.'][zadanie]" type="text" value="' . $row['zadanie'] . '"</td>';
        echo '<td><a class="delete" href="">' . __('Delete') . '</a></td></tr>';
        $i++;
    }
    echo '</tbody><tr><td colspan="2"><a class="add" href="">' . __('Add') . '</a></td></tr>';
    echo '<tr><td colspan="2"><input type="submit" value="' . __('Save') . '" /></td></tr>';
    echo '</table>';
    echo '</form>';

    echo '
        <script type="text/javascript">
        jQuery(document).ready(function($) {
        $("table .delete").click(function() {
        $(this).parent().parent().remove();
        return false;
        });
        $("table .add").click(function() {
        var count = $("tbody.items tr").length+1;
        var code=\'<tr><td><input type="text" name="ktdl_zad[\'+count+\'][zadanie]" /></td><td><a class="delete" href="">' . __('Delete') . '</a></td></tr>\';
        $("tbody.items").append(code);
        return false;
        });
        });
        </script>
        ';
}

function ktdl_display_settings() {
    echo '<p>Po kliknięciu usuń należy kliknać zapisz</p>';
    ktdl_zad();
}