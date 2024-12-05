<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class CollectionReport extends Model {
    
    public function getAllReports() {
        return $this->db->table('collection_reports cr')
                       ->select('cr.*, c.tracking_number, c.user_id')
                       ->join('collections c', 'c.id = cr.collection_id')
                       ->order_by('cr.report_date', 'DESC')
                       ->get_all();
    }

    public function getReport($id) {
        return $this->db->table('collection_reports cr')
                       ->select('cr.*, c.tracking_number, c.user_id')
                       ->join('collections c', 'c.id = cr.collection_id')
                       ->where('cr.id', $id)
                       ->get();
    }

    public function getReportsByType($type, $start_date = null, $end_date = null) {
        $query = $this->db->table('collection_reports cr')
                         ->select('cr.*, c.tracking_number, c.user_id')
                         ->join('collections c', 'c.id = cr.collection_id')
                         ->where('cr.report_type', $type);

        if ($start_date) {
            $query->where('cr.report_date >=', $start_date);
        }
        if ($end_date) {
            $query->where('cr.report_date <=', $end_date);
        }

        return $query->order_by('cr.report_date', 'DESC')->get_all();
    }

    public function addReport($data) {
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        return $this->db->table('collection_reports')->insert($data);
    }

    public function updateReport($id, $data) {
        return $this->db->table('collection_reports')
                       ->where('id', $id)
                       ->update($data);
    }

    public function deleteReport($id) {
        return $this->db->table('collection_reports')
                       ->where('id', $id)
                       ->delete();
    }

    public function generateMonthlyReport($month, $year) {
        $start_date = "$year-$month-01";
        $end_date = date('Y-m-t', strtotime($start_date));

        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total_collections')
                          ->select('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as completed_collections')
                          ->where('created_at >=', $start_date)
                          ->where('created_at <=', $end_date)
                          ->get();

        if ($result) {
            $report_data = [
                'report_type' => 'monthly',
                'report_date' => $start_date,
                'total_items' => $result['total_collections'],
                'total_weight' => 0, // This would need to be calculated based on actual item weights
                'created_at' => date('Y-m-d H:i:s')
            ];
            return $this->addReport($report_data);
        }
        return false;
    }

    public function generateQuarterlyReport($quarter, $year) {
        $start_month = ($quarter - 1) * 3 + 1;
        $end_month = $start_month + 2;
        
        $start_date = "$year-" . str_pad($start_month, 2, '0', STR_PAD_LEFT) . "-01";
        $end_date = date('Y-m-t', strtotime("$year-" . str_pad($end_month, 2, '0', STR_PAD_LEFT) . "-01"));

        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total_collections')
                          ->select('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as completed_collections')
                          ->where('created_at >=', $start_date)
                          ->where('created_at <=', $end_date)
                          ->get();

        if ($result) {
            $report_data = [
                'report_type' => 'quarterly',
                'report_date' => $start_date,
                'total_items' => $result['total_collections'],
                'total_weight' => 0, // This would need to be calculated based on actual item weights
                'created_at' => date('Y-m-d H:i:s')
            ];
            return $this->addReport($report_data);
        }
        return false;
    }

    public function generateYearlyReport($year) {
        $start_date = "$year-01-01";
        $end_date = "$year-12-31";

        $result = $this->db->table('collections')
                          ->select('COUNT(*) as total_collections')
                          ->select('SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as completed_collections')
                          ->where('created_at >=', $start_date)
                          ->where('created_at <=', $end_date)
                          ->get();

        if ($result) {
            $report_data = [
                'report_type' => 'yearly',
                'report_date' => $start_date,
                'total_items' => $result['total_collections'],
                'total_weight' => 0, // This would need to be calculated based on actual item weights
                'created_at' => date('Y-m-d H:i:s')
            ];
            return $this->addReport($report_data);
        }
        return false;
    }

    public function getReportSummary($start_date = null, $end_date = null) {
        $query = $this->db->table('collection_reports')
                         ->select('SUM(total_items) as total_items')
                         ->select('SUM(total_weight) as total_weight')
                         ->select('COUNT(*) as total_reports');

        if ($start_date) {
            $query->where('report_date >=', $start_date);
        }
        if ($end_date) {
            $query->where('report_date <=', $end_date);
        }

        return $query->get();
    }
}
?>
