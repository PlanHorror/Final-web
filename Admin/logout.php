<?php
spl_autoload_register(function($class){
    include __DIR__ . '/..'. '/lib/' . $class . '.php';
});
session_start();

$db = new Database();
$admin = new Admin();
if (!isset($_SESSION['email'])) {
    $_SESSION['error'] = 'You need to login first';
    header('Location: login.php');
    exit;
} else {
    $admin = new Admin();
    $adminData = $admin->getInformation($_SESSION['email']);
    if (!$adminData['admin']==1){
        $_SESSION['error'] = "You don't have permission to access this page";
        header('Location: login.php');
        exit;
    } else {
        unset($_SESSION['email']);
        unset($_SESSION['success']);
        unset($_SESSION['error']);
        $_SESSION['success'] = 'You are now logged out';
        header('Location: login.php');
        exit;
    }
} 

?>