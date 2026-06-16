# ITP - Inspectie Tehnica Periodica

Aplicatie web PHP pentru gestiunea unei statii de inspectie tehnica periodica (ITP): clientii isi inregistreaza masinile si fac programari, iar administratorul gestioneaza inspectiile, preturile si zilele libere.

## Functionalitati
- Cont si autentificare (inregistrare, login, resetare parola, logout)
- Gestiune masini (adaugare, actualizare, stergere)
- Programari pentru inspectii (creare, stergere)
- Inspectii: inregistrare si stergere
- Panou administrator (`indexAdmin.php`): preturi, zile libere
- Profil cont (`cont.php`, `updateCont.php`)

## Tehnologii
- PHP (mysqli)
- MySQL / MariaDB (baza de date `itp`)
- HTML / CSS

## Instalare locala (XAMPP)
1. Cloneaza in `htdocs`.
2. Creeaza baza de date `itp` in MySQL si tabelele aferente
   (programare, masina, inspectie, pret, cont etc.).
3. Conexiunea este configurata implicit pentru XAMPP: `localhost`, user `root`, parola goala.
4. Acceseaza `index.php` din browser.
