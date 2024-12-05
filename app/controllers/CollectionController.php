<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CollectionController extends Controller {
    public function __construct() {
        parent::__construct();
        check_auth(); // Require authentication for all methods
        $this->call->model('Collection');
        $this->call->model('Users_model');
        $this->call->model('Analytics');
        $this->call->model('Rewards');
    }

    public function schedulePickup() {
        // Get user's pickup history first
        $data['pickups'] = $this->Collection->getUserPickups($_SESSION['user_id']);

        if ($this->form_validation->submitted()) {
            $this->form_validation
                ->name('pickup_date')->required('Pickup date is required!')
                ->name('pickup_time')->required('Pickup time is required!')
                ->name('address')->required('Address is required!')
                ->name('items')->required('Items description is required!');

            if ($this->form_validation->run()) {
                $pickup_data = array(
                    'user_id' => $_SESSION['user_id'],
                    'pickup_date' => $this->io->post('pickup_date'),
                    'pickup_time' => $this->io->post('pickup_time'),
                    'address' => $this->io->post('address'),
                    'items' => $this->io->post('items'),
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s')
                );

                if ($this->Collection->schedulePickup($pickup_data)) {
                    set_flash_alert('success', 'Pickup scheduled successfully! Your request has been submitted.');
                    redirect('track-pickup');
                } else {
                    set_flash_alert('danger', 'Failed to schedule pickup. Please try again.');
                    $this->call->view('schedule', $data);
                }
            } else {
                set_flash_alert('danger', $this->form_validation->errors());
                $this->call->view('schedule', $data);
            }
        } else {
            // Load the view with pickup history
            $this->call->view('schedule', $data);
        }
    }

    public function trackPickup() {
        if ($this->form_validation->submitted()) {
            $this->form_validation
                ->name('tracking_number')->required('Tracking number is required!');

            if ($this->form_validation->run()) {
                $tracking_number = $this->io->post('tracking_number');
                $pickup = $this->Collection->getPickupByTracking($tracking_number);

                if ($pickup) {
                    // Only show if it belongs to the current user
                    if ($pickup['user_id'] == $_SESSION['user_id']) {
                        $data['pickup'] = $pickup;
                        $this->call->view('track_pickup', $data);
                        return;
                    }
                }
                set_flash_alert('danger', 'Invalid tracking number or pickup not found.');
                redirect('track-pickup');
            }
        }

        // Get recent pickups for the search page
        $data['recent_pickups'] = $this->Collection->get_user_recent_pickups($_SESSION['user_id'], 5);
        $this->call->view('track_pickup_search', $data);
    }

    public function generateReport() {
        $data['pickups'] = $this->Collection->getUserPickups($_SESSION['user_id']);
        $data['stats'] = $this->Collection->get_user_collection_stats($_SESSION['user_id']);
        $this->call->view('collection_report', $data);
    }

    public function cancelPickup($tracking_number) {
        $pickup = $this->Collection->getPickupByTracking($tracking_number);
        
        if ($pickup && $pickup['user_id'] == $_SESSION['user_id'] && $pickup['status'] == 'pending') {
            if ($this->Collection->update_collection($pickup['id'], ['status' => 'cancelled'])) {
                set_flash_alert('success', 'Pickup request cancelled successfully.');
            } else {
                set_flash_alert('danger', 'Failed to cancel pickup request.');
            }
        } else {
            set_flash_alert('danger', 'Invalid pickup request or cannot be cancelled.');
        }
        redirect('schedule');
    }

    public function cancel_status() {
        if ($this->form_validation->submitted()) {
            $tracking_number = $this->io->post('tracking_number');
            $status = $this->io->post('status');
            
            $collection = $this->Collection->cancel_get_one($tracking_number);
            if (!$collection) {
                set_flash_alert('danger', 'Collection not found.');
                redirect('schedule');
                return;
            }

            if ($this->Collection->cancel_collection($tracking_number, ['status' => $status])) {
                set_flash_alert('success', 'Collection status updated successfully.');
            } else {
                set_flash_alert('danger', 'Failed to update collection status.');
            }
        }
        redirect('schedule');
    }

    public function getPickupHistory() {
        // API endpoint for getting pickup history via AJAX
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $pickups = $this->Collection->getUserPickups($_SESSION['user_id']);
        echo json_encode(['pickups' => $pickups]);
    }
}
?>
