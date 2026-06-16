<?php
    session_start();
    $host = "localhost";
    $db = "itp";
    $user = "root";
    $pass = "";


    function verificareDate($nume, $prenume, $telefon, $email) {
        if($nume==$_SESSION['nume'] && $prenume==$_SESSION['prenume'] && $telefon==$_SESSION['telefon'] && $email==$_SESSION['email']) {
            return true;
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $telefon = $_POST['telefon'];
        $iduser = $_SESSION['iduser'];

        if (verificareDate($nume, $prenume, $telefon, $email)) {
            header("Location: cont.php");
            exit();
        } else {
            if(strlen($telefon) != 10) {
                header("Location: cont.php?date=1");
                exit();
            }
            $conexiune = mysqli_connect($host, $user, $pass, $db);

            if (!$conexiune) {
                die("Conexiune eșuată: " . mysqli_connect_error());
            }

            $stmt = mysqli_prepare($conexiune, "UPDATE user SET email = ? WHERE idUser = ?");
            mysqli_stmt_bind_param($stmt, "si", $email, $iduser);
            mysqli_stmt_execute($stmt);
            $stmt = mysqli_prepare($conexiune, "UPDATE proprietar SET nume = ?, prenume = ?, telefon = ? WHERE idUser = ?");
            mysqli_stmt_bind_param($stmt, "sssi", $nume, $prenume, $telefon, $iduser);
            mysqli_stmt_execute($stmt);
            $_SESSION['nume'] = $nume;
            $_SESSION['prenume'] = $prenume;
            $_SESSION['telefon'] = $telefon;
            $_SESSION['email'] = $email;
            mysqli_close($conexiune);
            header("Location: cont.php?date=0");
        }
    }
?>