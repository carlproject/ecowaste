<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
class Partnership extends Model {
public function getPartners() {
// Retrieve partners from the database
return $this->db->table('partners')->get_all();
}
public function addPartner($data) {
// Insert new partner into the database
$this->db->table('partners')->insert($data);
}
}
?>