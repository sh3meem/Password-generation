<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* background-color: #0d1117; Dark background color */
            color: #c9d1d9; /* Text color */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            width: 320px;
            background-color: #161b22; /* Darker background color */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 40px;
            box-sizing: border-box;
        }

        h2 {
            text-align: center;
            color: #c9d1d9; /* Text color */
            margin-bottom: 30px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 20px;
        }

        label {
            font-size: 14px;
            color: #c9d1d9; /* Text color */
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #30363d; /* Border color */
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
            background-color: #0d1117; /* Dark background color */
            color: #c9d1d9; /* Text color */
        }

        button {
            padding: 12px;
            border: none;
            border-radius: 4px;
            background-color: #238636; /* Button color */
            color: #ffffff; /* Text color */
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2ea44f; /* Hover button color */
        }

        button:focus {
            outline: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class= "input-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" name="login">Login</button>
        </form>
    </div>
</body>
</html>



<?php
// Include the database connection file
require_once('database.php');

// Define the UserAuthentication class
class UserAuthentication {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Method to authenticate user login
    public function login($username, $password) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            // Verify the password
            if(password_verify($password, $hashed_password)) {
                // Login successful, store user ID and username in session variables
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];

                header('Location: page.php');
                exit;
            } else {
                return "Invalid username or password";
            }
        } else {
            return "Invalid username or password";
        }
    }
}

// Create an instance of UserAuthentication and pass the database connection
$userAuth = new UserAuthentication($conn);

// Check if login form is submitted
if(isset($_POST["login"])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the login method
    $loginResult = $userAuth->login($username, $password);
    if($loginResult !== true) {
        echo $loginResult;
    }
}
?>

