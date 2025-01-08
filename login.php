<?php
    spl_autoload_register(function($class){
        include __DIR__ . '/lib/' . $class . '.php';
    });
    session_start();
    if (isset($_SESSION['email'])) {
        $_SESSION['error'] = 'You are already logged in';
        header('Location: index.php');
        exit();
    }
    $successMessage = $_SESSION['success'] ?? null;
    unset($_SESSION['success']);
    $errorMessage = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    $user = new User();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = $user->login($_POST);
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: rgb(255, 245, 250);
        }
        .card {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            margin: 50px;
        }
        .form-error {
            color:rgb(187, 45, 57);
        }
        .non-auth {
            display: block;
        }
        .auth {
            display: none;
        }
        h1 {
            color: #ffc107; /* Yellow main heading */
        }
    </style>
</head>
<body>
    <?php include 'template/navbar.html'; ?>
    <?php include 'template/message.php'; ?>
    <div class="container mt-5">
        <!-- Card with white frame for the form -->
        <div class="card p-5">
            <h1 class="text-center mb-4">User Login</h1>
            <form action="login.php" method="POST">

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>


                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-warning w-100">Login</button>
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

    <!-- Bootstrap 5 JS and Popper.js (Optional for Bootstrap components like tooltips, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
