<?php
// Start a session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
 
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $host = "localhost";
    $dbname = "shp";
    $username_db = "root";
    $password_db = "";

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      
        // Corrected INSERT statement
        $stmt = $db->prepare("INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)");
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $hashed_password);
        $stmt->execute();

        // Redirect to the login page after a successful insertion
        // No HTML output before this header function
        header("refresh:3;url=login.html");
        
        // Output a success message after the header call
        echo "<h2>Registration Successful</h2>";
        echo "Thank you for registering, " . htmlspecialchars($name) . "!<br>";
        echo "You'll be redirected to the login page in 3 seconds";
    }
    catch(PDOException $e) {
        // Output the specific error message to help with debugging
        echo "Connection failed: " . $e->getMessage();
    }
}
?>