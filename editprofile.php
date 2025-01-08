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
    $successMessage = $_SESSION['success'] ?? null;
    unset($_SESSION['success']);
    $errorMessage = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    $user = new User();
    $userData = $user->getInformation($_SESSION['email']);
    $nationalities = json_decode(file_get_contents('data/nationalities.json'), true);
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $errors = $user->update($_POST);
        if (count($errors) === 0) {
            $_SESSION['success'] = 'Profile updated successfully';
            header('Location: edit_profile.php');
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: rgb(255, 245, 250);
        }
        .card {
            margin: 50px auto;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            max-width: 600px;
        }
        .form-error {
            color: rgb(187, 45, 57);
        }
        .auth {
            display: block;
        }
        .non-auth {
            display: none;
        }
    </style>
</head>
<body>
    <?php include 'template/navbar.html'; ?>
    <?php include 'template/message.php'; ?>
    <div class="container">
        <div class="card p-5">
            <h2 class="text-center mb-4">Edit Profile</h2>

            <!-- Edit Profile Form -->
            <form action="editprofile.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($userData['name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($userData['phone_number']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="nationality" class="form-label">Nationality</label>
                    <select class="form-select" id="nationality" name="nationality" required>
                        <option value="">Select</option>
                        <?php foreach ($nationalities as $nationality): ?>
                            <option value="<?php echo $nationality; ?>" <?php echo $userData
['nationality'] == $nationality ? 'selected' : ''; ?>><?php echo $nationality; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($userData['age']); ?>" min="1" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Gender</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="male" name="gender" value="Male" <?php echo $userData['gender'] == 'Male' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="female" name="gender" value="Female" <?php echo $userData['gender'] == 'Female' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" id="other" name="gender" value="Other" <?php echo $userData['gender'] == 'Other' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="other">Other</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning w-100">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS and Popper.js (Optional for Bootstrap components like tooltips, modals, etc.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
