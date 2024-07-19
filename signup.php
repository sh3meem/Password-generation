<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign Up</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: white;
    }

    .signup-container {
        max-width: 400px;
        margin: 100px auto;
        background-color: #161b22; /* Darker background color */
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
    }

    h2 {
        text-align: center;
        color: #c9d1d9; /* Text color */
    }

    form {
        margin-top: 20px;
    }

    .input-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        color: #c9d1d9; /* Text color */
    }

    input[type="text"],
    input[type="password"] {
        width: calc(100% - 20px);
        padding: 10px;
        border: 1px solid #30363d; /* Border color */
        border-radius: 3px;
        font-size: 16px;
        background-color: #0d1117; /* Dark background color */
        color: #c9d1d9; /* Text color */
    }

    button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 3px;
        background-color: #238636; /* Button color */
        color: #ffffff; /* Text color */
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #2ea44f; /* Hover button color */
    }
</style>

</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form action="signup.php" method="post">
            <div class="input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="signup" value="signup">Sign Up</button>
        </form>
    </div>
</body>
</html>

<?php
// Include the database connection file
require_once('database.php');

// Define the UserSignup class
class UserSignup {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Method to handle user signup
    public function signup($username, $password) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Generate encoded key using user's plain password
        $aes_key = base64_encode($password); // You can use AES encryption here

        // Insert user data into the database
        $sql = "INSERT INTO users (username, password, aes_key) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $aes_key);

        try {
            if($stmt->execute()) {
                // Redirect to login page
                header('Location: login.php');
                exit; // Stop further execution
            }
        } catch (mysqli_sql_exception $e) {
            // Check if the error is due to duplicate username
            if($e->getCode() == 1062) {
                return "Error: Username already exists. Please choose a different username.";
            } else {
                return "Error: " . $e->getMessage();
            }
        }
    }
}

// Create an instance of UserSignup and pass the database connection
$userSignup = new UserSignup($conn);

// Check if the signup form is submitted
if(isset($_POST["signup"])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the signup method
    $signupResult = $userSignup->signup($username, $password);
    if($signupResult !== true) {
        echo $signupResult;
    }
}
?>

