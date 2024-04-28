<?php
$output = '';
if (isset($_POST['input'])) {
    // La faille se trouve ici, où l'entrée utilisateur est passée directement à la fonction system()
    $output = shell_exec($_POST['input']);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Injection de Commande</title>
    <link rel="stylesheet" type="text/css" href="stle.css">
</head>
<body>
    <h1>Test d'injection de commande</h1>
    <form method="post">
        <label for="input">Entrez une commande :</label>
        <input type="text" id="input" name="input">
        <button type="submit">Exécuter</button>
    </form>

    <h2>Résultat :</h2>
    <pre><?php echo htmlspecialchars($output, ENT_QUOTES, 'UTF-8'); ?></pre>

    <h2>Explication de la faille</h2>
    <p>Cette page permet l'exécution de commandes système directement à partir des entrées utilisateur sans aucune validation ou échappement. Cela peut permettre à un attaquant d'exécuter des commandes arbitraires sur le serveur, ce qui pose un risque de sécurité majeur.</p>
    <pre> $output = shell_exec($_POST['input']);
    </pre>

    <h2>Comment exploiter cette faille</h2>
    <p>Un attaquant peut entrer des commandes UNIX ou Windows dans le champ de saisie qui seront exécutées par le serveur. Par exemple, entrer <code>dir</code> sur un serveur Windows ou <code>ls</code> sur un serveur UNIX listera les fichiers du répertoire courant du serveur. Ou pire encore comme supprimer tous les fichiers entre autres.</p>
   
    <h2>Comment corriger cette faille</h2>
    <p>Pour prévenir les injections de commande, suivez ces étapes :</p>
    <ul>
        <li><strong>Validation des entrées :</strong> Validez strictement les entrées pour s'assurer qu'elles ne contiennent que des données sécurisées avant de les traiter. Par exemple, permettez seulement les entrées alphanumériques si possible.</li>
        <li><strong>Échappement des entrées :</strong> Échappez les caractères spéciaux dans les entrées utilisateur pour prévenir leur interprétation comme partie d'une commande.</li>
        <li><strong>Utilisation de fonctions plus sécurisées :</strong> Au lieu d'utiliser <code>shell_exec()</code> ou <code>system()</code>, envisagez des alternatives plus sûres qui limitent la possibilité d'exécuter des commandes arbitraires, comme des bibliothèques spécifiques pour les tâches nécessaires.</li>
    </ul>
    <pre> if (preg_match('/^[a-zA-Z0-9]+$/', $_POST['input'])) {
    $output = shell_exec('cat ' . escapeshellarg($_POST['input']));
} else {
    $output = 'Entrée non valide.';
}

    </pre> ou si il faut afficher le contenu d'un fichier :  
    <pre> if (in_array($_POST['input'], ['file1.txt', 'file2.txt'])) {
    $output = file_get_contents($_POST['input']);
} else {
    $output = 'Fichier non autorisé.';
}

    </pre>

    <button onclick="window.location.href='cookie_vulnerabilit.php';">Accéder à la page de la vulnérabilité cookie </button>
</body>
</html>
