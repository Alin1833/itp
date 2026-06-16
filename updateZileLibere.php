<?php
    $host = "localhost";
    $db = "itp";
    $user = "root";
    $pass = "";

   function verificareZi($zi) {
        global $host, $db, $user, $pass;
        $conexiune = mysqli_connect($host, $user, $pass, $db);

        if (!$conexiune) {
            die("Conexiune eșuată: " . mysqli_connect_error());
        }

        $stmt = mysqli_prepare($conexiune, "SELECT zi FROM zilelibere WHERE zi = ? ");
        mysqli_stmt_bind_param($stmt, "s", $zi);
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
        $zi = $_POST['zi'];
        if (verificareZi($zi)) {
            header("Location: indexAdmin.php?zi=1");
            exit();
        } else {
            $conexiune = mysqli_connect($host, $user, $pass, $db);

            if (!$conexiune) {
                die("Conexiune eșuată: " . mysqli_connect_error());
            }

            $stmt = mysqli_prepare($conexiune, "INSERT INTO zilelibere (zi) VALUES (?)");
            mysqli_stmt_bind_param($stmt, "s", $zi);
            mysqli_stmt_execute($stmt);
            mysqli_close($conexiune);
            header("Location: indexAdmin.php?zi=0");
        }
    }
?>