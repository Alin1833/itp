<?php
    session_start();
    $host = "localhost";
    $db = "itp";
    $user = "root";
    $pass = "";

    function verificareParola($email, $parola) {
        global $host, $db, $user, $pass;
        $conexiune = mysqli_connect($host, $user, $pass, $db);

        if (!$conexiune) {
            die("Conexiune eșuată: " . mysqli_connect_error());
        }

        $stmt = mysqli_prepare($conexiune, "SELECT parola FROM user WHERE email = ? ");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $rezultat = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($rezultat)) {
            if (password_verify($parola, $row['parola'])) {
                mysqli_close($conexiune);
                return true;
            }
        }

        mysqli_close($conexiune);
        return false;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
       $iduser = $_SESSION['iduser'];
        $email = $_SESSION['email'];
        $parola = $_POST['parolaVeche'];
        $parolaNoua = $_POST['parolaNoua'];
        $parolaNoua1 = $_POST['parolaNoua1'];

        if (verificareParola($email, $parola)) {
            if ($parolaNoua === $parolaNoua1) {
                    $parolaHash = password_hash($parolaNoua, PASSWORD_DEFAULT);
                    
                    $conexiune = mysqli_connect($host, $user, $pass, $db);
                    if (!$conexiune) {
                        die("Conexiune eșuată: " . mysqli_connect_error());
                    }

                    $stmt = mysqli_prepare($conexiune, "UPDATE user SET parola = ? WHERE idUser = ?");
                    mysqli_stmt_bind_param($stmt, "si", $parolaHash, $iduser);
                    mysqli_stmt_execute($stmt);
                    mysqli_close($conexiune);
                    header("Location: cont.php?parola=0"); 
                    exit();
            } else {
                header("Location: cont.php?parola=1"); 
                exit();
            }
        } else {
            header("Location: cont.php?parola=2"); 
            exit();
        }
    }
?>