<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Education extends Model {
    
    public function getAllMaterials() {
        return $this->db->table('education_materials')
                       ->order_by('created_at', 'DESC')
                       ->get_all();
    }

    public function getMaterialsByCategory($category) {
        return $this->db->table('education_materials')
                       ->where('category', $category)
                       ->order_by('created_at', 'DESC')
                       ->get_all();
    }

    public function getMaterial($id) {
        return $this->db->table('education_materials')
                       ->where('id', $id)
                       ->get();
    }

    public function addMaterial($data) {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        return $this->db->table('education_materials')->insert($data);
    }

    public function updateMaterial($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->table('education_materials')
                       ->where('id', $id)
                       ->update($data);
    }

    public function deleteMaterial($id) {
        return $this->db->table('education_materials')
                       ->where('id', $id)
                       ->delete();
    }

    public function getCategories() {
        return $this->db->table('education_materials')
                       ->select('DISTINCT category')
                       ->order_by('category', 'ASC')
                       ->get_all();
    }
}
?>
