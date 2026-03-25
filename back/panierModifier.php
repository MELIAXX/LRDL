<?php
    session_start();
    
    if (isset($_GET['p_id'])) {
        $produit = $_GET['p_id'];
        if (isset($_SESSION['panier'][$produit])) {
            $_SESSION['panier'][$produit] += 1;
        } else {
            $_SESSION['panier'][$produit] = 1;
        }
        header("Location: /ppe/index.php?success=1");
        exit;
        
    }

    if (isset($_GET['remove'])) {
        $idToRemove = $_GET['remove'];
        if (isset($_SESSION['panier'][$idToRemove])) {
            unset($_SESSION['panier'][$idToRemove]);
            echo "<script>window.location.href = '/ppe/index.php?success=1';</script>";
            exit;
        }
    }

    if (isset($_GET['minus'])) {
        $idToMinus = $_GET['minus'];
        if (isset($_SESSION['panier'][$idToMinus])) {
            $_SESSION['panier'][$idToMinus]--;
            if ($_SESSION['panier'][$idToMinus] <= 0) {
                unset($_SESSION['panier'][$idToMinus]);
            }
            echo "<script>window.location.href = '/ppe/index.php?success=1';</script>";
            exit;
        }

    }

    if (isset($_GET['plus'])) {
        $idToPlus = $_GET['plus'];
        if (isset($_SESSION['panier'][$idToPlus])) {
            $_SESSION['panier'][$idToPlus]++;
            echo "<script>window.location.href = '/ppe/index.php?success=1';</script>";
            exit;
        }

    }
?>