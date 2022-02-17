<?php
/**
 * Model pluginu
 *
 * @author Kamil
 */

class ktdl {
    private $tableName;
    private $wpdb;

    public function __construct() {
       global $wpdb;
       $prefix = $wpdb->prefix;
       $this->tableName = $prefix . "k_to_do_list";
       $this->wpdb = $wpdb;

   }

   public function getAll() {
       $query = "SELECT * FROM " . $this->tableName;
       return $this->wpdb->get_results($query, ARRAY_A);
   }

   public function add($data) {
       $this->wpdb->insert($this->tableName, $data, array('%s'));
   }

//    public function delete($id) {
//        $sql = "DELETE FROM " .$this->tableName . "WHERE " . $id;
//        $this->wpdb->query($sql);
//    }

   public function deleteAll() {
    $sql = "TRUNCATE TABLE " . $this->tableName;
    $this->wpdb->query($sql);
}
}
?>