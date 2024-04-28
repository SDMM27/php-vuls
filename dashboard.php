<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include('connect.php');

$message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST['user_input']; // données utilisateur vulnérables à XSS
    $message = $user_input; // stockage sans filtrage pour démonstration
}
echo "Bienvenue " . $_SESSION['username'] . "! Vous êtes connecté en tant que " . $_COOKIE['userRole'];
?>

<html>
<head>
    <title>Tableau de bord</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="stle.css">
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <h2>Tableau de bord</h2>
    <form method="post">
        <label for="user_input">Saisissez votre message :</label>
        <input type="text" id="user_input" name="user_input">
        <button type="submit">Envoyer</button>
    </form>

    <p>Message: <?php echo $message; ?></p> <!-- Point d'injection de XSS -->

    <h3>Explication de la faille XSS</h3>
    <p>La faille XSS ci-dessus permet à du code JavaScript arbitraire d'être exécuté dans le navigateur de l'utilisateur. Cela se produit parce que le contenu de l'entrée utilisateur est rendu sans être échappé, permettant à des balises script potentiellement malveillantes d'être exécutées.</p>
    <p>Pour exploiter cette vulnérabilité, un utilisateur pourrait saisir un script malveillant, tel que <code>&lt;script&gt;alert('XSS');&lt;/script&gt;</code>, qui s'exécuterait lors de l'affichage de cette page.</p>

    <h3>Comment corriger cette faille</h3>
    <p>Pour corriger cette vulnérabilité et empêcher l'exécution de scripts non désirés, il faudrait échapper toutes les entrées utilisateur avant de les afficher sur la page. Voici comment on peut modifier le code :</p>
    <code>
    &lt;p&gt;Message: &lt;?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?&gt;&lt;/p&gt;
    </code>
    <p>Cette modification utilise la fonction PHP <code>htmlspecialchars()</code> pour échapper à tous les caractères spéciaux dans les entrées utilisateur, les rendant sûrs à afficher. Les balises HTML, y compris les scripts malveillants, seront alors traitées comme du texte simple et non comme du code exécutable.</p>

    <h3>Pratiques supplémentaires de sécurisation</h3>
    <p>En plus d'échapper aux sorties, on peut également mettre en œuvre les pratiques suivantes pour renforcer la sécurité contre les attaques XSS :</p>
    <ul>
        <li>Utiliser des en-têtes HTTP de sécurité comme <code>Content-Security-Policy</code> pour contrôler les sources des ressources exécutables. : header("Content-Security-Policy: default-src 'self'; script-src 'self';");</li>
        <li>Valider toutes les entrées pour s'assurer qu'elles correspondent aux attentes (par exemple, vérifier la longueur, les types de données, et les formats). : 
           
        </li>
    </ul>
    <pre>if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = $_POST['user_input'];

    if (!preg_match("/^[a-zA-Z0-9 ]*$/", $input)) {
        $error = "Seuls les caractères alphanumériques et les espaces sont autorisés.";
    } else if (strlen($input) &gt; 100) {
        $error = "L'entrée ne peut pas dépasser 100 caractères.";
    } else {
        $message = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    }
}</pre>

    <ul>
        <li>Ne stockez pas les données de contrôle d'accès dans les cookies. Utilisez les sessions sécurisées côté serveur.</li>
        <li>Si vous devez utiliser des cookies, assurez-vous qu'ils sont configurés avec les flags HttpOnly et Secure pour renforcer leur sécurité.</li>
    </ul>
    <p>Sur la page login.php le cookie est assigné simplement comme tel : </p>
    <pre>setcookie("userRole", $row['role'], time()+3600);</pre>
    <p>Il est modifiable par n'importe qui via les outils de développement dans l'onglet application et c'est évidemment un problème. </p>
    <p>Voici un exemple de configuration de cookie sécurisée :</p>
    <pre>setcookie("nom", "valeur", [ "expires" => time() + 3600, "path" => "/", "domain" => "example.com", "secure" => true, "httponly" => true ] );</pre>

   
    <button onclick="window.location.href='command.php';">Accéder à la page Commande bash/powershell </button>
</body>
</html>
