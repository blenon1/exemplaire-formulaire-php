<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require('src/connection.php');

    if (!empty($_POST['email']) && !empty($_POST['password'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $error = 1;

        //Hash
        $password = "aq1".sha1($password."1994").rand()."28";
        
        // Utilisez une requête préparée pour éviter les injections SQL
        $request = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $request->execute(array($email));
        while($user = $request->fetch()){

            if ($password == $user['password']) {
                $error = 0;
                header('location: ../connexion.php?success=1');
                exit();
            }
        }
        if ($error == 1) {
            header('Location: ../connexion.php?error=1'); 
            exit();
        }

        
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/design/style.css">
    <title>Connexion</title>
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>
    
    <main>
        <p>Bienvenue sur mon site internet. Pour en savoir plus, veuillez vous inscrire 😍 !!! <a href="index.php">Sinon, inscrivez-vous</a></p>
        <?php 
            if (isset($_GET['error'])) {
                echo '<p id="error">Adresse email ou mot de passe incorrect</p>';
            } else if (isset($_GET['success'])) {
                echo '<p id="success">Vous êtes bien connecté</p>';
            }
        ?>
        <form action="connexion.php" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required placeholder="Ex: exemple@gmail.com">

            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required placeholder="Ex: *****">

            <div class="auto-connect">
                <input type="checkbox" name="cle_auto" id="cle_auto">
                <label for="cle_auto">Se souvenir de moi</label>
            </div>

            <input type="submit" name="button" value="Connexion">
        </form>
    </main>
</body>
</html>