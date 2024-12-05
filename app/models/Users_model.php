<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Users_model extends Model {
    
    public function read() {
        return $this->db->table('users')->get_all();
    }

    public function create($username, $email, $password, $address) {
        // Check if username or email already exists
        $existing_user = $this->db->table('users')
                                ->where('username', $username)
                                ->or_where('email', $email)
                                ->get();
        
        if ($existing_user) {
            return false;
        }

        $userdata = array(
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'address' => $address,
            'role' => 'user',
            'points' => 0
        );
        
        return $this->db->table('users')->insert($userdata);
    }

    public function verify_login($username, $password) {
        $user = $this->db->table('users')
                        ->where('username', $username)
                        ->get();
                        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function get_one($id) {
        return $this->db->table('users')
                       ->where('id', $id)
                       ->get();
    }

    // Admin-specific methods
    public function get_all_users() {
        return $this->db->table('users')
                       ->order_by('id', 'DESC')
                       ->get_all();
    }

    public function count_users() {
        $result = $this->db->table('users')
                          ->select('COUNT(*) as total')
                          ->get();
        return $result['total'] ?? 0;
    }

    public function count_admins() {
        $result = $this->db->table('users')
                          ->where('role', 'admin')
                          ->select('COUNT(*) as total')
                          ->get();
        return $result['total'] ?? 0;
    }

    public function get_recent_users($limit = 5) {
        return $this->db->table('users')
                       ->order_by('id', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }

    public function update_user($id, $data) {
        // Check if updating role and it's an admin
        if (isset($data['role']) && $data['role'] !== 'admin') {
            $user = $this->get_one($id);
            if ($user && $user['role'] === 'admin' && $this->count_admins() <= 1) {
                return false; // Prevent changing role of last admin
            }
        }

        // Check if username or email already exists (except for current user)
        if (isset($data['username']) || isset($data['email'])) {
            $query = $this->db->table('users')->where('id !=', $id);
            
            if (isset($data['username'])) {
                $query->where('username', $data['username']);
            }
            if (isset($data['email'])) {
                $query->or_where('email', $data['email']);
            }
            
            $existing_user = $query->get();
            if ($existing_user) {
                return false;
            }
        }

        return $this->db->table('users')
                       ->where('id', $id)
                       ->update($data);
    }

    public function delete_user($id) {
        $user = $this->get_one($id);
        if (!$user) {
            return false;
        }

        // Check if trying to delete an admin
        if ($user['role'] === 'admin' && $this->count_admins() <= 1) {
            return false; // Cannot delete the last admin
        }

        // Delete associated collections first
        $this->db->table('collections')
                 ->where('user_id', $id)
                 ->delete();

        // Delete the user
        return $this->db->table('users')
                       ->where('id', $id)
                       ->delete();
    }

    public function update_role($id, $role) {
        // Prevent changing the last admin's role
        if ($role !== 'admin') {
            $admin_count = $this->count_admins();
            $user = $this->get_one($id);
            if ($admin_count <= 1 && $user['role'] === 'admin') {
                return false;
            }
        }

        return $this->update_user($id, ['role' => $role]);
    }

    public function add_points($user_id, $points) {
        $user = $this->get_one($user_id);
        if ($user) {
            $new_points = $user['points'] + $points;
            return $this->update_user($user_id, ['points' => $new_points]);
        }
        return false;
    }

    public function get_user_stats() {
        $total = $this->count_users();
        $admin_count = $this->count_admins();

        return [
            'total_users' => $total,
            'admin_count' => $admin_count
        ];
    }

    public function search_users($search_term) {
        return $this->db->table('users')
                       ->like('username', $search_term)
                       ->or_like('email', $search_term)
                       ->get_all();
    }

    public function get_top_contributors($limit = 10) {
        return $this->db->table('users')
                       ->order_by('points', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }

    public function get_user_collections_count($user_id) {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->where('user_id', $user_id)
                          ->get();
        return $result['total'] ?? 0;
    }
}
?>
