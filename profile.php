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

    // Fetch achievements of the user
    $achievements = $user->getArchive($_SESSION['email']);  // Assuming getAchievements() fetches the data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: rgb(255, 245, 250);
        }
        .card {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            margin: 50px auto;
            max-width: 800px;
        }
        .form-error {
            color: rgb(187, 45, 57);
        }
        .non-auth {
            display: none;
        }
        .auth {
            display: block;
        }
        h2 {
            color: #ffc107; /* Yellow main heading */
        }
    </style>
</head>
<body>
    <?php include 'template/navbar.html'; ?>
    <?php include 'template/message.php'; ?>

    <div class="container">
        <!-- Profile Card -->
        <div class="card p-4">
            <h2 class="text-center mb-4">User Profile</h2>

            <!-- User Profile Information -->
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="full_name" value="<?php echo htmlspecialchars($userData['name']); ?>" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($userData['email']); ?>" disabled>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="text" class="form-control" id="phone_number" value="<?php echo htmlspecialchars($userData['phone_number']); ?>" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nationality" class="form-label">Nationality</label>
                        <input type="text" class="form-control" id="nationality" value="<?php echo htmlspecialchars($userData['nationality']); ?>" disabled>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="number" class="form-control" id="age" value="<?php echo htmlspecialchars($userData['age']); ?>" disabled>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <input type="text" class="form-control" id="gender" value="<?php echo htmlspecialchars($userData['gender']); ?>" disabled>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="best_record" class="form-label">Best Record</label>
                <input type="text" class="form-control" id="best_record" value="<?php echo htmlspecialchars($userData['best_record'] ?? 'Not available'); ?>" disabled>
            </div>
            <a href="editprofile.php" class="btn btn-warning">Edit Profile</a>
        </div>

        <!-- Achievement Table -->
        <div class="card p-4 mt-4">
            <h2 class="text-center mb-4">Achievements</h2>

            <?php  if ($achievements): ?>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Race Name</th>
                            <th>Hotel Name</th>
                            <th>Record Time</th>
                            <th>Standings</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($achievements as $index => $achievement): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($achievement['marathon']); ?></td>
                                <td><?php echo htmlspecialchars($achievement['hotel']); ?></td>
                                <td><?php echo htmlspecialchars($achievement['record']); ?></td>
                                <td><?php echo htmlspecialchars($achievement['standing']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">No achievements available.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
