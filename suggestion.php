<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


$db_host = "127.0.0.1:3307";
$db_user = "root";
$db_pass = "";
$db_name = "student_maintenance";


$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("<div style='color:red;padding:20px;font-family:Arial;'>
    <h2>Database Error</h2>
    <p>".$conn->connect_error."</p>
    <p>Check your XAMPP MySQL is running on port 3307</p>
    </div>");
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $regNo = $conn->real_escape_string($_POST['regNo']);
    $studentName = $conn->real_escape_string($_POST['studentName']);
    $block = $conn->real_escape_string($_POST['block']);
    $roomNo = $conn->real_escape_string($_POST['roomNo']);
    $improvementType = $conn->real_escape_string($_POST['improvementType']);
    $suggestion = $conn->real_escape_string($_POST['suggestion']);

   
    $sql = "INSERT INTO suggestions (regNo, studentName, block, roomNo, improvementType, suggestion)
            VALUES ('$regNo', '$studentName', '$block', '$roomNo', '$improvementType', '$suggestion')";

    if ($conn->query($sql) === TRUE) {
        // Complete success message
        echo '<!DOCTYPE html>
        <html>
        <head>
            <title>Success</title>
            <link rel="stylesheet" href="suggestion.css">
        </head>
        <body>
            <div class="form-container">
                <div class="success-message">
                    <h1>Suggestion Submitted Successfully!</h1>
                    <p>Thank you for your feedback.</p>
                    <a href="suggestion_form.html">Submit another suggestion</a>
                </div>
            </div>
        </body>
        </html>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
   
    $conn->close();
}
?>
