<?php
    $host = "localhost";
    $db = "itp";
    $user = "root";
    $pass = "";

    function verificareEmail($email) {
        global $host, $db, $user, $pass;
        $conexiune = mysqli_connect($host, $user, $pass, $db);

        if (!$conexiune) {
            die("Conexiune eșuată: " . mysqli_connect_error());
        }

        $stmt = mysqli_prepare($conexiune, "SELECT iduser FROM user WHERE email = ? ");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $rezultat = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($rezultat) > 0) {
            mysqli_close($conexiune);
            return true;
        } else {
            mysqli_close($conexiune);
            return false;
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $parola = $_POST['parola'];
        $nume = $_POST['nume'];
        $prenume = $_POST['prenume'];
        $telefon = $_POST['telefon'];
        $parola2 = $_POST['parola2'];

        if (verificareEmail($email)) {
            header("Location: login&register.php?eroare=1");
            exit();
        } else {
            if($parola != $parola2) {
                header("Location: login&register.php?eroare=4");
                exit();
            }
            if(strlen($telefon) != 10) {
                header("Location: login&register.php?eroare=3");
                exit();
            }
            $conexiune = mysqli_connect($host, $user, $pass, $db);

            if (!$conexiune) {
                die("Conexiune eșuată: " . mysqli_connect_error());
            }

            $parola_criptata = password_hash($parola, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conexiune, "INSERT INTO user (rol, email, parola) VALUES (?, ?, ?)");
            $rol = "client";
            mysqli_stmt_bind_param($stmt, "sss", $rol, $email, $parola_criptata);
            mysqli_stmt_execute($stmt);
            $iduser = mysqli_insert_id($conexiune);
            $stmt = mysqli_prepare($conexiune, "INSERT INTO proprietar (idUser, nume, prenume, telefon) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "isss", $iduser, $nume, $prenume, $telefon);
            mysqli_stmt_execute($stmt);
            mysqli_close($conexiune);
            header("Location: login&register.php?eroare=0");
        }
    }
    
    ?>