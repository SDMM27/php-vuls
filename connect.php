<?php
$servername = "localhost";
$username = "root";
$password = "";  // Utilisez le mot de passe de votre serveur MySQL, s'il y en a un.
$dbname = "test_db";

// Créer la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
