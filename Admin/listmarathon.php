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
$marathonData = $marathon->getAllMarathons();
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Marathon</title>
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
        <h1>Marathon List</h1>
        <div class="row">
    <?php if (!empty($marathonData)): ?>
        <?php foreach ($marathonData as $index => $marathon): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="<?= htmlspecialchars('../' . $marathon['image']) ?>" 
                         class="card-img-top" 
                         alt="Marathon Image" 
                         style="height: 200px; object-fit: cover;">

                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($marathon['name']) ?></h5>
                        <p class="card-text">
                            <strong>Date:</strong> <?= htmlspecialchars($marathon['date']) ?><br>
                            <strong>Description:</strong> <?= htmlspecialchars($marathon['description']) ?><br>
                            <strong>Total: </strong> <?= htmlspecialchars($marathon['total']) ?>
                        </p>
                    </div>

                    <div class="card-footer text-muted d-flex justify-content-between align-items-center">
                        <span>#<?= $index + 1 ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center">No marathons available.</p>
    <?php endif; ?>
</div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
