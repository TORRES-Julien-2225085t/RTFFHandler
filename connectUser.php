<?php
session_start();
require_once './Database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $account_id = $_POST['account_id'];
    $password = $_POST['password']; // mot de passe non haché fourni par l'utilisateur

    $query = "SELECT * FROM ACCOUNT WHERE account_id = :account_id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':account_id', $account_id);

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $row['password']; // mot de passe haché stocké dans la base de données

            if (password_verify($password, $hashed_password)) {
                // Les identifiants sont corrects
                $_SESSION['account_id'] = $row['account_id'];
                $_SESSION['display_name'] = $row['display_name'];
                // et autres informations que vous souhaitez stocker dans la session
                echo "Connexion réussie ! Bienvenue " . $row['display_name'] . "!";
            } else {
                // Le mot de passe est incorrect
                echo "Identifiant/Mot de passe incorrect !";
            }
        } else {
            // Aucun utilisateur trouvé avec cet email
            echo "Identifiant/Mot de passe incorrect !";
        }
    } else {
        echo "Une erreur est survenue lors de la connexion.";
    }
}
?>
<?php
require_once './navigation.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Page</title>
</head>
<body>
<div style="margin-left:220px; padding:10px;">
    <!-- Le contenu de votre page -->
</div>
</body>
</html>
<!-- Formulaire de connexion -->
<form method="post"  action="connectUser.php">
    Email: <input type="email" name="account_id" required><br>
    Mot de Passe: <input type="password" name="password" required><br>
    <input type="submit" value="Se connecter">
</form>
<a href="requestMailForReset.php">Mot de passe oublié</a><br>
<a href="createUser.php">Créer un compte</a>