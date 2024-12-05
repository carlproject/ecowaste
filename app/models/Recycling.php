<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
class Recycling extends Model {
public function manageRecycling($data) {
// Insert recycling data into the database
$this->db->table('recycling')->insert($data);
}
public function checkCompliance() {
// Check compliance with regulations
return $this->db->table('recycling')->get_all();
}
}
?>