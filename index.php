<?php
// Start the session
session_start();

// Define variables and initialize with empty values
$service_type = "";
$message = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate service type
    if (isset($_POST["service_type"])) {
        $service_type = trim($_POST["service_type"]);
        
        // Store service type in session
        $_SESSION['service_type'] = $service_type;
        
        // Redirect based on service type
        switch ($service_type) {
            case 'maintenance':
                header("Location: requisition.php");
                exit();
            case 'suggestion':
                header("Location: suggestion.php");
                exit();
            case 'feedback':
                header("Location: feedback.php");
                exit();
            default:
                $message = "Invalid service type selected.";
                break;
        }
    } else {
        $message = "Please select a service type.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Request</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <i class="fas fa-tools"></i>
            <span>Student Maintenance Portal</span>
        </div>
    </header>

    <main class="main-content">
        <div class="processing-container">
            <?php if (!empty($message)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($message); ?>
                </div>
                <a href="index.html" class="btn btn-primary">Go Back</a>
            <?php else: ?>
                <div class="processing-animation">
                    <i class="fas fa-cog fa-spin"></i>
                    <p>Processing your request...</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>© <?php echo date("Y"); ?> University Maintenance System</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Contact Support</a>
            </div>
        </div>
    </footer>
</body>
</html>