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
$accountData = $admin->getAllAccounts();
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
        th {
             background-color:rgb(255, 240, 195) !important; color: #000 !important; text-align: center !important;
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
        <h1 class="text-center text-warning mb-4" style="font-family: 'Arial', sans-serif;">Account Information</h1>
        <?php if (!empty($accountData)): ?>
            <div class="table-responsive">
                <!-- Table wrapped in a white frame with border -->
                <div class="table-frame" style="background-color: white; padding: 20px; border-radius: 15px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                    <table class="table table-hover table-striped" style="background-color: #fff3cd;  overflow: hidden;">
                        <thead style="background-color: #ffc107; color: #fff; text-align: center;">
                            <tr style="background-color: #ffc107; color: #fff; text-align: center;">
                                <th class="design-th">#</th>
                                <th class="design-th">Name</th>
                                <th class="design-th">Nationality</th>
                                <th class="design-th">Passport No</th>
                                <th class="design-th">Email</th>
                                <th class="design-th">Phone Number</th>
                                <th class="design-th">Age</th>
                                <th class="design-th">Gender</th>
                                <th class="design-th">Best Record</th>
                                <th class="design-th">Admin</th>
                            </tr>
                        </thead>
                        <tbody style="text-align: center;">
                            <?php foreach ($accountData as $index => $account): ?>
                                <tr>
                                    <td style="color: #6c757d;"><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($account['name']) ?></td>
                                    <td><?= htmlspecialchars($account['nationality']) ?></td>
                                    <td><?= htmlspecialchars($account['passport_no']) ?></td>
                                    <td><?= htmlspecialchars($account['email']) ?></td>
                                    <td><?= htmlspecialchars($account['phone_number']) ?></td>
                                    <td><?= htmlspecialchars($account['age']) ?></td>
                                    <td><?= htmlspecialchars($account['gender']) ?></td>
                                    <td><?= htmlspecialchars($account['best_record']) ?></td>
                                    <td>
                                        <span class="badge <?= $account['admin'] ? 'bg-success' : 'bg-danger'; ?>" style="font-size: 1rem;">
                                            <?= $account['admin'] ? 'Yes' : 'No' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center" style="color: #6c757d;">No accounts available.</p>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
