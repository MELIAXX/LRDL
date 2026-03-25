<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='./css/panier.css' type='text/css' />
    <title>Panier</title>
</head>
<div class="bg" style="width: 100%; height: 100%; position: fixed; top: 0; left: 0; background-color: rgba(0,0,0,0.5); display: none; z-index: 1;"></div>
<div class="panier-cont">
    <div class="panier">
        <p id="close-panier">x</p>
        <h1>Panier</h1>
        <div class="under-pan"></div>
        <div class="list">
            <?php
            $prixPanier = 0;
            if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
                foreach ($_SESSION['panier'] as $id => $quantity) {
                    $sql = "SELECT * FROM produit WHERE id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$id]);
                    $produit = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($produit) {

                        $prixTotal = $produit['prix'] * $quantity;
                        $prixPanier += $prixTotal;



                        $prixDesc = strval($produit['prix']) . '€ X ' . $quantity;
                        echo "<div class='items'>";
                        echo "<div class='item-image'>";
                        echo "<img src='" . htmlspecialchars($produit['img']) . "' alt=''>";
                        echo "</div>";
                        echo "<div class='infos'>";
                        echo "<h2>" . htmlspecialchars($produit['nom']) . "</h2>";
                        echo "<h3>" . htmlspecialchars($produit['marque']) . "</h3>";

                        echo "<div class='q-p'>";

                        echo "<div class='quantity-mod'>";
                        echo "<p class='quantity-plus'>+</p>";
                        echo "<p class='item-quantity'>" . htmlspecialchars($quantity) . "</p>";
                        echo "<p class='quantity-minus'>-</p>";

                        echo "</div>";
                        echo "<p class='item-price'>" . htmlspecialchars($prixTotal) . "€</p>";
                        if ($quantity > 1) {
                            echo "<p class='item-price-desc'>" . htmlspecialchars($prixDesc) . "</p>";
                        };
                        echo "</div>";

                        echo "<p class='item-id' style='display:none;'>" . htmlspecialchars($produit['id']) . "</p>";
                        echo "</div>";
                        echo "<p class='remove-item'>x</p>";
                        echo "</div><hr>";
                    }
                }
            } else {
                echo "<p style='text-align: center; font-weight: 400;'>Votre panier est vide.</p>";
            }
            ?>
        </div>
        <?php if (isset($prixPanier) && $prixPanier > 0) { ?>
            <div class="end-Panier">
                <?php
                echo "<p>Prix total : " . htmlspecialchars($prixPanier) . "€</p>";
                ?>
                <button class="check-out">Commander !</button>
            </div>
        <?php
        }
        ?>
    </div>

</div>

<script>
    let pan = 0;
    const panierButton = document.getElementById("panier");
    const closePanierButton = document.getElementById("close-panier");
    const escape = document.addEventListener("keydown", function(event) {
        if (event.key === "Escape" && pan === 1) {
            afficherPanier();
        }
    });
    const RemoveItemButtons = document.getElementsByClassName("remove-item");
    const QuantityPlusButtons = document.getElementsByClassName("quantity-plus");
    const QuantityMinusButtons = document.getElementsByClassName("quantity-minus");

    if (panierButton) {
        panierButton.addEventListener("click", afficherPanier);
    }
    if (closePanierButton) {
        closePanierButton.addEventListener("click", afficherPanier);
    }

    if (RemoveItemButtons) {
        Array.from(RemoveItemButtons).forEach(removeItem);
    }

    if (QuantityPlusButtons) {
        Array.from(QuantityPlusButtons).forEach(increaseQuantity);
    }

    if (QuantityMinusButtons) {
        Array.from(QuantityMinusButtons).forEach(decreaseQuantity);
    }

    function removeItem(button) {
        button.addEventListener("click", function() {
            const itemDiv = button.closest(".items");
            const itemId = itemDiv.querySelector(".item-id").textContent;
            window.location.href = "/ppe/back/panierModifier.php?remove=" + itemId;
        });
    }

    function increaseQuantity(button) {
        button.addEventListener("click", function() {
            const itemDiv = button.closest(".items");
            const itemId = itemDiv.querySelector(".item-id").textContent;
            window.location.href = "/ppe/back/panierModifier.php?plus=" + itemId;
        });
    }

    function decreaseQuantity(button) {
        button.addEventListener("click", function() {
            const itemDiv = button.closest(".items");
            const itemId = itemDiv.querySelector(".item-id").textContent;
            window.location.href = "/ppe/back/panierModifier.php?minus=" + itemId;
        });
    }

    function afficherPanier() {
        if (pan == 0) {
            document.querySelector(".panier-cont").style.display = "block";
            document.querySelector(".bg").style.display = "block";
            document.body.style.overflow = "hidden";
            pan = 1;
        } else {
            document.querySelector(".panier-cont").style.display = "none";
            document.querySelector(".bg").style.display = "none";
            document.body.style.overflow = "auto";
            pan = 0;
        }
    }
</script>