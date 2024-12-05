<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Collection extends Model {
    
    public function schedulePickup($data) {
        $data['tracking_number'] = $this->generateTrackingNumber();
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['status'] = 'pending';
        return $this->db->table('collections')->insert($data);
    }

    public function getPickupByTracking($tracking_number) {
        return $this->db->table('collections')
                       ->where('tracking_number', $tracking_number)
                       ->get();
    }

    public function getUserPickups($user_id) {
        return $this->db->table('collections')
                       ->where('user_id', $user_id)
                       ->order_by('created_at', 'DESC')
                       ->get_all();
    }

    public function updateStatus($tracking_number, $status) {
        return $this->db->table('collections')
                       ->where('tracking_number', $tracking_number)
                       ->update(['status' => $status]);
    }

    private function generateTrackingNumber() {
        $prefix = 'EB-';
        $date = date('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return $prefix . $date . '-' . $random;
    }

    public function count_all() {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->get();
        return $result['total'] ?? 0;
    }

    public function getUserCollectionsCount($user_id) {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->where('user_id', $user_id)
                          ->get();
        return $result['total'] ?? 0;
    }

    public function getUserCollectionsByStatus($user_id, $status) {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->where('user_id', $user_id)
                          ->where('status', $status)
                          ->get();
        return $result['total'] ?? 0;
    }

    public function count_by_status($status) {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->where('status', $status)
                          ->get();
        return $result['total'] ?? 0;
    }

    public function get_recent_collections($limit = 5) {
        return $this->db->table('collections')
                       ->order_by('created_at', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }

    public function get_all_with_users() {
        return $this->db->table('collections')
                       ->order_by('created_at', 'DESC')
                       ->get_all();
    }

    public function get_one($id) {
        return $this->db->table('collections')
                       ->where('id', $id)
                       ->get();
    }

    public function cancel_get_one($trackingNumber) {
        return $this->db->table('collections')
                       ->where('tracking_number', $trackingNumber)
                       ->get();
    }

    public function update_collection($id, $data) {
        if (isset($data['status']) && $data['status'] === 'confirmed') {
            $data['completed_at'] = date('Y-m-d H:i:s');
        }
        return $this->db->table('collections')
                       ->where('id', $id)
                       ->update($data);
    }
    public function cancel_collection($trackingNumber, $data) {
        return $this->db->table('collections')
                       ->where('tracking_number', $trackingNumber)
                       ->update($data);
    }

    public function get_monthly_stats() {
        $current_month = date('Y-m');
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->select("COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed")
                          ->select("COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending")
                          ->where('DATE_FORMAT(created_at, "%Y-%m")', $current_month)
                          ->get();
        return [
            'total' => $result['total'] ?? 0,
            'confirmed' => $result['confirmed'] ?? 0,
            'pending' => $result['pending'] ?? 0
        ];
    }

    public function get_user_collection_stats($user_id) {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->select("COUNT(CASE WHEN status = 'confirmed' THEN 1 END) as confirmed")
                          ->select("COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending")
                          ->where('user_id', $user_id)
                          ->get();
        return [
            'total' => $result['total'] ?? 0,
            'confirmed' => $result['confirmed'] ?? 0,
            'pending' => $result['pending'] ?? 0
        ];
    }

    public function get_user_recent_collections($user_id, $limit = 5) {
        return $this->db->table('collections')
                       ->where('user_id', $user_id)
                       ->order_by('created_at', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }

    public function get_status_counts() {
        $statuses = ['pending', 'confirmed', 'cancelled'];
        $counts = [];
        
        foreach ($statuses as $status) {
            $result = $this->db->table('collections')
                              ->select('COUNT(*) as count')
                              ->where('status', $status)
                              ->get();
            $counts[$status] = $result['count'] ?? 0;
        }
        
        return $counts;
    }

    public function delete_collection($id) {
        return $this->db->table('collections')
                       ->where('id', $id)
                       ->delete();
    }

    public function get_collections_by_date_range($start_date, $end_date) {
        return $this->db->table('collections')
                       ->where('created_at >=', $start_date)
                       ->where('created_at <=', $end_date)
                       ->order_by('created_at', 'DESC')
                       ->get_all();
    }

    public function get_user_recent_pickups($user_id, $limit = 5) {
        return $this->db->table('collections')
                       ->where('user_id', $user_id)
                       ->order_by('created_at', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }
}
?>
