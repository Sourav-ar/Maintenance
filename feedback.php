<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
require_once __DIR__ . '/config.php';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("<div class='error-container'>
            <h2>Database Connection Failed</h2>
            <p>{$conn->connect_error}</p>
            <a href='feedback.html' class='btn btn-secondary'>Back to Form</a>
        </div>");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate rating (1-5)
    $rating = intval($_POST['rating']);
    if ($rating < 1 || $rating > 5) {
        die("<div class='error-container'>
                <h2>Invalid Rating</h2>
                <p>Please select a rating between 1 and 5 stars</p>
                <a href='feedback.html' class='btn btn-secondary'>Try Again</a>
            </div>");
}

    // Get and sanitize form data
    $regNo = $conn->real_escape_string($_POST['regNo']);
    $studentName = $conn->real_escape_string($_POST['studentName']);
    $block = $conn->real_escape_string($_POST['block']);
    $roomNo = $conn->real_escape_string($_POST['roomNo']);
    $feedback = $conn->real_escape_string($_POST['feedback']);

    // Insert into database using prepared statement
    $sql = "INSERT INTO feedback (regNo, studentName, block, roomNo, feedback, rating)
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $regNo, $studentName, $block, $roomNo, $feedback, $rating);
    
    if ($stmt->execute()) {
        // Success response
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Thank You</title>
            <link rel="stylesheet" href="feedback.css">
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        </head>
        <body>
            <div class="form-container">
                <div class="success-message">
                    <h1><i class="fas fa-check-circle"></i> Feedback Submitted</h1>
                    <div class="submission-details">
                        <p><strong>Thank you, '.htmlspecialchars($studentName).'!</strong></p>
                        <div class="rating-display">
                            Your rating: ';
        
        // Display stars based on rating
        for ($i = 1; $i <= 5; $i++) {
            echo $i <= $rating 
                ? '<i class="fas fa-star"></i>' 
                : '<i class="far fa-star"></i>';
        }
        
        echo '</div>
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
                <a href='feedback.html' class='btn btn-secondary'>Try Again</a>
              </div>";
    }
    
    $stmt->close();
} else {
    header("Location: feedback.html");
    exit();
}

$conn->close();
?>