
<?php
    session_start();

    if (!isset($_SESSION['iduser'])) {
        header("Location: index.php");
        exit();
    }
    if (isset($_GET['date']) && $_GET['date'] == 0) {
        echo '<script>alert("Datele au fost actualizate cu succes!");</script>';
    }
    if (isset($_GET['date']) && $_GET['date'] == 1) {
        echo '<script>alert("Numărul de telefon trebuie să conțină 10 cifre!");</script>';
    }
    if (isset($_GET['parola']) && $_GET['parola'] == 0) {
        echo '<script>alert("Parola a fost schimbată!");</script>';
    }
    if (isset($_GET['parola']) && $_GET['parola'] == 1) {
        echo '<script>alert("Parolele nu coincid!");</script>';
    }
    if (isset($_GET['parola']) && $_GET['parola'] == 2) {
        echo '<script>alert("Parola veche este greșită!");</script>';
    }
    if (isset($_GET['masina']) && $_GET['masina'] == 0) {
        echo '<script>alert("Mașina a fost adăugată!");</script>';
    }
    if (isset($_GET['masina']) && $_GET['masina'] == 1) {
        echo '<script>alert("Mașina a fost modificată!");</script>';
    }
    if (isset($_GET['masina']) && $_GET['masina'] == 2) {
        echo '<script>alert("Mașina a fost ștearsă!");</script>';
    }
    if (isset($_GET['programare']) && $_GET['programare'] == 1) {
        echo '<script>alert("Programarea a fost anulată cu succes!");</script>';
    }
    
    
    $email = $_SESSION['email'];
    $iduser = $_SESSION['iduser'];
    $nume = $_SESSION['nume'];
    $prenume = $_SESSION['prenume'];
    $telefon = $_SESSION['telefon'];
    $idProprietar = $_SESSION['idProprietar'];
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contul Meu</title>
    <link rel="stylesheet" href="styleIndex.css">
</head>
<body>
    <header>
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Contul Meu</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Acasă</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><label class="switch" title="Mod întunecat">
                        <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
                        <span class="slider"></span>
                    </label></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <h3>Mașinile Mele</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Serie Șasiu</th>
                            <th>Marcă</th>
                            <th>Categorie</th>
                            <th>An Apariție</th>
                            <th>RCA</th>
                            <th>Perioada ITP</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $host = "localhost";
                        $db = "itp";
                        $user = "root";
                        $pass = "";
                        
                        $conexiune = mysqli_connect($host, $user, $pass, $db);
                        if (!$conexiune) {
                            die("Conexiune eșuată: " . mysqli_connect_error());
                        }

                        $sql = "SELECT m.*, 
                                CASE 
                                    WHEN (YEAR(CURRENT_DATE) - m.anAparitie) <= 10 THEN 2
                                    ELSE 1
                                END as aniITP
                                FROM masina m
                                JOIN masinaproprietar mp ON m.idMasina = mp.idMasina
                                WHERE mp.idProprietar = ?";
                        
                        $stmt = mysqli_prepare($conexiune, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $idProprietar);
                        mysqli_stmt_execute($stmt);
                        $rezultat = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($rezultat) > 0) {
                            while($row = mysqli_fetch_assoc($rezultat)) {
                                $rcaStatus = $row['RCA'] ? 'Da' : 'Nu';
                                $itpPeriod = $row['aniITP'] . ' ani';
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['serieSasiu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['marcaMasina']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['categorie']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['anAparitie']) . "</td>";
                                echo "<td>" . $rcaStatus . "</td>";
                                echo "<td>" . $itpPeriod . "</td>";
                                echo "<td>"?>
                                        <button class='btn-modifica' onclick="window.location.href='updateMasina.php?id=<?=$row['idMasina']?>'">Modifică</button>
                                        <button class='btn-sterge' onclick="window.location.href='deleteMasina.php?id=<?=$row['idMasina']?>'">Șterge</button>
                                    <?php   "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Nu aveți mașini înregistrate</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <button class="btn-submit" style="margin-top: 20px;" onclick="window.location.href='insertMasina.php'">Adaugă Mașină Nouă</button>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h3>Programări Următoare</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Data Programare</th>
                            <th>Marcă Mașină</th>
                            <th>Serie Șasiu</th>
                            <th>Număr Înmatriculare</th>
                            <th>Tip Inspecție</th>
                            <th>Status</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.*, m.marcaMasina, m.serieSasiu, i.denumireInspectie 
                                FROM programare p
                                JOIN masina m ON p.idMasina = m.idMasina
                                JOIN inspectii i ON p.idInspectie = i.idInspectie
                                WHERE p.idProprietar = ? AND p.dataProgramare >= CURRENT_DATE
                                ORDER BY p.dataProgramare ASC";
                        
                        $stmt = mysqli_prepare($conexiune, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $idProprietar);
                        mysqli_stmt_execute($stmt);
                        $rezultatProgramari = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($rezultatProgramari) > 0) {
                            while($row = mysqli_fetch_assoc($rezultatProgramari)) {
                                if($row['status'] == null) {
                                    $status = 'În așteptare';
                                } 
                                echo "<tr>";
                                echo "<td>" . date('d.m.Y', strtotime($row['dataProgramare'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['marcaMasina']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['serieSasiu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nrInmatriculare']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['denumireInspectie']) . "</td>";
                                echo "<td>" . $status . "</td>";
                                echo "<td>"?>

                                        <button class='btn-sterge' onclick="window.location.href='deleteProgramare.php?id=<?=$row['idMasina']?>'">Anulează</button>
                                      <?php "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7'>Nu aveți programări active</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h3>Actualizează Informațiile</h3>
            <form action="updateCont.php" method="POST">
                <label for="nume"> Nume:</label>
                <input type="text" id="nume" name="nume" value="<?php echo htmlspecialchars($nume); ?>" required>

                <label for="prenume">Prenume:</label>
                <input type="text" id="prenume" name="prenume" value="<?php echo htmlspecialchars($prenume); ?>" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

                <label for="telefon">Telefon:</label>
                <input type="text" id="telefon" name="telefon" value="<?php echo htmlspecialchars($telefon); ?>" required>

                <button type="submit">Salvează Modificările</button>
            </form>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <h3>Schimbă Parola</h3>
            <form action="resetareParola.php" method="POST">
                <label for="parola_veche">Parola Veche:</label>
                <input type="password" id="parola_veche" name="parolaVeche" required>

                <label for="parola_noua">Parola Nouă:</label>
                <input type="password" id="parola_noua" name="parolaNoua" required>

                <label for="confirma_parola">Confirmă Parola Nouă:</label>
                <input type="password" id="confirma_parola" name="parolaNoua1" required>

                <button type="submit">Schimbă Parola</button>
            </form>
        </div>
    </section>

    <footer>
        <div class="container">
            <p>&copy; 2025 ClickITP. Toate drepturile rezervate.</p>
        </div>
    </footer>


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
    <?php
        mysqli_close($conexiune);
    ?>
</body>
</html>