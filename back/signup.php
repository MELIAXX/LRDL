<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    echo var_dump($_POST) . '<br>';
    echo $_POST['pw'] . '<br>';
    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['pw'])) {
        try {
            include("conn.php");
        } catch (PDOException $e) {
            die(print_r($e));
        }

        // (?=.* ..) au moins 1 .. nimporte où | \d = [0-9] | ^ du debut $ a la fin 
        $pass_pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/';
        $email_pattern = '/^[A-Za-z0-9._-]+@[a-z]+\.[a-z]{2,3}$/';

        if (preg_match($email_pattern, $_POST['email'])) {
            if (preg_match($pass_pattern, $_POST['pw'])) {
                $hpw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
                $sql = "INSERT INTO client (nom, prenom, email, pw) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$_POST['nom'], $_POST['prenom'], $_POST['email'], $hpw]);
                echo "Inscription réussie !";

                session_start();
                $_SESSION['nom'] = $_POST['nom'];
                $_SESSION['prenom'] = $_POST['prenom'];
                header("Location: ../index.php");



            } else {
                echo "Mot de passe invalide. Il doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
                exit;
            }
        } else {
            echo "Email invalide.";
            exit;
        }
    } else {
        echo "Veuillez remplir tous les champs du formulaire.";
        exit;
    }

    ?>
</body>

</html>