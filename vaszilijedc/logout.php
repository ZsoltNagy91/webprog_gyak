<?php
// Indítjuk a session-t
session_start();

// Session törlése (kijelentkezés)
session_unset(); // Eltávolítja az összes session változót
session_destroy(); // Megszünteti a session-t

// Átirányítás a kezdőlapra vagy belépéshez
header("Location: login.php"); // Vagy login.php, ha inkább oda szeretnéd irányítani
exit();
?>