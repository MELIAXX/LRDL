<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='./css/search.css' type='text/css' />
    <title>Document</title>
</head>

<body>
    <?php
    include("nav.php");
    include("panier.php");

    // Récupération des valeurs distinctes de marque et couleur pour alimenter les filtres
    $sql = "SELECT DISTINCT marque FROM produit";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resm = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT DISTINCT couleur FROM produit";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resc = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM cat";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $rescat = $stmt->fetchAll(PDO::FETCH_ASSOC);

    /*
     * L'opérateur ?? retourne la valeur de gauche si elle existe, sinon []
     * Le cast (array) garantit qu'on obtient toujours un tableau
     */
    $tags = (array)($_GET['tags']   ?? []);
    $marques = (array)($_GET['marque'] ?? []);


    $params = [];
    $params[':search'] = '%' . ($_GET['search'] ?? '') . '%';
    $sql = "SELECT * FROM produit WHERE (nom LIKE :search OR description LIKE :search)";

    // Filtres sur les plages de prix (min et max sont indépendants)
    if (!empty($_GET['prix-min'])) {
        $sql .= " AND prix >= :prixmin";
        $params[':prixmin'] = $_GET['prix-min'];
    }

    if (!empty($_GET['prix-max'])) {
        $sql .= " AND prix <= :prixmax";
        $params[':prixmax'] = $_GET['prix-max'];
    }

    if (!empty($_GET['couleur'])) {
        $sql .= " AND couleur = :couleur";
        $params[':couleur'] = $_GET['couleur'];
    }


    if (!empty($marques)) {

        //$i compte les marque selectioner => :marque$i => on les colle ensemble avec des ",
        $placeholders = implode(',', array_map(fn($i) => ":marque$i", range(0, count($marques) - 1)));

        $sql .= " AND marque IN ($placeholders)";
        foreach ($marques as $i => $marque) {
            $params[":marque$i"] = $marque;
        }
    }

    //meme chose que si dessus avec les tags
    if (!empty($tags)) {
        $tph = implode(',', array_map(fn($i) => ":tag$i", range(0, count($tags) - 1)));
        $sql .= " AND id IN (SELECT pro_id FROM pro_cat WHERE cat_id IN ($tph))";
        foreach ($tags as $i => $tag) {
            $params[":tag$i"] = $tag;
        }
    }

    // Filtres sur les caractéristiques techniques : boutons, poids, DPI
    if (!empty($_GET['nb-btn'])) {
        $sql .= " AND nb_btn = :nbbtn";
        $params[':nbbtn'] = $_GET['nb-btn'];
    }

    if (!empty($_GET['poids-min'])) {
        $sql .= " AND poids >= :poidsmin";
        $params[':poidsmin'] = $_GET['poids-min'];
    }

    if (!empty($_GET['poids-max'])) {
        $sql .= " AND poids <= :poidsmax";
        $params[':poidsmax'] = $_GET['poids-max'];
    }

    if (!empty($_GET['dpi-min'])) {
        $sql .= " AND dpi >= :dpimin";
        $params[':dpimin'] = $_GET['dpi-min'];
    }

    if (!empty($_GET['dpi-max'])) {
        $sql .= " AND dpi <= :dpimax";
        $params[':dpimax'] = $_GET['dpi-max'];
    }


    if (isset($_GET['filaire'])) {
        if ($_GET['filaire'] == '1') {
            $sql .= " AND filaire = 1";
        } elseif ($_GET['filaire'] == '0') {
            $sql .= " AND filaire = 0";
        }
    }

    // Exécution de la requête finale avec tous les paramètres liés
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    ?>

    <div class="container">
        <form class="filter" method="GET" action="">

            <h1>Filtre</h1>

            <!-- Champ caché pour conserver le terme de recherche lors de l'application des filtres -->
            <input type="hidden" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">

            <label for="prix">Prix :</label>
            <div class="prix">
                <input type="number" class="prixmin" name="prix-min" value="<?php echo htmlspecialchars($_GET['prix-min'] ?? ''); ?>" placeholder="Prix min">
                <p>-</p>
                <input type="number" class="prixmax" name="prix-max" value="<?php echo htmlspecialchars($_GET['prix-max'] ?? ''); ?>" placeholder="Prix max">
            </div>

            <br>

            <label for="couleur">Couleur :</label>
            <div class="couleur">
                <select name="couleur" id="couleur">
                    <!-- Option vide = aucun filtre couleur -->
                    <option value="" selected='selected'>  </option>
                    <?php
                    foreach ($resc as $result) {
                        // 'selected' si
                        echo "<option value='" . htmlspecialchars($result['couleur']) . "' " . (isset($_GET['couleur']) && $_GET['couleur'] == $result['couleur'] ? 'selected' : '') . ">" . htmlspecialchars(ucfirst($result['couleur'])) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <br>

            <label for="marque">Marque :</label>
            <div class="marque">
                <div>
                    <?php
                    foreach ($resm as $result) {
                        // case cochée marque présente dans le tableau $marques 
                        echo "<input type='checkbox' name='marque[]' id='color" . htmlspecialchars($result['marque']) . "' value='" . htmlspecialchars(ucfirst($result['marque'])) . "' " . (in_array($result['marque'], $marques) ? 'checked' : '') . ">";
                        echo "<label for='color" . htmlspecialchars($result['marque']) . "'>" . htmlspecialchars(ucfirst($result['marque'])) . "</label><br>";
                    }
                    ?>
                </div>
            </div>

            <br>

            <label for="nb-btn">Nombre de boutons :</label>
            <div class="nb-btn">
                <input type="number" name="nb-btn" id="nb-btn" value="<?php echo htmlspecialchars($_GET['nb-btn'] ?? ''); ?>">
            </div>

            <br>

            <label for="poids">Poids :</label>
            <div class="poids">
                <input type="number" name="poids-min" id="poids-min" value="<?php echo htmlspecialchars($_GET['poids-min'] ?? ''); ?>" placeholder="Poids min (g)">
                <p>-</p>
                <input type="number" name="poids-max" id="poids-max" value="<?php echo htmlspecialchars($_GET['poids-max'] ?? ''); ?>" placeholder="Poids max (g)">
            </div>

            <br>

            <label for="dpi">DPI :</label>
            <div class="dpi">
                <input type="number" name="dpi-min" id="dpi-min" value="<?php echo htmlspecialchars($_GET['dpi-min'] ?? ''); ?>" placeholder="DPI min">
                <p>-</p>
                <input type="number" name="dpi-max" id="dpi-max" value="<?php echo htmlspecialchars($_GET['dpi-max'] ?? ''); ?>" placeholder="DPI max">
            </div>

            <br>

            <div>
                <input type="checkbox" name="filaire" id="filaire" value="1" <?php echo isset($_GET['filaire']) && $_GET['filaire'] == '1' ? 'checked' : ''; ?>>
                <label for="filaire">Filaire uniquement</label>
            </div>

            <br>

            <label for="tags">Tags :</label>
            <div class="tags">
                <?php
                foreach ($rescat as $result) {
                    echo "<div class='rescat'>";
                    // case cochée si id de la catégorie est dans $tags
                    // Note : on cast en string car $_GET retourne des chaînes, et in_array est strict sur les types
                    echo "<input type='checkbox' name='tags[]' id='tag" . htmlspecialchars($result['c_nom']) . "' value='" . htmlspecialchars($result['id']) . "' " . (in_array((string)$result['id'], $tags) ? 'checked' : '') . ">";
                    echo "<label for='tag" . htmlspecialchars($result['c_nom']) . "'>" . htmlspecialchars(ucfirst($result['c_nom'])) . "</label>";
                    echo "</div>";
                }
                ?>
            </div>

            <br>

            <input type="submit" value="Filtrer">
            <!--
                Bouton reset : conserve les valeurs GET dans l'URL. On force une redirection vers
                search.php en ne gardant que le terme de recherche pour vider tous les filtres.
            -->
            <input type="reset" value="Réinitialiser" onclick="window.location.href='search.php?search=<?php echo urlencode($_GET['search'] ?? ''); ?>'">
        </form>

        <div class="results">
            <?php
            if (!empty($_GET['search'])) {
                if ($results) {
                    echo "<h2 id='search-res'>Résultats pour la recherche : " . htmlspecialchars($_GET['search']) . "</h2>";
                    foreach ($results as $produit) {
                        echo "<div class='produit'>";
                        echo "<img src='" . htmlspecialchars($produit['img']) . "' alt='Image du produit'>";
                        echo "<div class='info'>";
                        echo "<h2>" . htmlspecialchars(ucfirst($produit['nom'])) . "</h2>";
                        echo "<p>Marque: " . htmlspecialchars(ucfirst($produit['marque'])) . "</p>";
                        echo "<p class='desc'>" . htmlspecialchars(ucfirst($produit['description'])) . "</p>";
                        echo "</div>";
                        echo "<div class='actions'>";
                        echo "<p>Prix: " . htmlspecialchars($produit['prix']) . "€</p>";

                        echo "<form method='get' action='back/panierModifier.php' name='panier-form'>";
                        echo "<input type='hidden' name='p_id' value='" . $produit['id'] . "'>";
                        echo "<input type='submit' class='add-to-cart' value='Ajouter au panier'>";
                        echo "</form>";
                        echo "</div>";
                        echo "</div>";
                        echo "<hr>";
                    }
                } else {
                    echo "Aucun résultat trouvé pour '" . htmlspecialchars($_GET['search']) . "'";
                }
            } else {
                echo "Veuillez entrer un terme de recherche.";
            }
            ?>
        </div>
    </div>

</body>

</html>
