<?php
class PasswordGenerator {
    public static function generatePassword($length, $uppercase, $lowercase, $numbers, $special) {
        $chars = [];
        $password = '';

        // Uppercase letters (A-Z)
        for ($i = 0; $i < $uppercase; $i++) {
            $chars[] = chr(rand(65, 90));
        }

        // Lowercase letters (a-z)
        for ($i = 0; $i < $lowercase; $i++) {
            $chars[] = chr(rand(97, 122));
        }

        // Numbers (0-9)
        for ($i = 0; $i < $numbers; $i++) {
            $chars[] = chr(rand(48, 57));
        }

        // Special characters
        $specialChars = "!@#$%^&*()_+{}[];:'\"<>,.?/";
        if ($special > 0) {
            $chars[] = $specialChars[rand(0, strlen($specialChars) - 1)];
            $special--; // Reduce the count of special characters
        }

        // Fill remaining characters with random selections
        for ($i = 0; $i < $length - $uppercase - $lowercase - $numbers; $i++) {
            $chars[] = chr(rand(33, 126)); // ASCII range of printable characters
        }

        // Shuffle the characters
        shuffle($chars);

        // Generate the password
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, count($chars) - 1)];
        }

        return $password;
    }
}
?>
