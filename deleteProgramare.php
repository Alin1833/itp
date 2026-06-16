<?php
    session_start();
    
    if (!isset($_SESSION['iduser'])) {
        header("Location: login&register.php");
        exit();
    }

    $host = "localhost";
    $db = "itp";
    $user = "root";
    $pass = "";
    
    $conexiune = mysqli_connect($host, $user, $pass, $db);
    if (!$conexiune) {
        die("Conexiune eșuată: " . mysqli_connect_error());
    }

    if (isset($_GET['id'])) {
        $idMasina = $_GET['id'];
        $idProprietar = $_SESSION['idProprietar'];
        $stmt = mysqli_prepare($conexiune, "DELETE FROM programare WHERE idProprietar = ? AND idMasina = ?");
        mysqli_stmt_bind_param($stmt, "ii", $idProprietar, $idMasina);
        
        if (mysqli_stmt_execute($stmt)) {
            header("Location: cont.php?programare=1");
        }
    } else {
        header("Location: cont.php");
    }

    mysqli_close($conexiune);
?> 