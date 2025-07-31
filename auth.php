<?php
// Start session if needed
session_start();

// Replace with your database credentials
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "stackwood_db"; // CHANGE THIS

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch form data
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

if (isset($_POST['login'])) {
    // LOGIN process
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Set session variables
        $_SESSION['email'] = $email;
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        // Show popup and redirect using JavaScript
        echo "<script>alert('Login successful!'); window.location.href='index.html';</script>";
    } else {
        echo "Invalid credentials.";
    }

} elseif (isset($_POST['signup'])) {
    // SIGNUP process
    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    } else {
        // Password validation rules
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must include at least one uppercase letter.";
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must include at least one lowercase letter.";
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = "Password must include at least one number.";
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = "Password must include at least one symbol.";
        }
        
        if (!empty($errors)) {
            echo "<script>alert('Password requirements not met:\\n" . implode("\\n", $errors) . "');</script>";
        } else {
            $check = "SELECT * FROM users WHERE email='$email'";
            $result = $conn->query($check);
            if ($result->num_rows > 0) {
                echo "Email already exists.";
            } else {
                $sql = "INSERT INTO users (first_name, last_name, email, password) 
                        VALUES ('$firstName', '$lastName', '$email', '$password')";
                if ($conn->query($sql) === TRUE) {
                    // Set session variables
                    $_SESSION['email'] = $email;
                    $_SESSION['firstName'] = $firstName;
                    $_SESSION['lastName'] = $lastName;
                    // Show popup and redirect using JavaScript
                    echo "<script>alert('Account created successfully!'); window.location.href='index.html';</script>";
                } else {
                    echo "Error: " . $conn->error;
                }
            }
        }
    }
}

$conn->close();
?>
