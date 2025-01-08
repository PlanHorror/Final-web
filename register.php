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
    $db = new Database();
    $user = new User();
    $nationalities = json_decode(file_get_contents('data/nationalities.json'), true);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = $user->register($_POST);
        if (empty($errors)) {
            $_SESSION['success'] = 'You have successfully registered';
            header('Location: login.php');
            exit();
        }
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
            <h1 class="text-center mb-4">User Registration</h1>
            <form action="register.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="nationality" class="form-label">Nationality</label>
                    <select class="form-select" id="nationality" name="nationality" required>
                        <option value="" disabled selected>Select your nationality</option>
                        <?php foreach ($nationalities as $nationality): ?>
                            <option value="<?php echo htmlspecialchars($nationality); ?>"><?php echo htmlspecialchars($nationality); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="passport_no" class="form-label">Passport Number (Optional)</label>
                    <input type="text" class="form-control" id="passport_no" name="passport_no">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                </div>



                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" required min="1">
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="male" name="gender" value="Male" required>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="female" name="gender" value="Female">
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="other" name="gender" value="Other">
                        <label class="form-check-label" for="other">Other</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password1" name="password1" required>
                </div>

                <button type="submit" class="btn btn-warning w-100">Register</button>
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
