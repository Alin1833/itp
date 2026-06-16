<?php
    session_start();
    session_destroy();
    header("Location: index.php?eroare=1");
    exit();
?>