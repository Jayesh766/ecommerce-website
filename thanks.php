<?php
// Start the session at the very beginning of the script
session_start();

// Redirect to login if a user is not logged in to prevent direct access
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }
        
        h1 {
            color: #008000;
            font-size: 2.5em;
            text-align: center;
            margin-top: 50px;
        }
        
        p {
            color: #333;
            font-size: 1.2em;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php
    // Retrieve the customer name from the session variable
    $customerName = isset($_SESSION['username']) ? $_SESSION['username'] : "Valued Customer";

    // Display the thank you message
    echo "<h1>Thank You, " . htmlspecialchars($customerName) . "!</h1>";
    echo "<p>Your order has been received and will be delivered soon.</p>";
    ?>
</body>

</html>