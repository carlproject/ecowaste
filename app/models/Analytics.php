<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Analytics extends Model {
    
    public function getMonthlyTrends($limit = 6) {
        return $this->db->table('collections')
                       ->select('DATE_FORMAT(created_at, "%Y-%m") as month')
                       ->select('COUNT(*) as total')
                       ->select('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed')
                       ->select('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
                       ->group_by('DATE_FORMAT(created_at, "%Y-%m")')
                       ->order_by('month', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }

    public function getCategoryTrends() {
        return $this->db->table('collections')
                       ->select('COALESCE(items, "Uncategorized") as category')
                       ->select('COUNT(*) as total')
                       ->where('status', 'confirmed')
                       ->group_by('items')
                       ->order_by('total', 'DESC')
                       ->get_all();
    }

    public function getYearlyTotal() {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->where('YEAR(created_at)', date('Y'))
                          ->get();
        return $result['total'] ?? 0;
    }

    public function getCollectionStats() {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->select('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending')
                          ->select('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed')
                          ->get();
        return $result ?? ['total' => 0, 'pending' => 0, 'confirmed' => 0];
    }

    public function getMostCommonItems($limit = 5) {
        return $this->db->table('collections')
                       ->select('items')
                       ->select('COUNT(*) as count')
                       ->where('status', 'confirmed')
                       ->where('items IS NOT NULL')
                       ->group_by('items')
                       ->order_by('count', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }

    public function getUserParticipation($limit = 10) {
        return $this->db->table('users')
                       ->select('users.username')
                       ->select('COUNT(collections.id) as total_collections')
                       ->select('SUM(CASE WHEN collections.status = "confirmed" THEN 1 ELSE 0 END) as confirmed')
                       ->left_join('collections', 'users.id = collections.user_id')
                       ->group_by('users.id')
                       ->order_by('collections.id', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }

    public function getMonthlyComparison() {
        $current_month_start = date('Y-m-01');
        $current_month_end = date('Y-m-t');
        $last_month_start = date('Y-m-01', strtotime('-1 month'));
        $last_month_end = date('Y-m-t', strtotime('-1 month'));

        // Current month query
        $current = $this->db->table('collections')
                           ->select('COUNT(*) as total')
                           ->where('created_at BETWEEN "' . $current_month_start . '" AND "' . $current_month_end . '"')
                           ->get();

        // Previous month query
        $previous = $this->db->table('collections')
                            ->select('COUNT(*) as total')
                            ->where('created_at BETWEEN "' . $last_month_start . '" AND "' . $last_month_end . '"')
                            ->get();

        $current_count = $current['total'] ?? 0;
        $previous_count = $previous['total'] ?? 0;
        $growth = $previous_count > 0 ? (($current_count - $previous_count) / $previous_count * 100) : 0;

        return [
            'current' => $current_count,
            'previous' => $previous_count,
            'growth' => $growth
        ];
    }

    public function getCompletionRate() {
        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total')
                          ->select('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed')
                          ->get();

        $total = $result['total'] ?? 0;
        $confirmed = $result['confirmed'] ?? 0;
        $rate = $total > 0 ? ($confirmed / $total * 100) : 0;

        return [
            'total' => $total,
            'confirmed' => $confirmed,
            'rate' => $rate
        ];
    }

    public function getDailyCollections($days = 7) {
        $start_date = date('Y-m-d', strtotime("-$days days"));
        return $this->db->table('collections')
                       ->select('DATE(created_at) as date')
                       ->select('COUNT(*) as total')
                       ->where('created_at >= "' . $start_date . '"')
                       ->group_by('DATE(created_at)')
                       ->order_by('date', 'DESC')
                       ->get_all();
    }

    public function getUserStats() {
        $result = $this->db->table('users')
                          ->select('COUNT(*) as total_users')
                          ->select('SUM(CASE WHEN role = "admin" THEN 1 ELSE 0 END) as admin_count')
                          ->select('AVG(points) as avg_points')
                          ->get();
        return $result ?? ['total_users' => 0, 'admin_count' => 0, 'avg_points' => 0];
    }

    public function getTopPerformers($limit = 5) {
        return $this->db->table('users')
                       ->select('users.username')
                       ->select('COUNT(collections.id) as total_collections')
                       ->select('SUM(CASE WHEN collections.status = "confirmed" THEN 1 ELSE 0 END) as completed_collections')
                       ->select('users.points')
                       ->left_join('collections', 'users.id = collections.user_id')
                       ->group_by('users.id')
                       ->order_by('COUNT(collections.id)', 'DESC')
                       ->limit($limit)
                       ->get_all();
    }
}
?>
