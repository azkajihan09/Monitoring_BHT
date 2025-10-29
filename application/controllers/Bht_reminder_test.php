<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller Sederhana untuk Testing BHT Reminder
 * 
 * PEMBELAJARAN DEBUGGING:
 * Jika sistem kompleks error, buat versi sederhana dulu untuk isolasi masalah
 */
class Bht_reminder_test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Coba load satu-satu untuk debug
        echo "Loading model...<br>";
        $this->load->model('M_bht_reminder');
        echo "Model loaded!<br>";

        echo "Loading helpers...<br>";
        $this->load->helper(['url', 'date']);
        echo "Helpers loaded!<br>";

        echo "Loading library session...<br>";
        $this->load->library('session');
        echo "Session loaded!<br>";
    }

    /**
     * Test sederhana tanpa template system
     */
    public function index()
    {
        echo "<h1>Test BHT Reminder System</h1>";

        try {
            echo "<h2>Testing Model Methods:</h2>";

            // Test method get_automatic_reminders
            echo "<h3>1. Testing get_automatic_reminders()</h3>";
            $reminders = $this->M_bht_reminder->get_automatic_reminders(3);
            echo "Result: " . count($reminders) . " reminders found<br>";

            if (!empty($reminders)) {
                echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
                echo "<tr><th>Nomor Perkara</th><th>Status</th><th>Sisa Hari</th></tr>";
                foreach ($reminders as $reminder) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($reminder->nomor_perkara) . "</td>";
                    echo "<td>" . htmlspecialchars($reminder->status_prioritas) . "</td>";
                    echo "<td>" . $reminder->sisa_hari . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }

            // Test method get_bht_status_report
            echo "<h3>2. Testing get_bht_status_report()</h3>";
            $report = $this->M_bht_reminder->get_bht_status_report();
            echo "Result: <pre>" . print_r($report, true) . "</pre>";

            // Test method get_monthly_chart_data
            echo "<h3>3. Testing get_monthly_chart_data()</h3>";
            $chart_data = $this->M_bht_reminder->get_monthly_chart_data();
            echo "Chart Data: <pre>" . print_r($chart_data, true) . "</pre>";

            // Test method get_case_type_distribution
            echo "<h3>4. Testing get_case_type_distribution()</h3>";
            $case_dist = $this->M_bht_reminder->get_case_type_distribution();
            echo "Case Distribution: <pre>" . print_r($case_dist, true) . "</pre>";

            echo "<h2 style='color: green;'>✅ All Tests Passed!</h2>";

            echo "<hr>";
            echo "<h2>Next Steps:</h2>";
            echo "<ol>";
            echo "<li><a href='" . base_url('bht_reminder_test/test_with_template') . "'>Test dengan Template System</a></li>";
            echo "<li><a href='" . base_url('bht_reminder') . "'>Test Full Dashboard</a></li>";
            echo "</ol>";
        } catch (Exception $e) {
            echo "<h2 style='color: red;'>❌ Error Occurred:</h2>";
            echo "<p>Error: " . $e->getMessage() . "</p>";
            echo "<p>File: " . $e->getFile() . "</p>";
            echo "<p>Line: " . $e->getLine() . "</p>";
        }
    }

    /**
     * Test dengan template system
     */
    public function test_with_template()
    {
        try {
            // Data minimal untuk testing
            $data = [
                'page_title' => 'Test Template System',
                'reminders' => $this->M_bht_reminder->get_automatic_reminders(3),
                'monthly_report' => $this->M_bht_reminder->get_bht_status_report(),
                'chart_data' => $this->M_bht_reminder->get_monthly_chart_data(),
                'case_distribution' => $this->M_bht_reminder->get_case_type_distribution(),
                'reminder_count' => 0,
                'current_month_name' => 'Oktober',
                'current_year' => 2025
            ];

            $data['reminder_count'] = count($data['reminders']);

            echo "Loading template system...<br>";

            // Load template dan view
            $this->load->view('template/new_header', $data);
            $this->load->view('template/new_sidebar');

            // Load view sederhana untuk testing
            echo '<div class="content-wrapper">';
            echo '<section class="content"><div class="container-fluid">';
            echo '<div class="row"><div class="col-12">';
            echo '<div class="card"><div class="card-body">';
            echo '<h1>Template System Test Berhasil!</h1>';
            echo '<p>Jumlah pengingat: ' . $data['reminder_count'] . '</p>';
            echo '<p>Total perkara putus: ' . $data['monthly_report']->total_perkara_putus . '</p>';
            echo '<a href="' . base_url('bht_reminder') . '" class="btn btn-primary">Test Full Dashboard</a>';
            echo '</div></div>';
            echo '</div></div>';
            echo '</div></section>';
            echo '</div>';

            $this->load->view('template/new_footer');
        } catch (Exception $e) {
            echo "<h2 style='color: red;'>❌ Template Error:</h2>";
            echo "<p>Error: " . $e->getMessage() . "</p>";
        }
    }
}
