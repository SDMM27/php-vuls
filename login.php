<?php
include('connect.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Code vulnérable à l'injection SQL
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];
        // Créer un cookie pour le rôle qui peut être facilement manipulé
        setcookie("userRole", $row['role'], time()+3600);  // Vulnérabilité ici
        
        header("Location: dashboard.php");
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="stle.css">
</head>
<body>
    <h2>Connexion</h2>
    <form method="post">
        Nom d'utilisateur: <input type="text" name="username"><br>
        Mot de passe: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>

    <!-- Section d'explication de la faille -->
    <div>
        <h3>Explication de la faille d'injection SQL</h3>
        <p>La faille se trouve dans la requête SQL qui est formée par concaténation directe des entrées utilisateur :</p>
        <pre>$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";</pre>
        <p>Cette méthode permet à un attaquant d'injecter du SQL malveillant. Par exemple, en entrant admin' OR '1'='1 ou admin' -- dans le champ du nom d'utilisateur et en saisissant n'importe quoi en mot de passe, ils peuvent bypasser l'authentification.</p>

        <h3>Comment corriger cette faille</h3>
        <p>Pour corriger cette faille, nous devons utiliser des requêtes préparées :</p>
        <pre>$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();</pre>
        <p>Les requêtes préparées séparent les données des commandes SQL, empêchant ainsi l'exécution de SQL malveillant.</p>
    </div>
</body>
</html>
