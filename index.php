<style>
    h1{
        color: green;
        text-align: center; 
        font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
    }
</style>
<?php
// Database configuration
$servername = "sql12.freesqldatabase.com"; // Replace with your database server name
$username = "sql12712591"; // Replace with your database username
$password = "rHBApC4hWJ"; // Replace with your database password
$dbname = "sql12712591"; // Replace with your database name

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize input data
    function sanitize($data) {
        return htmlspecialchars(trim($data));
    }

    // Collect and sanitize input data
    $first_name = sanitize($_POST['first_name']);
    $last_name = sanitize($_POST['last_name']);
    $email = sanitize($_POST['email']);
    $website = sanitize($_POST['website']);
    $message = sanitize($_POST['message']);

    // Error messages
    $errors = [];

    // Validate fields
    if (empty($first_name)) {
        $errors[] = "First Name is required.";
    }
    if (empty($last_name)) {
        $errors[] = "Last Name is required.";
    }
    if (empty($email)) {
        $errors[] = "Email Address is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid Email format.";
    }
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $errors[] = "Invalid Website URL format.";
    }
    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    // Check if there are any errors
    if (count($errors) > 0) {
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
        exit;
    }

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contacts (first_name, last_name, email, website, message) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $first_name, $last_name, $email, $website, $message);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<h1>Message has sent successfully</h1>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
