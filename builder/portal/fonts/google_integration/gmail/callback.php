<?php
session_start();
header('Location: https://www.smallbuilders.com.au/builder/portal/gmail_integration.php?ui=' . $_SESSION['ui'] . '&ci=' . $_SESSION['ci']);
?>