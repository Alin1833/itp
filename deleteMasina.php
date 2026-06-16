<?php
    session_start();
    
    if (!isset($_SESSION['iduser'])) {
        header("Location: index.php");
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

    $idMasina = isset($_GET['id']) ? $_GET['id'] : 0;

    $stmt = mysqli_prepare($conexiune, "DELETE FROM masinaproprietar WHERE idMasina = ?");
    mysqli_stmt_bind_param($stmt, "i", $idMasina);
    mysqli_stmt_execute($stmt);
    header("Location: cont.php?masina=2");
?> 