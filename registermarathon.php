<?php
    spl_autoload_register(function($class){
        include __DIR__ . '/lib/' . $class . '.php';
    });
    session_start();

    if (!isset($_SESSION['email'])) {
        $_SESSION['error'] = 'You need to login first';
        header('Location: login.php');
        exit;
    }
    $db = new Database();
    $user = new User();
    $marathon = new Marathon();
    $marathons = $marathon->getAllMarathons();

    $successMessage = $_SESSION['success'] ?? null;
    unset($_SESSION['success']);
    $errorMessage = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    $today = date('Y-m-d');
    $id = $_GET['id'] ?? null;
    if ($id === null) {
        $_SESSION['error'] = 'Invalid marathon id';
        header('Location: listmarathon.php');
        exit;
    }

    $marathonData = $marathon->findMarathon($id);
    if (!$marathonData) {
        $_SESSION['error'] = 'Marathon not found';
        header('Location: listmarathon.php');
        exit;
    }
    $thisUser = $user->getInformation($_SESSION['email']);
    if (!$thisUser) {
        $_SESSION['error'] = 'User not found';
        header('Location: listmarathon.php');
        exit;
    }
    if ($marathon->checkParticipateExist($id, $thisUser['id'])) {
        $_SESSION['error'] = 'You have already registered for this marathon';
        header('Location: listmarathon.php');
        exit;
    }
    if ($marathonData['date'] <= $today) {
        $_SESSION['error'] = 'Marathon has already ended';
        header('Location: listmarathon.php');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $marathon->regParticipate([
            'hotel' => $_POST['hotel'] ?? null,
            'marathon_id' => $id,
            'user_id' => $thisUser['id']
        ]);
        $_SESSION['success'] = 'You have successfully registered for the marathon';
        header('Location: index.php');
        exit;
        
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Marathon Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: rgb(255, 245, 250);
        }
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #ffc107;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2rem;
        }
        .form-label {
            font-weight: bold;
            color: #6c757d;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            outline: none;
            border-color: #ffc107;
            box-shadow: 0 0 5px rgba(255, 193, 7, 0.6);
        }
        .btn-submit {
            background: #ffc107;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            padding: 10px 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s, transform 0.2s;
        }
        .btn-submit:hover {
            background: #e6ac00;
            transform: translateY(-2px);
        }
        .auth{
            display: block;
        }
        .non-auth{
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'template/navbar.html'; ?>
    <?php include 'template/message.php'; ?>

    <div class="container form-container">
        <h1>Join Marathon</h1>

        <form method="POST" action="registermarathon.php?id=<?php echo $id; ?>">
            <div class="mb-3">
                <label for="hotel" class="form-label">Hotel (Optional)</label>
                <input type="text" class="form-control" id="hotel" name="hotel" placeholder="Enter hotel name (if any)">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-submit">Register</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>