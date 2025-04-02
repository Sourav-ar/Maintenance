<?php
require_once __DIR__ . '/config.php';
require 'vendor/autoload.php';

// Set headers for JSON response
header('Content-Type: application/json');

try {
    // Database connection
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Get JSON input
    $json = file_get_contents('php://input');
    if (empty($json)) {
        throw new Exception("No input data received");
    }

    $input = json_decode($json, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Invalid JSON data: " . json_last_error_msg());
    }

    // Validate required parameters
    if (!isset($input['format'])) {
        throw new Exception("Format parameter is required");
    }

    // Base query
    $sql = "SELECT * FROM maintenance_reports WHERE 1=1";
    $params = [];

    // Add filters
    if (!empty($input['time_period'])) {
        switch ($input['time_period']) {
            case 'today':
                $sql .= " AND DATE(submission_date) = CURDATE()";
                break;
            case 'week':
                $sql .= " AND submission_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                break;
            case 'month':
                $sql .= " AND submission_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
                break;
            case 'custom':
                if (!empty($input['start_date'])) {
                    $sql .= " AND submission_date >= ?";
                    $params[] = $input['start_date'] . ' 00:00:00';
                }
                if (!empty($input['end_date'])) {
                    $sql .= " AND submission_date <= ?";
                    $params[] = $input['end_date'] . ' 23:59:59';
                }
                break;
        }
    }

    if (!empty($input['status'])) {
        $sql .= " AND status = ?";
        $params[] = $input['status'];
    }

    // Prepare and execute query
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception("SQL preparation failed: " . $conn->error);
    }

    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        $stmt->bind_param($types, ...$params);
    }

    if (!$stmt->execute()) {
        throw new Exception("SQL execution failed: " . $stmt->error);
    }

    $result = $stmt->get_result();

    // Create reports directory if not exists
    if (!file_exists('reports')) {
        mkdir('reports', 0755, true);
    }

    // Generate Excel
    if ($input['format'] === 'excel') {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add headers
        $headers = [];
        foreach ($result->fetch_fields() as $field) {
            $headers[] = $field->name;
        }
        $sheet->fromArray([$headers], null, 'A1');
        
        // Add data
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = array_values($row);
        }
        $sheet->fromArray($data, null, 'A2');
        
        // Generate filename
        $filename = 'reports/report_' . date('Ymd_His') . '.xlsx';
        
        // Save file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filename);
        
        echo json_encode([
            'success' => true,
            'file' => $filename,
            'message' => 'Excel report generated successfully'
        ]);

    // Generate PDF
    } else {
        $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator('Maintenance Portal');
        $pdf->SetTitle('Maintenance Report');
        $pdf->AddPage();

        // Create HTML content
        $html = '<h1>Maintenance Report</h1>';
        $html .= '<table border="1" cellpadding="5">';
        
        // Headers
        $html .= '<tr style="background-color:#f2f2f2;">';
        foreach ($result->fetch_fields() as $field) {
            $html .= '<th>' . htmlspecialchars($field->name) . '</th>';
        }
        $html .= '</tr>';
        
        // Data rows
        while ($row = $result->fetch_assoc()) {
            $html .= '<tr>';
            foreach ($row as $value) {
                $html .= '<td>' . htmlspecialchars($value) . '</td>';
            }
            $html .= '</tr>';
        }
        
        $html .= '</table>';

        $pdf->writeHTML($html, true, false, true, false, '');
        
        $filename = 'reports/report_' . date('Ymd_His') . '.pdf';
        $pdf->Output($filename, 'F');
        
        echo json_encode([
            'success' => true,
            'file' => $filename,
            'message' => 'PDF report generated successfully'
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}