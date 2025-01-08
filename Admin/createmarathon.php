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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = $marathon->createMarathon($_POST, $_FILES);
    if (!$errors||!empty($errors)) {
        $_SESSION['success'] = 'Marathon created successfully';
        header('Location: index.php');
        exit();
}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Marathon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
.container-fluid {
            width: 100%; /* Full width */
        }
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
    <div class="container">
        <div class="card">
            <h1>Create New Marathon</h1>
            <form method="POST" action="createmarathon.php" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="marathonName" class="form-label">Marathon Name</label>
                    <input type="text" class="form-control" id="marathonName" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="marathonDate" class="form-label">Date</label>
                    <input type="date" class="form-control" id="marathonDate" name="date" required>
                </div>
                <div class="mb-3">
                    <label for="marathonImage" class="form-label">Image</label>
                    <input type="file" class="form-control" id="marathonImage" name="image" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="marathonDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="marathonDescription" name="des" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-warning w-100">Create Marathon</button>
                <?php if (!empty($errors)): ?>
                    <div style="padding: 10px; margin-top: 10px;" class="form-error">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
