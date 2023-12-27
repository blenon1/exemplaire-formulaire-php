<?php 
    //inclusion d'un fichier php
    require('src/connection.php');

    //verification de si les champs son remplie
    if(!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password'])
    && !empty($_POST['password_confirm']) && isset($_POST['button'])) {
    //declaration des variables
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'] ;
    $pass_confirm = $_POST['password_confirm'];
    
    //verification/test de la concordance du password et de password-confirm
    if($password != $password_confirm) {
        header('location: ../?error=1&pass=1');
    }

    //verification de si l'addresse email est deja utiliser!!
    $request = $conn->prepare("SELECT count(*) as numberEmail FROM users WHERE email = ?");
    $request->execute(array($email));

    while($email_verification = $request->fetch()){
        if($email_verification['numberEmail'] != 0) {
            header('location: ../?error=1&email=1');
        }
    }

    //Haching..............
    $cle = sha1($email).rand();
    $cle = sha1($cle).rand().rand();

    //CRYPTAGE PASSWORD
    $password = "aq1".sha1($password."1994").rand()."28";

    //envoi du données
    $request = $conn->prepare('INSERT INTO users(pseudo, email, password, cle) VALUES(?, ?, ?, ?)');
    $request->execute(array($pseudo, $email, $password, $cle));

    header('location: ../?success=1');

    }



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/design/style.css">
    <title>William BLENON</title>
</head>
<body>
    <header>
            <h1>inscription</h1>
    </header>
   
    <main>  
        <p>Bienvenu sur mon site internet, pour en savoir plus veuillez vous inscrire 😍 !!! <a href="connexion.php">sinon connectez-vous</a></p>

        <?php 
            if(isset($_GET['error'])) {
                if(isset($_GET['pass'])){
                    echo '<p id="error"><span>⚠️ </span>Les mots de passes se sont pas identiques <span>⚠️ </span></p>';
                } else if(isset($_GET['email'])) {
                    echo '<p id="error"><span> ⚠️ </span> Cette addresse email est deja utilisé <span> ⚠️ </span></p>';
                } 
            } else if(isset($_GET['success'])) {
                echo '<p id="success"><span> 😁 </span>Féliciation votre inscription à été bien prise en compte<span> 😁 </span></p>';
            }
        ?>

        <form action="index.php" method="post">
            <label for="pseudo">pseudo</label>
            <input type="text" name="pseudo" required placeholder="Ex: John">

            <label for="email">email</label>
            <input type="email" name="email" id="email" placeholder="Ex: exemple@gmail.com">

            <label for="password">mot de passe</label>
            <input type="password" name="password" required placeholder="Ex: *****">

            <label for="password_confirm">confirmation</label>
            <input type="password" name="password_confirm" required placeholder="Ex: *****">

            <input type="submit" name="button" value="Inscription">
        </form>
    </main>
</body>
</html>