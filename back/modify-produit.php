<?php
include("conn.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $marque = $_POST['marque'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $description = $_POST['description'];
    $couleur = $_POST['couleur'];
    $filaire = isset($_POST['filaire']) ? 1 : 0;
    $nb_btn = $_POST['nb_btn'];
    $dpi = $_POST['dpi'];
    $poids = $_POST['poids'];

    $stmtParams = [$nom, $description, $prix, $marque, $couleur, $dpi, $poids, $nb_btn];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && $_FILES['image'] !== null) {
        $ext = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        $image_name = $nom . "_" . date("H-i-s") . "." . $ext;
        $image_tmp_name = $_FILES["image"]["tmp_name"];
        $image_path = $_SERVER['DOCUMENT_ROOT'] . "/ppe/img/produit/" . $image_name;
        move_uploaded_file($image_tmp_name, $image_path);
        $sql = "UPDATE produit SET nom=?, description=?, prix=?, marque=?, couleur=?, dpi=?, poids=?, nb_btn=?, img=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($stmtParams, ["/ppe/img/produit/" . $image_name, $id]));
        if (isset($_POST['tags'])) {
            $tags = $_POST['tags'];
            $sql = "DELETE FROM pro_cat WHERE pro_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            foreach ($tags as $tag) {
                $sql = "INSERT INTO pro_cat (pro_id, cat_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$id, $tag]);
            }
        }
        header("Location: ../admin/gestion-produits.php?upload=success");
        exit;
    } else {
        $sql = "UPDATE produit SET nom=?, description=?, prix=?, marque=?, couleur=?, dpi=?, poids=?, nb_btn=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($stmtParams, [$id]));
        if (isset($_POST['tags'])) {
            $tags = $_POST['tags'];
            $sql = "DELETE FROM pro_cat WHERE pro_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            foreach ($tags as $tag) {
                $sql = "INSERT INTO pro_cat (pro_id, cat_id) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$id, $tag]);
            }
        }
        header("Location: ../admin/gestion-produits.php?upload=success");
        exit;
    }
} else {
    header("Location: ../admin/gestion-produits.php?upload=error2");
    exit;
}

