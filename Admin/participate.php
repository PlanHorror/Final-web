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
$marathonDataId = $_GET['id'] ?? null;
// If no marathon id is provided, redirect to listmarathon.php
if ($marathonDataId === null) {
    $_SESSION['error'] = 'Invalid marathon id';
    header('Location: listmarathon.php');
    exit;
}
$marathonData = $marathon->findMarathon($marathonDataId);
// If no marathon is found, redirect to listmarathon.php
if (!$marathonData) {
    var_dump($marathonData);
    $_SESSION['error'] = 'Marathon not found';
    header('Location: listmarathon.php');
    exit;
}
$marathonData = $marathon->getParticipate($marathonDataId);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marathon->updateParticipate($_POST);
    $_SESSION['success'] = 'Participants updated successfully';
    header('Location: participate.php?id=' . $marathonDataId);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Marathon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>

body {
            background-color: rgb(255, 245, 250);
        }
        .table-container {
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 90%;
        }
        h1 {
            color: #ffc107;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-control {
            width: 100px;
            display: inline-block;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .non-auth {
            display: none;
        }
    </style>
</head>
<body>
    <?php include '../template/ad-navbar.html'; ?>
    <?php include '../template/message.php'; ?>
    <div class="container table-container">
        <h1>Marathon Participants</h1>
        <form action="participate.php<?php echo '?id=' . $marathonDataId ?>" method="post">
        <input type="hidden" name="marathon_id" value="<?= $marathonDataId ?>">
        <table class="table table-bordered table-hover">
            <thead class="table-warning">
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Entry Number</th>
                    <th>Standing</th>
                    <th>Record</th>
                </tr>
            </thead>
            <tbody>
                
                <?php if (!empty($marathonData)): ?>
                    <?php foreach ($marathonData as $index => $participant): ?>
                        
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($participant['user']['name']) ?></td>
                            <td>
                                <input type="number" class="form-control" name="entry_number[<?= $participant['user']['id'] ?>]" value="<?= htmlspecialchars($participant['entry_number'] ?? '') ?>">
                            </td>
                            <td><?= htmlspecialchars($participant['standing']) ?></td>
                            <td>
                                <input type="text" class="form-control" name="record[<?= $participant['user']['id'] ?>]" value="<?= htmlspecialchars($participant['record'] ?? '') ?>">
                            </td>
                        </tr>
                        
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="5" class="text-center">
                            <button type="submit" class="btn btn-warning w-100">Update</button>
                        </td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No participants found</td>
                    </tr>
                <?php endif; ?>
                
            </tbody>
        </table>
        </form> 
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
