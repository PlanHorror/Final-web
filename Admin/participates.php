<?php 
spl_autoload_register(function($class){
    include __DIR__ . '/..'. '/lib/' . $class . '.php';
});
session_start();

$db = new Database();
$admin = new Admin();
$marathon = new Marathon();
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
    }
} 
$successMessage = $_SESSION['success'] ?? null;
unset($_SESSION['success']);
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
$listParticipate = $marathon->getAllMarathons();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Participates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>

        .bg-primary-custom {
            background-color: #6c757d; /* Custom grayish-blue color */
        }
        body {
            background-color: rgb(255, 245, 250);
        }
        .card {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            margin: 50px auto;
            max-width: 600px;
            padding: 20px;
        }
        h1 {
            color: #ffc107; /* Yellow main heading */
            text-align: center;
            padding-bottom: 20px;
        }
        .form-error {
            color: rgb(187, 45, 57);
        }
        .non-auth {
                display: none;
            }
    </style>
</head>
<body>
    <?php include '../template/ad-navbar.html'; ?>
    <?php include '../template/message.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center text-warning mb-4" style="font-family: 'Arial', sans-serif;">Select a Marathon</h1>

        <div class="dropdown text-center">
            <button class="btn btn-warning dropdown-toggle container" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Choose a Marathon
            </button>
            <ul class="dropdown-menu container text-center" aria-labelledby="dropdownMenuButton">
                <?php if (!empty($listParticipate)): ?>
                    <?php foreach ($listParticipate as $marathon): ?>
                        <li>
                            <a 
                                class="dropdown-item " 
                                href="participate.php?id=<?= urlencode($marathon['id']) ?>">
                                <?= htmlspecialchars($marathon['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="dropdown-item text-muted">No marathons available</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
