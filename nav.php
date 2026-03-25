<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/nav.css' type='text/css' />
    <title>Document</title>
</head>
<body>
    <?php
    session_start();
    include("back/conn.php");
    
    ?>
    <nav>

    
        <div class="nav1">
            <a class="brand-link" href="/ppe/index.php"><img class="logo" src="/ppe/img/logo-b.png" alt=""><div class="name"><p class="p1">LR</p><p class="p2">DL</p></div></a>
            <form class="search-form" method="GET" action="/ppe/search.php">
                <input type="text" name="search" placeholder="Rechercher un produit..." class="search-input" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                <button type="submit" class="search-btn">🔍</button>
            </form>
            <?php
            

            if (isset($_SESSION['id']) && $_SESSION['admin'] == 1) {
                echo "<div class='profil'><a href='/ppe/admin/gestion-produits.php'>Moderation</a>";
                echo "<a href='/ppe/back/logout.php'>Déconnexion</a></div>";
            } elseif (isset($_SESSION['id']) && $_SESSION['admin'] == 0) {
                echo "<div class='profil'><button type='button' id='panier'>Panier</button>";
                echo "<a href='/ppe/back/logout.php'>Déconnexion</a></div>";
            }else {
                echo "<div class='profil'><a href='/ppe/connection.php'>Connexion</a>";
                echo "<a href='/ppe/inscription.php'>Inscription</a></div>";
            }

            $sql = "SELECT * FROM `pro_cat` JOIN produit on pro_cat.pro_id = produit.id JOIN cat ON cat.id = pro_cat.cat_id;";
            ?>
        </div>
        <div class="nav2">
        <!--<a class="menu" href="#"><img src="./img/menu-burger-b.png" alt="">Menu</a> -->
            <a href="/ppe/search.php?search=souris&tags=5">Souris bureautique</a>
            <a href="/ppe/search.php?search=souris&tags=3">Souris gaming</a>
            <a href="/ppe/search.php?search=souris&filaire=1">Souris fillaire</a>
            <a href="/ppe/search.php?search=souris&filaire=0">Souris sans-fil</a>
            <a href="/ppe/search.php?search=souris&tags=4">Souris ergonomique</a>
            <a href="/ppe/search.php?search=souris&tags=6">Souris nomade</a>
        </div>
    </nav>
</body>
</html>
