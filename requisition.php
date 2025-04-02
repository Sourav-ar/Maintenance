<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration (use same as suggestion.php)
require_once __DIR__ . '/config.php';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("<div class='error-container'>
            <h2>Database Connection Failed</h2>
            <p>{$conn->connect_error}</p>
            <a href='requisition.html' class='btn btn-secondary'>Back to Form</a>
        </div>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process file upload
    $proof_filename = '';
    if (!empty($_FILES['proof']['name'])) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $proof_filename = basename($_FILES['proof']['name']);
        $target_file = $upload_dir . $proof_filename;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Validate file
        $allowed_types = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];
        if (!in_array($file_type, $allowed_types)) {
            die("<div class='error-container'>
                    <h2>Invalid File Type</h2>
                    <p>Only JPG, PNG, PDF, DOC files are allowed</p>
                    <a href='requisition.html' class='btn btn-secondary'>Try Again</a>
                </div>");
        }
        
        if ($_FILES['proof']['size'] > 5000000) { // 5MB max
            die("<div class='error-container'>
                    <h2>File Too Large</h2>
                    <p>Maximum file size is 5MB</p>
                    <a href='requisition.html' class='btn btn-secondary'>Try Again</a>
                </div>");
        }
        
        if (!move_uploaded_file($_FILES['proof']['tmp_name'], $target_file)) {
            die("<div class='error-container'>
                    <h2>Upload Failed</h2>
                    <p>Could not save your file</p>
                    <a href='requisition.html' class='btn btn-secondary'>Try Again</a>
                </div>");
        }
    }

    // Get and sanitize form data
    $regNo = $conn->real_escape_string($_POST['regNo']);
    $studentName = $conn->real_escape_string($_POST['studentName']);
    $block = $conn->real_escape_string($_POST['block']);
    $roomNo = $conn->real_escape_string($_POST['roomNo']);
    $workType = $conn->real_escape_string($_POST['workType']);
    $comments = $conn->real_escape_string($_POST['comments'] ?? '');

    // Insert into database
    $sql = "INSERT INTO requisitions (regNo, studentName, block, roomNo, workType, comments, proof_filename)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $regNo, $studentName, $block, $roomNo, $workType, $comments, $proof_filename);
    
    if ($stmt->execute()) {
        // Success response
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Request Submitted</title>
            <link rel="stylesheet" href="form.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        </head>
        <body>
            <div class="form-container">
                <div class="success-message">
                    <h1><i class="fas fa-check-circle"></i> Maintenance Request Submitted</h1>
                    <div class="submission-details">
                        <p><strong>Request ID:</strong> '.$stmt->insert_id.'</p>
                        <p><strong>Work Type:</strong> '.htmlspecialchars($workType).'</p>
                        '.($proof_filename ? '<p><strong>Attachment:</strong> '.htmlspecialchars($proof_filename).'</p>' : '').'
                    </div>
                    <a href="index.html" class="btn btn-card">
                        <i class="fas fa-home"></i> Return to Home
                    </a>
                </div>
            </div>
        </body>
        </html>';
    } else {
        echo "<div class='error-container'>
                <h2>Submission Error</h2>
                <p>{$stmt->error}</p>
                <a href='requisition.html' class='btn btn-secondary'>Try Again</a>
              </div>";
    }
    
    $stmt->close();
} else {
    header("Location: requisition.html");
    exit();
}

$conn->close();
?>