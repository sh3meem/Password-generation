<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f5f5;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 600px; /* Adjust width for better readability */
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        #options-container {
            width: 45%;
            padding-right: 20px;
            border-right: 1px solid #ccc;
        }

        #output-container {
            width: 45%;
            padding-left: 20px;
            display: flex;
            flex-direction: column;
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="number"] {
            width: 70px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 5px;
            text-align: center;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
            margin: 4px;
        }

        button:hover {
            background-color: #0056b3;
        }

        #password {
            width: 100%;
            margin-top: 20px;
            font-size: 18px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="options-container">
            <h2>Password Options</h2>
            <form id="passwordForm" action="" method="post">
                <div class="form-group">
                    <label for="length">Password Length:</label>
                    <input type="number" id="length" name="length" min="4" max="50" value="" required>
                </div>
                <div class="form-group">
                    <label for="uppercase">Uppercase Letters:</label>
                    <input type="number" id="uppercase" name="uppercase" min="0" value="">
                </div>
                <div class="form-group">
                    <label for="lowercase">Lowercase Letters:</label>
                    <input type="number" id="lowercase" name="lowercase" min="0" value="">
                </div>
                <div class="form-group">
                    <label for="numbers">Numbers:</label>
                    <input type="number" id="numbers" name="numbers" min="0" value="">
                </div>
                <div class="form-group">
                    <label for="special">Special Characters:</label>
                    <input type="number" id="special" name="special" min="0" value="">
                </div>
                <div class="form-group">
                    <label for="platform">Platform:</label>
                    <select id="platform" name="platform">
                        <option value="Instagram">Instagram</option>
                        <option value="Facebook">Facebook</option>
                        <option value="Gmail">Gmail</option>
                        <!-- Add more options as needed -->
                    </select>
                </div>
                <button type="submit">Generate Password</button>
            </form>
        </div>
        <div id="output-container">
            <h1>Password Generator</h1>
            <input type="text" id="password" name="password" readonly>
            <div class="button-container">
                <button id="copyButton">Copy</button>
                <form method="post" action="save.php"><button id="saveButton" name="save" type="submit">Save</button></form>
            </div>
        </div>
    </div>

    <script>
    // JavaScript to copy password to clipboard
    document.getElementById("copyButton").addEventListener("click", function() {
        var passwordField = document.getElementById("password");
        passwordField.select();
        document.execCommand("copy");
        alert("Password copied to clipboard!");
    });
    </script>


    <?php


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        require_once 'passGen.php';
        $length = $_POST["length"];
        $uppercase = $_POST["uppercase"];
        $lowercase = $_POST["lowercase"];
        $numbers = $_POST["numbers"];
        $special = $_POST["special"];

        $password = PasswordGenerator::generatePassword($length, $uppercase, $lowercase, $numbers, $special);
        echo "<script>document.getElementById('password').value = '$password';</script>";

        $_SESSION['generated_password'] = $password;
        $_SESSION['platform'] = $_POST["platform"];

        if(isset($_POST['save'])){
            echo "saved succesfully";
        }

    }
    ?>


</body>
</html>