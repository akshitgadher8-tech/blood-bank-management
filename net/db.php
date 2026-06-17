<?php
$servername = "localhost";
$username = "root"; // default WAMP user
$password = ""; // default WAMP password is empty
$dbname = "netdb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>