<?php
    session_start();
    // Clear any existing session when accessing the login endpoint
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
    // Start a fresh session and regenerate the ID to prevent fixation
    session_start();
    session_regenerate_id(true);
    $host = "localhost";
    $db = "itp";
    $user = "root";
    $pass = "";

    function verificareLogin($email, $parola) {
        global $host, $db, $user, $pass;
        $conexiune = mysqli_connect($host, $user, $pass, $db);

        if (!$conexiune) {
            die("Conexiune eșuată: " . mysqli_connect_error());
        }

        $stmt = mysqli_prepare($conexiune, "SELECT idProprietar, idUser, nume, prenume, telefon, parola,
        rol FROM user LEFT JOIN proprietar USING(idUser) WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $rezultat = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($rezultat)) {
            if (password_verify($parola, $row['parola'])) {
                $_SESSION['nume'] = $row['nume'];
                $_SESSION['prenume'] = $row['prenume'];
                $_SESSION['telefon'] = $row['telefon'];
                $_SESSION['iduser'] = $row['idUser'];
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['idProprietar'] = $row['idProprietar'];
                $_SESSION['email'] = $email;
                mysqli_close($conexiune);
                return true;
            }
        }

        mysqli_close($conexiune);
        return false;
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $parola = $_POST['parola'];

        if (verificareLogin($email, $parola)) {
            
            header("Location: index.php");
            exit();
        } else {
            header("Location: login&register.php?eroare=2");
            exit();
        }
    }
    
    ?>