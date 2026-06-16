<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Autoreparatur</title>
    <link rel="stylesheet" href="styleIndex.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
 <?php
   if (isset($_GET['zi']) && $_GET['zi'] == 1){
       echo '<script>alert("Zi liberă salvată deja!");</script>';
   }
   if (isset($_GET['inspectie']) && $_GET['inspectie'] == 1) {
       echo '<script>alert("Inspecție existentă!");</script>';
   }
   if (isset($_GET['zi']) && $_GET['zi'] == 0) {
       echo '<script>alert("Zi liberă salvată!");</script>';
   }
   if (isset($_GET['inspectie']) && $_GET['inspectie'] == 0) {
       echo '<script>alert("Inspecție salvată cu succes!");</script>';
   }
   if (isset($_GET['delete'])) {
       if ($_GET['delete'] == 1) {
           echo '<script>alert("Inspecție ștearsă cu succes!");</script>';
       } else if ($_GET['delete'] == 2) {
           echo '<script>alert("Nu se poate șterge inspecția deoarece există programări asociate!");</script>';
       } 
   }
   if (isset($_GET['update']) && $_GET['update'] == 1) {
        echo '<script>alert("Preț actualizat cu succes!");</script>';
   }
   ?>
<body>
    

    <header>
        <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
            <h1>Admin - ClickITP</h1>
            <nav>
                <ul>
                    <li><a href="#preturi">Schimbare Prețuri</a></li>
                    <li><a href="#inspectii">Adăugare Inspecție</a></li>
                    <li><a href="#zile-libere">Zile Libere</a></li>
                    <li><a href="#programari">Programări</a></li>
                    <li><a href="logout.php">Logout</a></li>
                    <li><label class="switch" title="Mod test">
                    <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
                    <span class="slider"></span>
                    </label></li>
                </ul>
            </nav>
        </div>
    </header>

    <section id="preturi" class="section">
        <div class="container">
            <h3>Lista Inspecții</h3>
            <table>
                <thead>
                    <tr>
                        <th>Denumire Inspecție</th>
                        <th>Durata</th>
                        <th>Specificații</th>
                        <th>Preț (lei)</th>
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

                    $sql = "SELECT i.idInspectie, i.denumireInspectie, i.durata, p.specificatii, p.pret, p.idPret 
                            FROM inspectii i
                            JOIN listapreturi p ON i.idPret = p.idPret";
                    $stmt = mysqli_prepare($conexiune, $sql);
                    mysqli_stmt_execute($stmt);
                    $rezultat = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($rezultat) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($rezultat)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row["denumireInspectie"]) ?></td>
                                <td><?= htmlspecialchars($row["durata"]) ?></td>
                                <td><?= htmlspecialchars($row["specificatii"]) ?></td>
                                <td><?= htmlspecialchars(number_format($row["pret"], 2)) ?> lei</td>
                                <td class="action-buttons">
                                    <form action="updatePret.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="idPret" value="<?= $row["idPret"] ?>">
                                        <input type="number" name="pret" value="<?= $row["pret"] ?>" step="0.1" class="pret-input">
                                        <button type="submit" class="btn-modifica">Modifică</button>
                                    </form>
                                    <form action="deleteInspectie.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="idInspectie" value="<?= $row["idInspectie"] ?>">
                                        <input type="hidden" name="idPret" value="<?= $row["idPret"] ?>">
                                        <button type="submit" class="btn-sterge" onclick="return confirm('Sigur doriți să ștergeți această inspecție?')">Șterge</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">Nu există inspecții înregistrate</td></tr>
                    <?php endif; ?>

                    <?php mysqli_close($conexiune); ?>

                </tbody>
            </table>
        </div>
    </section>

    <section id="inspectii" class="section">
        <div class="container">
            <h3>Adaugă Inspecție Nouă</h3>
            <form action="insertInspectie.php" method="POST">
                <label for="denumire">Denumire Inspecție:</label>
                <input type="text" name="denumire" required>

                <label>Durata acțiunii:</label><br>
                <div class="durata-container">
                    <input type="number" name="ore" min="0" max="99" placeholder="HH" required>
                    <span>:</span>
                    <input type="number" name="minute" min="0" max="59" placeholder="MM" required>
                    <span>:</span>
                    <input type="number" name="secunde" min="0" max="59" placeholder="SS" required>
                </div>

                <label for="specificatii">Specificații:</label>
                <input type="text" name="specificatii" required>

                <label for="pret">Preț (lei):</label>
                <input type="number" name="pret" step="0.1" required>

                <button type="submit">Adaugă Inspecție</button>
            </form>
        </div>
    </section>

    

    

    <section id="zile-libere" class="section">
        <div class="container">
            <h3>Selectare Zi Liberă</h3>
            <form action="updateZileLibere.php" method="POST">
                <label for="zi">Alege o zi liberă:</label>
                <input type="date" id="zi" name="zi" required>
                <button type="submit">Salvează Ziua Liberă</button>
            </form>
        </div>
    </section>


    <section id="programari" class="section">
        <div class="container">
            <h3>Inspectii Viitoare</h3>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Data Programare</th>
                            <th>Nume</th>
                            <th>Prenume</th>
                            <th>Telefon</th>
                            <th>Marcă Mașină</th>
                            <th>Serie Șasiu</th>
                            <th>Număr Înmatriculare</th>
                            <th>Tip Inspecție</th>
                            <th>Status</th>
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

                        $sql = "SELECT p.*, pr.nume, pr.prenume, pr.telefon, m.marcaMasina, m.serieSasiu, i.denumireInspectie 
                                FROM programare p
                                JOIN proprietar pr ON pr.idProprietar = p.idProprietar
                                JOIN masina m ON p.idMasina = m.idMasina
                                JOIN inspectii i ON p.idInspectie = i.idInspectie
                                WHERE p.dataProgramare >= CURRENT_DATE
                                ORDER BY p.dataProgramare , pr.nume , pr.prenume";
                        
                        $stmt = mysqli_prepare($conexiune, $sql);
                        mysqli_stmt_execute($stmt);
                        $rezultat = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($rezultat) > 0) {
                            while($row = mysqli_fetch_assoc($rezultat)) {
                                echo "<tr>";
                                echo "<td>" . date('d.m.Y', strtotime($row['dataProgramare'])) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nume']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['prenume']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['telefon']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['marcaMasina']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['serieSasiu']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['nrInmatriculare']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['denumireInspectie']) . "</td>";
                                echo "<td>" . ucfirst($row['status']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9'>Nu există programări viitoare</td></tr>";
                        }

                        mysqli_close($conexiune);
                        ?>
                    </tbody>
                </table>
            </div>
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