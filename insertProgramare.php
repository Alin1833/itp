<?php
    session_start();
    
    $host = "localhost";
    $db = "itp";
    $user = "root";
    $pass = "";

    if (!isset($_SESSION['iduser'])) {
        header("Location: login&register.php");
        exit();
    }
    
    $conexiune = mysqli_connect($host, $user, $pass, $db);
    if (!$conexiune) {
        die("Conexiune eșuată: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $telefon = $_POST['telefon'];
        $idInspectie = $_POST['idInspectie'];
        $data = $_POST['data'];
        $numarInmatriculare = $_POST['numarInmatriculare'];

        $stmt = mysqli_prepare($conexiune, "SELECT * FROM zilelibere WHERE zi = ?");
        mysqli_stmt_bind_param($stmt, "s", $data);
        mysqli_stmt_execute($stmt);
        $rezultat = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($rezultat) > 0) {
            header("Location: index.php?zi=1");
            exit();
        }

        $stmt = mysqli_prepare($conexiune, "SELECT COUNT(*) as numar_programari FROM programare WHERE dataProgramare = ? AND status = 'programat'");
        mysqli_stmt_bind_param($stmt, "s", $data);
        mysqli_stmt_execute($stmt);
        $rezultat = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($rezultat);

        if ($row['numar_programari'] >= 16) {
            header("Location: index.php?zi=2");
            exit();
        }
        $idProprietar = $_SESSION['idProprietar'];
        $stmt = mysqli_prepare($conexiune, "INSERT INTO programare (idProprietar, idMasina, idInspectie, dataProgramare, nrInmatriculare) 
                                            VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "iiiss", $idProprietar, $idMasina, $idInspectie, $data, $numarInmatriculare);
        mysqli_stmt_execute($stmt);
        header("Location: index.php?programare=1");
        }

    mysqli_close($conexiune);
?> 