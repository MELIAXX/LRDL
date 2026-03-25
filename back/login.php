<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php

    session_start();

    if (!empty($_POST['email']) || !empty($_POST['pw'])) {
        try {
            include("conn.php");
        } catch (PDOException $e) {
            die(print_r($e));
        }

        $email = $_POST['email'];
        $sql = "SELECT pw FROM client WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$email]);
        $pw = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$pw) {
            header("Location: ../connection.php?error=3");
            exit;
        }

        if (password_verify($_POST['pw'], $pw['pw'])) {
            $sql = "SELECT * FROM client WHERE email=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            $_SESSION['id'] = $user['id'];
            $_SESSION['nom'] = $user['nom'];
            $_SESSION['prenom'] = $user['prenom'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['admin'] = $user['admin'];
            $_SESSION['panier'] = [];

            if (isset($_POST['remember'])) {
                setcookie('id', $user['id'], time() + (86400 * 30), "/");
            }
            //supprime ancienne session si elle existe
            session_regenerate_id(true);
            header("Location: ../index.php");
            exit;
        } else {
            header("Location: ../connection.php?error=1");
            exit;
        }
    } else {
        header("Location: ../connection.php?error=2");
        exit;
    }
    ?>
</body>

</html>