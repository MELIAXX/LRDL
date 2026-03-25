<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='./css/index.css' type='text/css' />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <title>Document</title>
</head>



<body>

    <?php
    include("nav.php");
    include("panier.php");
    if (isset($_GET['success'])) {
        echo "<script> afficherPanier(); </script>";
    }

    if (empty($_SESSION['id']) && !empty($_COOKIE['id'])) {
        try {


            $sql = "SELECT id, nom, prenom FROM client WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$_COOKIE['id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['nom'] = $user['nom'];
                $_SESSION['prenom'] = $user['prenom'];
            }
        } catch (PDOException $e) {
            die(print_r($e));
        }
    }

    $sql = "SELECT * FROM produit LIMIT 4";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT cat.c_nom FROM `pro_cat` JOIN produit on pro_cat.pro_id = produit.id JOIN cat ON cat.id = pro_cat.cat_id;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $pro_cat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>



    <section class="img_h">
        <img class="img" src="/ppe/img/intro_m2.png" alt="">
        <div>
            <h1>Trouvez la souris optimale, alliant précision, confort et design, pour une expérience idéale quelle que soit votre utilisation</h1>
            <a href="#stuff">Découvrir</a>
        </div>
    </section>

    <h1 class="preview" style="text-decoration: underline;">Les plus réputé :</h1>
    <section id="stuff">
        <div class="produits">

            <?php
            foreach ($produits as $produit) {
                $id = $produit['id'];
                echo "<div class='produit'>";
                echo "<img src='" . ($produit['img']) . "' alt='" . htmlspecialchars($produit['nom']) . "'>";
                echo "<div class='info'>";
                echo "<h2>" . htmlspecialchars($produit['nom']) . "</h2>";
                echo "<p>" . htmlspecialchars($produit['marque']) . "</p>";
                echo "<p class='prix'>" . htmlspecialchars($produit['prix']) . "€</p>";
                echo "<p>" . htmlspecialchars($produit['dpi']) . " dpi</p>";
                echo "<p>" . htmlspecialchars($produit['poids']) . "g</p>";
                echo "<p>" . htmlspecialchars($produit['nb_btn']) . " boutons</p>";
                echo "<br>";
                echo "<form method='get' action='back/panierModifier.php' name='panier-form'>";
                echo "<input type='hidden' name='p_id' value='" . $produit['id'] . "'>";
                echo "<input class='btn-panier' type='submit' value='Ajouter au panier'>";
                echo "</form>";
               
                echo "</div>";
                echo "</div>";
            }
            
            ?>
        </div>
    </section>

</body>

</html>
