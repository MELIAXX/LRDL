<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='./css/connexion.css' type='text/css' />
    <title>Connexion</title>
</head>

<body>
    <nav class="nav">
        <div class="nav">
            <a class="home" href="index.php"><img src="img/logo.png" alt="Logo" class="logo" /></a>
        </div>
    </nav>
    <?php
    if (isset($_GET['error'])) {
        if ($_GET['error'] == 1) {
            echo "<p class='error'>Mot de passe incorrect</p>";
        } elseif ($_GET['error'] == 2) {
            echo "<p class='error'>Veuillez remplir tous les champs du formulaire.</p>";
        } elseif ($_GET['error'] == 3) {
            echo "<p class='error'>Adresse e-mail non trouvée.</p>";
        }
    }
    ?>
    <div class="formulaire">
        <form method="post" action="back/login.php">
            <h2>Connexion</h2>
            <div class="inputs">
                <p></p><input type="email" placeholder="E-mail" name="email">
                <p></p><input type="password" placeholder="Mot de passe" name="pw">
                <p></p> <input type="checkbox" id="remember" name="remember" value="remember"> <label for="remember">Se souvenir de moi</label>
                <div id="bouton"><input type="submit" value="Se connecter" name="btok"></div>
        </form>
    </div>
    </div>
</body>

</html>