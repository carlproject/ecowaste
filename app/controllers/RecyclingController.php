<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * RecyclingController Class
 * Manages recycling-related operations such as handling the recycling process and ensuring compliance.
 */
class RecyclingController extends Controller {
    /**
     * Constructor
     * Loads the Recycling model for use in the controller methods.
     */
    public function __construct() {
        parent::__construct();
        $this->call->model('Recycling');
    }

    /**
     * Manage Recycling
     * Handles the recycling process using data submitted via POST request.
     */
    public function manageRecycling() {
        $data = $this->input->post(); // Retrieve POST data
        $this->Recycling->manageRecycling($data); // Process recycling data
        redirect('dashboard'); // Redirect to the dashboard after processing
    }

    /**
     * Compliance Check
     * Ensures that recycling operations comply with regulations and displays compliance information.
     */
    public function complianceCheck() {
        $data['compliance'] = $this->Recycling->checkCompliance(); // Fetch compliance status
        $this->call->view('compliance_check', $data); // Load the compliance check view with data
    }
}
?>
