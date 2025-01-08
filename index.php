<?php 
session_start();
spl_autoload_register(function($class){
    include __DIR__ . '/lib/' . $class . '.php';
});
$successMessage = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-image: url('image/homepage.webp');
            /* height: 100%; */
            background-position: top;
            background-repeat: no-repeat;
            background-size: cover;
        }
        <?php if (isset($_SESSION['email'])): ?>
            .non-auth {
                display: none;
            }
        <?php else: ?>
            .auth {
                display: none;
            }
        <?php endif; ?>
    </style>
</head>
<body>
    <?php include 'template/navbar.html'; ?>
    <?php include 'template/message.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
