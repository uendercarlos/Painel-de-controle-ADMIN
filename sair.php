<?php
session_start();

unset($_SESSION['session_farma']);
unset($_SESSION['autenticacao']);
setcookie('cookie_farma', '', 1);
        
header('Location: pages/login.php');
exit;