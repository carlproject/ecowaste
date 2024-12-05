<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AnalyticsController extends Controller {
    
    public function __construct() {
        parent::__construct();
        check_auth();
        $this->call->model('Analytics');
        $this->call->model('Collection');
    }

    public function index() {
        // Get total collections
        $total_collections = $this->Collection->count_all();

        // Get category trends
        $category_trends = $this->Analytics->getCategoryTrends();
        
        // Get monthly comparison
        $monthly_comparison = $this->Analytics->getMonthlyComparison();

        // Get completion rate
        $completion_rate = $this->Analytics->getCompletionRate();

        // Get user participation
        $user_participation = $this->Analytics->getUserParticipation();

        $data = array(
            'total_collections' => $total_collections,
            'category_trends' => $category_trends,
            'monthly_comparison' => $monthly_comparison,
            'completion_rate' => $completion_rate,
            'user_participation' => $user_participation
        );

        $this->call->view('admin/analytics', $data);
    }

    public function getTrends() {
        // Get collection statistics
        $collection_stats = $this->Collection->get_monthly_stats();
        
        // Get category trends
        $category_trends = $this->Analytics->getCategoryTrends();
        
        // Get most common items
        $common_items = $this->Analytics->getMostCommonItems();

        // Format data for view
        $data = array(
            'collection_stats' => [
                'total' => $collection_stats['total'] ?? 0,
                'confirmed' => $collection_stats['confirmed'] ?? 0,
                'pending' => $collection_stats['pending'] ?? 0
            ],
            'category_trends' => is_array($category_trends) ? array_values($category_trends) : [],
            'common_items' => is_array($common_items) ? array_values($common_items) : []
        );

        // Add monthly comparison
        $monthly_comparison = $this->Analytics->getMonthlyComparison();
        if ($monthly_comparison) {
            $data['monthly_comparison'] = [
                'current' => $monthly_comparison['current'] ?? 0,
                'previous' => $monthly_comparison['previous'] ?? 0,
                'growth' => $monthly_comparison['growth'] ?? 0
            ];
        }

        // Add completion rate
        $completion_rate = $this->Analytics->getCompletionRate();
        if ($completion_rate) {
            $data['completion_rate'] = [
                'total' => $completion_rate['total'] ?? 0,
                'confirmed' => $completion_rate['confirmed'] ?? 0,
                'rate' => $completion_rate['rate'] ?? 0
            ];
        }

        // Get user participation
        $user_participation = $this->Analytics->getUserParticipation();
        if (is_array($user_participation)) {
            $data['user_participation'] = array_map(function($user) {
                return [
                    'username' => $user['username'] ?? 'Unknown',
                    'collections' => $user['collections'] ?? 0,
                    'confirmed' => $user['confirmed'] ?? 0
                ];
            }, $user_participation);
        } else {
            $data['user_participation'] = [];
        }

        $this->call->view('trends', $data);
    }

    public function generatePerformanceReport() {
        $data = array(
            'collection_stats' => $this->Collection->get_monthly_stats(),
            'category_trends' => $this->Analytics->getCategoryTrends(),
            'completion_rate' => $this->Analytics->getCompletionRate(),
            'monthly_comparison' => $this->Analytics->getMonthlyComparison()
        );

        // Ensure all data arrays are properly formatted
        foreach ($data as $key => &$value) {
            if (!is_array($value)) {
                $value = [];
            }
        }

        $this->call->view('performance_report', $data);
    }

    private function formatNumber($number) {
        return number_format($number ?? 0);
    }

    private function calculatePercentage($value, $total) {
        if (!$total) return 0;
        return round(($value / $total) * 100, 1);
    }
}
?>
