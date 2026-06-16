<?php
    session_start();
    
    if (!isset($_SESSION['iduser'])) {
        header("Location: index.php");
        exit();
    }
    if (isset($_GET['eroare']) && $_GET['eroare'] == 1) {
        echo '<script>alert("Seria de șasiu există deja la altă mașină!");</script>';
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
    $idProprietar = $_SESSION['idProprietar'];

    $stmt = mysqli_prepare($conexiune, "SELECT m.* FROM masina m 
                                      JOIN masinaproprietar mp ON m.idMasina = mp.idMasina 
                                      WHERE m.idMasina = ? AND mp.idProprietar = ?");
    mysqli_stmt_bind_param($stmt, "ii", $idMasina, $idProprietar);
    mysqli_stmt_execute($stmt);
    $rezultat = mysqli_stmt_get_result($stmt);

    $masina = mysqli_fetch_assoc($rezultat);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $serieSasiu = $_POST['serieSasiu'];
        $marcaMasina = $_POST['marcaMasina'];
        $categorie = $_POST['categorie'];
        $rca = isset($_POST['rca']) ? 1 : 0;
        $anAparitie = $_POST['anAparitie'];

        if($serieSasiu == $masina['serieSasiu'] && $marcaMasina == $masina['marcaMasina'] && $categorie == $masina['categorie'] && $rca == $masina['RCA'] && $anAparitie == $masina['anAparitie']) {
            header("Location: cont.php");
            exit();
        }

        $stmt = mysqli_prepare($conexiune, "SELECT idMasina FROM masina WHERE serieSasiu = ? AND idMasina != ?");
        mysqli_stmt_bind_param($stmt, "si", $serieSasiu, $idMasina);
        mysqli_stmt_execute($stmt);
        $rezultat = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($rezultat) > 0) {
            header("Location: updateMasina.php?eroare=1"); 
            exit();
        }

        $stmt = mysqli_prepare($conexiune, "UPDATE masina SET serieSasiu = ?, marcaMasina = ?, categorie = ?, RCA = ?, anAparitie = ? WHERE idMasina = ?");
        mysqli_stmt_bind_param($stmt, "sssiii", $serieSasiu, $marcaMasina, $categorie, $rca, $anAparitie, $idMasina);
        mysqli_stmt_execute($stmt);
        header("Location: cont.php?masina=1"); 
    }
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifică Mașină</title>
    <link rel="stylesheet" href="styleIndex.css">
</head>
<body>
    <header>
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Modifică Mașină</h1>
            <nav>
                <ul>
                    <li><a href="cont.php">Înapoi la Cont</a></li>
                    <li><label class="switch" title="Mod Test">
                        <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
                        <span class="slider"></span>
                    </label></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <form action="updateMasina.php?id=<?= $idMasina; ?>" method="POST" class="form-programare">
                <div class="form-columns">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="serieSasiu">Serie Șasiu:</label>
                            <input type="text" id="serieSasiu" name="serieSasiu" value="<?php echo htmlspecialchars($masina['serieSasiu']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="marcaMasina">Marcă Mașină:</label>
                            <input type="text" id="marcaMasina" name="marcaMasina" value="<?php echo htmlspecialchars($masina['marcaMasina']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="categorie">Categorie:</label>
                            <select id="categorie" name="categorie" required>
                                <option value="">Selectează categoria</option>
                                <option value="M1" <?php echo $masina['categorie'] == 'M1' ? 'selected' : ''; ?>>M1 - Autoturisme</option>
                                <option value="M2" <?php echo $masina['categorie'] == 'M2' ? 'selected' : ''; ?>>M2 - Microbuze</option>
                                <option value="M3" <?php echo $masina['categorie'] == 'M3' ? 'selected' : ''; ?>>M3 - Autobuze</option>
                                <option value="N1" <?php echo $masina['categorie'] == 'N1' ? 'selected' : ''; ?>>N1 - Autoutilitare</option>
                                <option value="N2" <?php echo $masina['categorie'] == 'N2' ? 'selected' : ''; ?>>N2 - Camioane medii</option>
                                <option value="N3" <?php echo $masina['categorie'] == 'N3' ? 'selected' : ''; ?>>N3 - Camioane grele</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-group">
                            <label for="anAparitie">An Apariție:</label>
                            <input type="number" id="anAparitie" name="anAparitie" min="1900" max="<?php echo date('Y'); ?>" value="<?php echo $masina['anAparitie']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Asigurare RCA:</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="rca" value="1" <?php echo $masina['RCA'] ? 'checked' : ''; ?> required> Da
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="rca" value="0" <?php echo !$masina['RCA'] ? 'checked' : ''; ?> required> Nu
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Salvează Modificările</button>
            </form>
        </div>
    </section>

    <script>
        function toggleDarkMode() {
            const body = document.body;
            const toggle = document.getElementById('darkModeToggle');
            if (toggle.checked) {
                body.classList.add('dark-mode');
            } else {
                body.classList.remove('dark-mode');
            }
        }
    </script>
</body>
</html>
<?php
mysqli_close($conexiune);
?> 