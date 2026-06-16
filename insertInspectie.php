<?php
$host = "localhost";
$db = "itp";
$user = "root";
$pass = "";

function verificareDenumireInspectie($denumire) {
    global $host, $db, $user, $pass;
    $conexiune = mysqli_connect($host, $user, $pass, $db);

    if (!$conexiune) {
        die("Conexiune eșuată: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($conexiune, "SELECT idInspectie FROM inspectii WHERE denumireInspectie = ?");
    mysqli_stmt_bind_param($stmt, "s", $denumire);
    mysqli_stmt_execute($stmt);
    $rezultat = mysqli_stmt_get_result($stmt);

    $exista = mysqli_num_rows($rezultat) > 0;
    mysqli_close($conexiune);
    return $exista;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $denumire = $_POST["denumire"];
    $ore = str_pad($_POST["ore"], 2, "0", STR_PAD_LEFT);
    $minute = str_pad($_POST["minute"], 2, "0", STR_PAD_LEFT);
    $secunde = str_pad($_POST["secunde"], 2, "0", STR_PAD_LEFT);
    $durata = implode(":", [$ore, $minute, $secunde]);
    $specificatii = $_POST["specificatii"];
    $pret = $_POST["pret"];

    if (verificareDenumireInspectie($denumire)) {
        header("Location: indexAdmin.php?inspectie=1");
        exit();
    } else {
        $conexiune = mysqli_connect($host, $user, $pass, $db);

        if (!$conexiune) {
            die("Conexiune eșuată: " . mysqli_connect_error());
        }

        $stmt1 = mysqli_prepare($conexiune, "INSERT INTO listapreturi (specificatii, pret, dataStabilirePret) VALUES (?, ?, CURDATE())");
        mysqli_stmt_bind_param($stmt1, "sd", $specificatii, $pret);
        mysqli_stmt_execute($stmt1);
        $idPretNou = mysqli_insert_id($conexiune);

        $stmt2 = mysqli_prepare($conexiune, "INSERT INTO inspectii (denumireInspectie, idPret, durata) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt2, "sis", $denumire, $idPretNou, $durata);
        mysqli_stmt_execute($stmt2);

        mysqli_close($conexiune);
        header("Location: indexAdmin.php?inspectie=0");
        exit();
    }
}
?>
