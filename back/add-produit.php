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
    include("conn.php");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $image_name =$_POST['nom'] . "_" . date("H-i-s") . "." . $ext;
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        $image_path = $_SERVER['DOCUMENT_ROOT'] . "/ppe/img/produit/" . $image_name;
        move_uploaded_file($image_tmp_name, $image_path);

      $sql = "INSERT INTO produit (marque, nom, description, prix, couleur, img) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ucfirst($_POST['marque']),
            $_POST['nom'],
            $_POST['description'],
            $_POST['prix'],
            $_POST['couleur'],
            "/ppe/img/produit/" . $image_name
        ]);
        header("Location: ../admin/gestion-produits.php?upload=success");
        exit;
    }

    ?>
    
</body>
</html>