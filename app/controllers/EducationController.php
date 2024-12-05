<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * EducationController Class
 * Handles education-related operations such as fetching educational materials.
 */
class EducationController extends Controller {
    /**
     * Constructor
     * Initializes the controller.
     */
    public function __construct() {
        parent::__construct();
        $this->call->model('Education');
    }

    /**
     * Get Educational Materials
     * Retrieves all educational materials from the database and displays them.
     */
    public function getMaterials() {
        // Fetch educational materials from the database
        $materials = $this->db->table('education_materials')->get_all();
        
        // Extract unique categories from materials
        $categories = [];
        if ($materials) {
            foreach ($materials as $material) {
                if (!empty($material['category']) && !in_array($material['category'], $categories)) {
                    $categories[] = $material['category'];
                }
            }
        }

        // Prepare data for view
        $data = [
            'materials' => $materials,
            'categories' => $categories,
            'total_materials' => count($materials),
            'total_categories' => count($categories)
        ];

        // Load the education view with data
        $this->call->view('education', $data);
    }
}
?>
