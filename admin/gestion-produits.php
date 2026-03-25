<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='../css/nav.css' type='text/css' />
    <link rel='stylesheet' href='css/gestion-produits.css' type='text/css' />
    <title>Document</title>
</head>

<body>


    <div id="bg"></div>
    

    <?php
    include("../nav.php");
    include("../back/conn.php");


    $sql = "SELECT * FROM produit";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM cat";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tags = $stmt->fetchAll(PDO::FETCH_ASSOC);



    $sql = "SELECT  produit.*, GROUP_CONCAT(cat.id) as cat_id, GROUP_CONCAT(cat.c_nom) as c_nom FROM produit LEFT JOIN pro_cat ON produit.id = pro_cat.pro_id LEFT JOIN cat ON pro_cat.cat_id = cat.id GROUP BY produit.id;";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $p_tags = $stmt->fetchAll(PDO::FETCH_ASSOC);




    ?>

    <!-- <button id="btn" type="button">Ajouter produit</button> -->
     <br><br>
    <div class="list">

        <div id="produit-add">
            <p>+</p>
        </div>
        <?php

        foreach ($p_tags as $produit) {


            echo "<div class='produit' name='" . htmlspecialchars($produit['id']) . "'>";
            echo "<div class='front'>";
            echo "<img src='" . htmlspecialchars($produit['img']) . "' alt='Image du produit'>";
            echo "<p class='marque'>" . htmlspecialchars($produit['marque']) . "</p>";
            echo "<h2>" . htmlspecialchars($produit['nom']) . "</h2>";
            echo "</div>";
            echo "<div class='info'>";
            echo "<p>" . htmlspecialchars($produit['prix']) . "€</p>";
            echo "<p>" . ($produit['filaire'] ? 'Filaire' : 'Sans fil') . "</p>";
            echo "<p>" . htmlspecialchars($produit['couleur']) . "</p>";
            echo "<p>" . htmlspecialchars($produit['dpi']) . "</p>";
            echo "<p>" . htmlspecialchars($produit['poids']) . "g</p>";
            echo "<p>" . htmlspecialchars($produit['nb_btn']) . " boutons</p>";
            echo "</div>";
            echo "</div>";

            //formulaire edit//
            echo "<div class='edit-produit'>";
            echo "<p class='b-exit'>x</p>";
            echo "<h2 class='title'>Modifier produit</h2>";
            echo "<form action='/ppe/back/modify-produit.php' method='post' class='edit-form' enctype='multipart/form-data'>";
            echo "<div>";
            echo "<div>";
            echo "<input type='file' name='image' accept='image/*' id='image'>";
            echo "<label for='image' style='font-size: 10px; color: gray;'>(Laisser vide pour ne pas changer l'image)</label>";
            echo "</div>";
            echo "<div>";
            echo "<label for='filaire'>Filaire :</label>";
            echo "<input type='checkbox' name='filaire' id='filaire'" . ($produit['filaire'] ? 'checked' : '') . ">";
            echo "</div>";
            echo "</div>";
            echo "<input type='text' name='marque' placeholder='Marque' value='" . htmlspecialchars($produit['marque']) . "'>";
            echo "<input type='text' name='nom' placeholder='Modèle' value='" . htmlspecialchars($produit['nom']) . "'>";
            echo "<div class='prix'>";
            echo "<input type='number' name='prix' placeholder='Prix' value='" . htmlspecialchars($produit['prix']) . "'>";
            echo "<p>€</p>";
            echo "</div>";
            echo "<input type='text' name='couleur' placeholder='Couleur' value='" . htmlspecialchars($produit['couleur']) . "'>";
            
            echo "<div class='tags_des'>";
            echo "<fieldset class='tags'>";
            echo "<legend> Catégories </legend>";
            foreach ($tags as $tag) {
                $cat_ids = explode(',', $produit['cat_id']);
                echo "<div class='tag'>";
                echo "<label for='".$tag['id'] ."'>" . ucfirst($tag['c_nom']) . "</label>";
                //verifie si tag[id] est dans cat_ids => si oui "checked", si non ""(rien)
                echo "<input type='checkbox' name='tags[]' value='" . $tag['id'] . "' " . (in_array($tag['id'], $cat_ids) ? 'checked' : '') . ">";
                echo "</div>";
            }
            echo "</fieldset>";
            echo "<textarea name='description' class='description' placeholder='Description'>" . htmlspecialchars($produit['description']) . "</textarea>";
            echo "</div>";

            echo "<div class='detail'>";
            echo "<div class='detail-items'>";
            echo "<label for='dpi'>DPI :</label>";
            echo "<input type='number' name='dpi' placeholder='0' value='" . htmlspecialchars($produit['dpi']) . "'>";
            echo "</div>";
            echo "<div class='detail-items'>";
            echo "<label for='poids'>Poids (g) :</label>";
            echo "<input type='number' name='poids' placeholder='0' value='" . htmlspecialchars($produit['poids']) . "'>";
            echo "</div>";
            echo "<div class='detail-items'>";
            echo "<label for='nb_btn'>Nombre de boutons :</label>";
            echo "<input type='number' name='nb_btn' placeholder='0' value='" . htmlspecialchars($produit['nb_btn']) . "'>";
            echo "</div>";
            echo "<input type='hidden' name='id' value='" . $produit['id'] . "'>";
            echo "</div>";
            echo "<input type='submit' value='Modifier'>";
            echo "</form>";
            echo "</div>";
        }
        ?>
    </div>

    <div class="ajout-produit">
        <p class="b-exit">x</p>
        <h2 class="title">Ajouter produit</h2>
        <form action="/ppe/back/add-produit.php" method="post" class="add-form" enctype="multipart/form-data">
            <div>
                <input type="file" name="image" accept="image/*" required>
                <div style="display: flex; flex-direction: row; width: 18%;">
                    <label for="filaire" style="width: 1500%">Filaire :</label>
                    <input type="checkbox" name="filaire" id="filaire" value="1">
                </div>
            </div>
            <input type="text" name="marque" placeholder="Marque" required>
            <input type="text" name="nom" placeholder="Modèle" required>
            <input type="number" name="prix" placeholder="Prix" required>
            <input type="text" name="couleur" placeholder="Couleur" required>
            <textarea name="description" class="description" placeholder="Description" required></textarea>
            <div class="detail">
                <input type="number" name="dpi" placeholder="DPI" required>
                <input type="number" name="poids" placeholder="Poids (g)" required>
                <input type="number" name="nb_boutons" placeholder="Nombre de boutons" required>
            </div>
            <input type="submit" value="Ajouter">
        </form>
    </div>

    <script>
        const bg = document.getElementById("bg");
        const form_a = document.querySelector(".ajout-produit");
        const b_exit = document.querySelectorAll(".b-exit");


        function show(element) {
            document.getElementById("bg").style.display = "block";
            element.style.display = "block";
            element.querySelector(".b-exit").style.display = "block";
        }

        function hide(element) {
            document.getElementById("bg").style.display = "none";
            element.style.display = "none";
            element.querySelector(".b-exit").style.display = "none";
        }

        function toggle(parent) {

            if (bg.style.display === "block") {
                hide(parent);
            } else {
                show(parent);
            }
        }

        const escape = document.addEventListener("keydown", function(event) {
            if (event.key === "Escape" && bg.style.display === "block") {
                document.querySelectorAll(".ajout-produit, .edit-produit").forEach(function(form) {
                    hide(form);
                });
            }
        });


        document.querySelectorAll(".produit").forEach(function(produit) {
            produit.addEventListener("click", function() {
                const produitId = this.getAttribute("name");
                const form_e = this.nextElementSibling;
                toggle(form_e);
            });
        });

        document.getElementById("produit-add").addEventListener("click", function() {
            toggle(form_a);
        });

        document.querySelectorAll(".b-exit").forEach(function(exit) {
            exit.addEventListener("click", function() {
                const parent = this.parentElement;
                toggle(parent);
            });
        });
    </script>




</body>

</html>