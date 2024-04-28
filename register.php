<?php
include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']); // Vulnérabilité ici: le rôle est contrôlé par l'input utilisateur

    // Vérifier si l'utilisateur existe déjà
    $checkUser = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkUser->bind_param("s", $username);
    $checkUser->execute();
    $result = $checkUser->get_result();

    if ($result->num_rows > 0) {
        echo "Ce nom d'utilisateur est déjà pris.";
    } else {
        // Hasher le mot de passe avant de l'enregistrer
        $hashed_password = $password;

        // Insérer le nouvel utilisateur avec le rôle fourni
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $role);
        $stmt->execute();

        if ($stmt->affected_rows === 1) {
            echo "Inscription réussie!";
        } else {
            echo "Erreur lors de l'inscription.";
        }
    }
}
?>

<html>
<head>
    <title>Inscription</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="stle.css">
</head>
<body>
    <h2>Inscription</h2>
    <form method="post">
        Nom d'utilisateur: <input type="text" name="username" required><br>
        Mot de passe: <input type="password" name="password" required><br>
        <!-- Champ caché pour le rôle, vulnérable aux modifications par l'utilisateur -->
        <input type="hidden" name="role" value="user">
        <input type="submit" value="S'inscrire">
    </form>

    <h3>Comment exploiter la faille</h3>
    <p>Cette page contient une faille de sécurité où le rôle de l'utilisateur peut être directement influencé par les entrées de l'utilisateur. Pour exploiter cette faille :</p>
    <ol>
        <li>Ouvrez les outils de développement de votre navigateur (F12 ou clic droit -> Inspecter).</li>
        <li>Trouvez le champ caché dans le formulaire d'inscription avec le nom 'role'.</li>
        <li>Changez la valeur de 'user' à 'admin' dans ce champ.</li>
        <li>Soumettez le formulaire pour vous inscrire en tant qu'administrateur.</li>
    </ol>
    <p>Normalement, le rôle ne devrait jamais être déterminé par l'utilisateur et devrait être géré côté serveur pour éviter de telles escalades de privilèges.</p>
    <button onclick="window.location.href='login.php';">se connecter </button>
</body>
</html>
