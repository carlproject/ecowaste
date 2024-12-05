<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Rewards extends Model {
    
    public function getAllRewards() {
        return $this->db->table('rewards')
                       ->order_by('points_required', 'ASC')
                       ->get_all();
    }

    public function getActiveRewards() {
        return $this->db->table('rewards')
                       ->where('status', 'active')
                       ->order_by('points_required', 'ASC')
                       ->get_all();
    }

    public function get_reward_details($id) {
        return $this->db->table('rewards')
                       ->where('id', $id)
                       ->get();
    }

    public function add_reward($data) {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }
        return $this->db->table('rewards')->insert($data);
    }

    public function update_reward($id, $data) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->table('rewards')
                       ->where('id', $id)
                       ->update($data);
    }

    public function delete_reward($id) {
        // First check if reward has been claimed
        try {
            $claimed = $this->db->table('claimed_rewards')
                               ->where('reward_id', $id)
                               ->get();
            
            if ($claimed) {
                // If claimed, just deactivate the reward
                return $this->update_reward($id, ['status' => 'inactive']);
            }
        } catch (Exception $e) {
            // If table doesn't exist, proceed with deletion
        }
        
        // If not claimed or table doesn't exist, can safely delete
        return $this->db->table('rewards')
                       ->where('id', $id)
                       ->delete();
    }

    public function getAllClaimedRewards() {
        try {
            return $this->db->table('claimed_rewards cr')
                           ->select('cr.*, r.name, r.points_required, u.username')
                           ->join('rewards r', 'r.id = cr.reward_id')
                           ->join('users u', 'u.id = cr.user_id')
                           ->order_by('cr.claimed_at', 'DESC')
                           ->get_all();
        } catch (Exception $e) {
            return [];
        }
    }

    public function getUserClaimedRewards($user_id) {
        try {
            return $this->db->table('claimed_rewards cr')
                           ->select('cr.*, r.name, r.points_required')
                           ->join('rewards r', 'r.id = cr.reward_id')
                           ->where('cr.user_id', $user_id)
                           ->order_by('cr.claimed_at', 'DESC')
                           ->get_all();
        } catch (Exception $e) {
            return [];
        }
    }

    public function claimReward($user_id, $reward_id) {
        // Get reward details
        $reward = $this->get_reward_details($reward_id);
        if (!$reward || $reward['status'] !== 'active') {
            return false;
        }

        // Get user points
        $user = $this->db->table('users')
                        ->select('points')
                        ->where('id', $user_id)
                        ->get();

        if (!$user || $user['points'] < $reward['points_required']) {
            return false;
        }

        try {
            // Start transaction
            $this->db->trans_start();

            // Deduct points from user
            $this->db->table('users')
                     ->where('id', $user_id)
                     ->update(['points' => $user['points'] - $reward['points_required']]);

            // Record claim
            $claim_data = [
                'user_id' => $user_id,
                'reward_id' => $reward_id,
                'points_used' => $reward['points_required'],
                'claimed_at' => date('Y-m-d H:i:s'),
                'status' => 'pending'
            ];
            
            try {
                $this->db->table('claimed_rewards')->insert($claim_data);
            } catch (Exception $e) {
                // If insert fails, rollback transaction
                $this->db->trans_rollback();
                return false;
            }

            // Complete transaction
            $this->db->trans_complete();

            return $this->db->trans_status();
        } catch (Exception $e) {
            return false;
        }
    }

public function updateClaimStatus($claim_id, $status) {
        try {
            return $this->db->table('claimed_rewards')
                           ->where('id', $claim_id)
                           ->update([
                               'status' => $status,
                               'updated_at' => date('Y-m-d H:i:s')
                           ]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function addPoints($user_id, $points) {
        // Get current points
        $user = $this->db->table('users')
                        ->select('points')
                        ->where('id', $user_id)
                        ->get();

        if (!$user) {
            return false;
        }

        // Add points
        return $this->db->table('users')
                       ->where('id', $user_id)
                       ->update(['points' => $user['points'] + $points]);
    }

    public function getUserPoints($user_id) {
        $user = $this->db->table('users')
                        ->select('points')
                        ->where('id', $user_id)
                        ->get();
        
        return $user ? $user['points'] : 0;
    }
}
?>
