<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='./css/inscription.css' type='text/css' />
    <title>Inscription</title>
</head>

<body>
    <nav class="nav">
        <div class="nav">
            <a class="home" href="index.php"><img src="img/logo.png" alt="Logo" class="logo" /></a>
        </div>
    </nav>

    <div class="formulaire">
        <form method="post" action="back/signup.php">
            <h2>Inscription</h2>
            <div class="inputs">
                <p></p><input type="text" placeholder="Nom" name="nom">
                <p></p><input type="text" placeholder="Prénom" name="prenom">
                <p></p><input type="email" placeholder="E-mail" name="email">
                <p></p><input type="password" placeholder="Mot de passe" name="pw">
                <input type="submit" value="S'inscrire" name="btok">
        </form>
    </div>
    </div>
</body>

</html>

<style></style>