<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saved Passwords</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Saved Passwords</h2>
        <?php
session_start(); // Start the session

class PasswordManager {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Method to save the generated password to the database
    public function savePassword($aes_key, $generated_password, $platform) {
        $stmt = $this->conn->prepare("INSERT INTO generated (aes_key, generated_password, platform) VALUES (?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("sss", $aes_key, $generated_password, $platform);
            if ($stmt->execute()) {
                return true; // Password saved successfully
            } else {
                return "Error: " . $this->conn->error;
            }
            $stmt->close();
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    // Method to retrieve saved passwords for a specific aes_key
    public function getSavedPasswords($aes_key) {
        $passwords = [];
        $stmt = $this->conn->prepare("SELECT generated_password, platform, dateandtime FROM generated WHERE aes_key = ?");
        if ($stmt) {
            $stmt->bind_param("s", $aes_key);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $passwords[] = $row;
                    }
                }
                $stmt->close();
                return $passwords;
            } else {
                return "Error: " . $this->conn->error;
            }
        } else {
            return "Error: " . $this->conn->error;
        }
    }
}

// Include the database connection file
include('database.php');

// Instantiate the PasswordManager class with the database connection
$passwordManager = new PasswordManager($conn);

// Check if the form is submitted and the "Save" button is clicked
if (isset($_POST['save'])) {
    // Retrieve the generated password and platform from the session variables
    $password = $_SESSION["generated_password"];
    $platform = $_SESSION["platform"];
    $aes_key = $_SESSION['aes_key'];

    // Save the password to the database using the PasswordManager instance
    $saveResult = $passwordManager->savePassword($aes_key, $password, $platform);
    if ($saveResult === true) {
        echo "Password saved successfully!";
    } else {
        echo $saveResult; // Display error message
    }
}

// Retrieve the aes_key from the session
$aes_key = $_SESSION['aes_key'];

// Get saved passwords for the specific aes_key using the PasswordManager instance
$savedPasswords = $passwordManager->getSavedPasswords($aes_key);

// Display the saved passwords
if (!empty($savedPasswords)) {
    echo "<table>";
    echo "<tr><th>Password</th><th>Platform</th><th>Date/Time Saved</th></tr>";
    foreach ($savedPasswords as $row) {
        echo "<tr><td>".$row["generated_password"]."</td><td>".$row["platform"]."</td><td>".$row["dateandtime"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No results found.</p>";
}

// Close the database connection
$conn->close();
?>

    </div>
</body>
</html>
