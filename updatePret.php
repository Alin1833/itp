<?php
$host = "localhost";
$db = "itp";
$user = "root";
$pass = "";

if (isset($_POST['idPret']) && isset($_POST['pret'])) {
    $idPret = $_POST['idPret'];
    $pret = $_POST['pret'];

    $conexiune = mysqli_connect($host, $user, $pass, $db);
    if (!$conexiune) {
        die("Conexiune eșuată: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($conexiune, "UPDATE listapreturi SET pret = ? WHERE idPret = ?");
    mysqli_stmt_bind_param($stmt, "di", $pret, $idPret);
    mysqli_stmt_execute($stmt);
    header("Location: indexAdmin.php?update=1");
    mysqli_close($conexiune);
} else {
    header("Location: indexAdmin.php");
}
?> 