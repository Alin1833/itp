<?php
$host = "localhost";
$db = "itp";
$user = "root";
$pass = "";

if (isset($_POST['idInspectie']) && isset($_POST['idPret'])) {
    $idInspectie = $_POST['idInspectie'];
    $idPret = $_POST['idPret'];

    $conexiune = mysqli_connect($host, $user, $pass, $db);
    if (!$conexiune) {
        die("Conexiune eșuată: " . mysqli_connect_error());
    }

    $stmt_check = mysqli_prepare($conexiune, "SELECT COUNT(*) as count FROM programare WHERE idInspectie = ?");
    mysqli_stmt_bind_param($stmt_check, "i", $idInspectie);
    mysqli_stmt_execute($stmt_check);
    $result = mysqli_stmt_get_result($stmt_check);
    $row = mysqli_fetch_assoc($result);

    if ($row['count'] > 0) {
        header("Location: indexAdmin.php?delete=2");
    } else {
        $stmt1 = mysqli_prepare($conexiune, "DELETE FROM inspectii WHERE idInspectie = ?");
        mysqli_stmt_bind_param($stmt1, "i", $idInspectie);
        
        if (mysqli_stmt_execute($stmt1)) {
            $stmt2 = mysqli_prepare($conexiune, "DELETE FROM listapreturi WHERE idPret = ?");
            mysqli_stmt_bind_param($stmt2, "i", $idPret);
            mysqli_stmt_execute($stmt2);
            header("Location: indexAdmin.php?delete=1");
        }
    }

    mysqli_close($conexiune);
} else {
    header("Location: indexAdmin.php");
}
?> 