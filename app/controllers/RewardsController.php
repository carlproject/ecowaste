<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class RewardsController extends Controller {
    
    public function __construct() {
        parent::__construct();
        check_auth();
        $this->call->model('Rewards');
    }

    public function index() {
        $user_id = $_SESSION['user_id'];
        
        $data = array(
            'rewards' => $this->Rewards->getAllRewards(),
            'user_points' => $this->Rewards->getUserPoints($user_id),
            'claimed_rewards' => $this->Rewards->getUserClaimedRewards($user_id)
        );

        $this->call->view('rewards', $data);
    }

    public function admin() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            redirect('dashboard');
        }

        $data = array(
            'rewards' => $this->Rewards->getAllRewards(),
            'claimed_rewards' => $this->Rewards->getAllClaimedRewards()
        );

        $this->call->view('admin/rewards', $data);
    }

    public function add() {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array(
                'name' => $this->io->post('name'),
                'description' => $this->io->post('description'),
                'points_required' => $this->io->post('points_required'),
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            );

            if ($this->Rewards->add_reward($data)) {
                set_flash_alert('success', 'Reward added successfully!');
            } else {
                set_flash_alert('danger', 'Failed to add reward.');
            }
            redirect('admin/rewards');
        }
    }

    public function edit($id) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            redirect('dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = array(
                'name' => $this->io->post('name'),
                'description' => $this->io->post('description'),
                'points_required' => $this->io->post('points_required'),
                'status' => $this->io->post('status'),
                'updated_at' => date('Y-m-d H:i:s')
            );

            if ($this->Rewards->update_reward($id, $data)) {
                set_flash_alert('success', 'Reward updated successfully!');
            } else {
                set_flash_alert('danger', 'Failed to update reward.');
            }
            redirect('admin/rewards');
        }

        $data = array(
            'reward' => $this->Rewards->get_reward_details($id)
        );

        $this->call->view('admin/edit_reward', $data);
    }

    public function delete($id) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            redirect('dashboard');
        }

        if ($this->Rewards->delete_reward($id)) {
            set_flash_alert('success', 'Reward deleted successfully!');
        } else {
            set_flash_alert('danger', 'Failed to delete reward.');
        }
        redirect('admin/rewards');
    }

    public function claimReward($reward_id) {
        $user_id = $_SESSION['user_id'];
        
        if ($this->Rewards->claimReward($user_id, $reward_id)) {
            set_flash_alert('success', 'Reward claimed successfully!');
        } else {
            set_flash_alert('danger', 'Failed to claim reward. Please check if you have enough points.');
        }
        
        redirect('rewards');
    }

    public function getRewards() {
        $user_id = $_SESSION['user_id'];
        
        $data = array(
            'rewards' => $this->Rewards->getAllRewards(),
            'user_points' => $this->Rewards->getUserPoints($user_id),
            'claimed_rewards' => $this->Rewards->getUserClaimedRewards($user_id)
        );

        $this->call->view('rewards', $data);
    }

    public function updateClaimStatus($claim_id) {
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            redirect('dashboard');
        }

        $status = $this->io->post('status');
        if ($this->Rewards->updateClaimStatus($claim_id, $status)) {
            set_flash_alert('success', 'Claim status updated successfully!');
        } else {
            set_flash_alert('danger', 'Failed to update claim status.');
        }
        redirect('admin/rewards');
    }

    // This method is called when a collection is completed to add points
    public function addPoints($user_id, $points) {
        if ($this->Rewards->addPoints($user_id, $points)) {
            return true;
        }
        return false;
    }
}
?>
