<?php
    session_start();
    
    if (isset($_GET['eroare']) && $_GET['eroare'] == 1){
       echo '<script>alert("V-ați delogat cu succes!");</script>';
    }
    if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin') {
        header("Location: indexAdmin.php");
        exit();
    }

    if(isset($_GET['zi'])) {
        if ($_GET['zi'] == 1) {
            echo '<script>alert("Nu se poate face programare în această zi!");</script>';
        } else if ($_GET['zi'] == 2) {
            echo '<script>alert("Nu mai sunt locuri disponibile în această zi!");</script>';
        }
    }
    if(isset($_GET['programare']) && $_GET['programare'] == 1) {
        echo '<script>alert("Programarea a fost efectuată cu succes!");</script>';
    }

    $host = "localhost";
    $db = "itp";
    $user = "root";
    $pass = "";

    $conexiune = mysqli_connect($host, $user, $pass, $db);
    if (!$conexiune) {
        die("Conexiune eșuată: " . mysqli_connect_error());
    }

    if(isset($_SESSION['iduser'])) {
        $nume=$_SESSION['nume'];
        $prenume=$_SESSION['prenume'];
        $telefon=$_SESSION['telefon'];
        $email=$_SESSION['email'];
        $iduser=$_SESSION['iduser'];
        $idProprietar=$_SESSION['idProprietar'];
    }


?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programare ITP - Autoreparatur</title>
    <link rel="stylesheet" href="styleIndex.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
    <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
        <h1>ClickITP</h1>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <nav>
                <ul>
                    <?php if (isset($_SESSION['iduser'])): ?>
                        <li><a href="cont.php">Contul Meu</a></li>
                    <?php else: ?>
                        <li><a href="login&register.php">Login/Register</a></li>
                    <?php endif; ?>
                    <li><a href="#servicii">Servicii</a></li>
                    <li><a href="#preturi">Preturi</a></li>
                    <li><a href="#programare">Programare</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
            <label class="switch" title="Mod test">
                <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
                <span class="slider"></span>
            </label>
        </div>
    </div>
</header>

    <section class="hero">
        <div class="container">
            <?php if (isset($_SESSION['nume']) && isset($_SESSION['prenume'])): ?>
                <h2>Bun venit, <?php echo htmlspecialchars($_SESSION['nume'] . ' ' . $_SESSION['prenume']); ?>!</h2>
            <?php endif; ?>
            <h2>Fă-ți programare ITP rapid și ușor</h2>
            <p>Verificare tehnică profesională în doar câțiva pași.</p>
            <a href="#programare" class="btn">Programează-te acum</a>
        </div>
    </section>

    <section id="servicii" class="section">
        <div class="container">
            <h3>Serviciile noastre</h3>
            <ul class="servicii-lista">
                <li>ITP pentru autoturisme</li>
                <li>ITP pentru autoutilitare</li>
                <li>Verificare tahograf</li>
            </ul>
        </div>
    </section>

    <section id="preturi" class="section">
        <div class="container">
            <h3>Lista Prețuri</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Tip Inspecție</th>
                            <th>Specificații</th>
                            <th>Durata</th>
                            <th>Preț (lei)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT i.denumireInspectie, i.durata, p.specificatii, p.pret 
                                FROM inspectii i
                                JOIN listapreturi p ON i.idPret = p.idPret
                                ORDER BY i.denumireInspectie";
                        $rezultat = mysqli_query($conexiune, $sql);

                        if (mysqli_num_rows($rezultat) > 0) {
                            while($row = mysqli_fetch_assoc($rezultat)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['denumireInspectie']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['specificatii']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['durata']) . "</td>";
                                echo "<td>" . number_format($row['pret'], 2) . " lei</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Nu există prețuri disponibile</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <section id="programare" class="section">
        <div class="container">
            <h3>Formular programare</h3>
            <div class="programare">
                <form action="insertProgramare.php" method="post">
                    <?php if (isset($_SESSION['iduser'])) { ?>
                        <input type="text" name="nume" value="<?php echo $nume; ?>" required>
                        <input type="text" name="prenume" value="<?php echo $prenume; ?>" required>
                        <input type="tel" name="telefon" value="<?php echo $telefon; ?>" required>
                    <?php } else { ?>
                        <input type="text" name="nume" placeholder="Nume" required>
                        <input type="text" name="prenume" placeholder="Prenume" required>
                        <input type="tel" name="telefon" placeholder="Telefon" required>
                    <?php } ?>

                    <select name="idInspectie" id="idInspectie" required>
                        <option value="">Selectează tipul de inspecție</option>
                        <?php
                        $stmt = mysqli_prepare($conexiune, "SELECT idInspectie, denumireInspectie, durata FROM inspectii");
                        mysqli_stmt_execute($stmt);
                        $rezultat = mysqli_stmt_get_result($stmt);
                        while ($row = mysqli_fetch_assoc($rezultat)) {
                            echo "<option value='" . $row['idInspectie'] . "' data-durata='" . $row['durata'] . "'>" . 
                                 $row['denumireInspectie'] . " (" . $row['durata'] . " min)</option>";
                        }
                        ?>
                    </select>

                    <?php if (isset($_SESSION['email'])) { ?>
                        <select name="idMasina" id="idMasina" required>
                            <option value="">Selectează mașina</option>
                            <?php
                            $stmt = mysqli_prepare($conexiune, "SELECT m.idMasina, m.serieSasiu, m.marcaMasina, m.categorie 
                                                       FROM masina m 
                                                       JOIN masinaproprietar mp ON m.idMasina = mp.idMasina 
                                                       WHERE mp.idProprietar = ? AND m.idMasina NOT IN (
                                                       SELECT idMasina FROM programare WHERE status IS NULL)");
                            mysqli_stmt_bind_param($stmt, "i", $idProprietar);
                            mysqli_stmt_execute($stmt);
                            $rezultat = mysqli_stmt_get_result($stmt);
                            while ($row = mysqli_fetch_assoc($rezultat)) {
                                echo "<option value='" . $row['idMasina'] . "'>" . 
                                     $row['marcaMasina'] . " - " . $row['serieSasiu'] . " (" . $row['categorie'] . ")</option>";
                            }
                            ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" name="serieSasiu" placeholder="Serie Șasiu" required>
                        <input type="text" name="marcaMasina" placeholder="Marcă Mașină" required>
                        <select name="categorie" required>
                            <option value="">Selectează categoria</option>
                            <option value="M1">M1</option>
                            <option value="M2">M2</option>
                            <option value="M3">M3</option>
                            <option value="N1">N1</option>
                            <option value="N2">N2</option>
                            <option value="N3">N3</option>
                        </select>
                    <?php } ?>

                    <input type="text" name="numarInmatriculare" placeholder="Număr Înmatriculare" required>
                    <input type="date" name="data" id="data" required min="<?php echo date('Y-m-d'); ?>">

                    <button type="submit">Programează</button>
                </form>
            </div>
        </div>
    </section>

    <section id="contact" class="section">
        <div class="container">
            <h3>Contact</h3>
            <p>Adresă: Strada Exemplu nr. 123, Oraș</p>
            <p>Email: contact@clickitp.ro</p>
            <p>Telefon: 0712345678</p>
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
</body>
</html>