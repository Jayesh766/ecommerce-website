<?php
// Start the session at the very beginning of the script
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Connect to the database
    $host = "localhost";
    $dbname = "shp";
    $username_db = "root";
    $password_db = "";

    try {
        $db = new PDO(
            "mysql:host=$host;dbname=$dbname",
            $username_db,
            $password_db
        );
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Check if the user exists in the database
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user["password"])) {
                // If login is successful, store user data in a session
                $_SESSION["user_id"] = $user['id']; // It's better to store a user ID
                $_SESSION["username"] = $user['username'];
                
                // Redirect to the shop page immediately with a header
                // Note: The alert will not work with a header redirect
                header("Location: shop.php");
                exit(); // Always call exit() after a header redirect
            } else {
                // Invalid password
                $_SESSION["error"] = "Invalid username or password.";
                header("Location: login.html");
                exit();
            }
        } else {
            // User doesn't exist
            $_SESSION["error"] = "User does not exist.";
            header("Location: login.html");
            exit();
        }
    } catch (PDOException $e) {
        // Output the specific error message for database connection failure
        echo "Connection failed: " . $e->getMessage();
    }
}
?>