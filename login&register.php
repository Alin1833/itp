<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register - Autoreparatur</title>
    <link rel="stylesheet" href="styleLogin.css">
</head>
<body>
    <label class="switch" title="Mod test">
        <input type="checkbox" id="darkModeToggle" onchange="toggleDarkMode()">
        <span class="slider"></span>
    </label>

    <div class="wrapper">
        <div class="mode-toggle">
            <span class="mode-label">Login</span>
            <label class="switch">
                <input type="checkbox" class="toggle" id="modeToggle" onchange="toggleMode()">
                <span class="slider"></span>
            </label>
            <span class="mode-label">Sign Up</span>
        </div>

        <div class="card-switch">
            <div class="flip-card__inner">
                <div class="flip-card__front">
                    <div class="title">Log in</div>
                    <form class="flip-card__form" action="login.php" method="POST">
                        <input class="flip-card__input" name="email" placeholder="Email" type="email">
                        <input class="flip-card__input" name="parola" placeholder="Parola" type="password">
                        <button class="flip-card__btn" type="submit">Let`s go!</button>
                        <a href="index.php" class="back-button">← Înapoi</a>
                    </form>
                </div>
                <div class="flip-card__back">
                    <div class="title">Sign up</div>
                    <form class="flip-card__form" action="inregistrare.php" method="POST">
                        <input class="flip-card__input" name="email" placeholder="Email" type="email">
                        <input class="flip-card__input" name="nume" placeholder="Nume" type="text">
                        <input class="flip-card__input" name="prenume" placeholder="Prenume" type="text">
                        <input class="flip-card__input" name="telefon" placeholder="Nr. Telefon" type="text">
                        <input class="flip-card__input" name="parola" placeholder="Parola" type="password">
                        <input class="flip-card__input" name="parola2" placeholder="Rescrieți parola" type="password">
                        <button class="flip-card__btn" type="submit">Confirm!</button>
                        <a href="index.php" class="back-button">← Înapoi</a>
                    </form>
                </div>
            </div>
        </div>   
    </div>

    <?php
    if (isset($_GET['eroare'])) {
        switch ($_GET['eroare']) {
            case 0:
                echo '<script>alert("Înregistrare reușită!");</script>';
                break;
            case 1:
                echo '<script>alert("Email-ul este deja înregistrat!");</script>';
                break;
            case 2:
                echo '<script>alert("Email sau parolă incorecte!");</script>';
                break;
            case 3:
                echo '<script>alert("Numărul de telefon trebuie să conțină exact 10 cifre!");</script>';
                break;
            case 4:
                echo '<script>alert("Parolele nu coincid!");</script>';
                break;
        }
      }
    ?>

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

         function toggleMode() {
         const toggle = document.getElementById('modeToggle');
         const cardInner = document.querySelector('.flip-card__inner');
         const cardSwitch = document.querySelector('.card-switch');

         if (toggle.checked) {
            cardInner.style.transform = 'rotateY(180deg)';
            cardSwitch.classList.add('expanded');
         } else {
            cardInner.style.transform = 'rotateY(0deg)';
            cardSwitch.classList.remove('expanded');
         }

    }
    </script>
</body>
</html>