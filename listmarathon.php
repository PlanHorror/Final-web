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

    $marathon = new Marathon();
    $marathons = $marathon->getAllMarathons();

    $successMessage = $_SESSION['success'] ?? null;
    unset($_SESSION['success']);
    $errorMessage = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    $today = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marathons List</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: rgb(255, 245, 250); /* Keep old background color */
        }
        .card {
            border: 3px solid rgb(255, 255, 255); /* Highlighted with yellow */
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .btn-warning {
            color: #fff; /* White text on yellow button */
        }
        h1 {
            color: #ffc107; /* Yellow main heading */
        }
        .card-title {
            color: #ffc107; /* Yellow for marathon titles */
        }
        .card-footer {
            background-color: #fff3cd; /* Subtle yellow tint for footer */
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

    <div class="container mt-5">
        <h1 class="text-center mb-4">Available Marathons</h1>

        <!-- Handle empty marathons -->
        <?php if (empty($marathons)): ?>
            <div class="alert alert-warning text-center" role="alert">
                No marathons available
            </div>
        <?php endif; ?>
        <!-- Marathon Cards -->
        <div class="row g-5">
            <?php foreach ($marathons as $marathon): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars($marathon['image']); ?>" class="card-img-top" alt="Marathon Image" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($marathon['name']); ?></h5>
                            <p class="card-text">
                                <strong>Date:</strong> <?php echo htmlspecialchars($marathon['date']); ?><br>
                                <strong>Description:</strong> <?php echo htmlspecialchars($marathon['description'] ?? 'N/A'); ?>
                            </p>
                        </div>
                        <?php if ($marathon['date'] > $today): ?>
                        <div class="card-footer text-center">
                            <a href="registermarathon.php?id=<?php echo $marathon['id']; ?>" class="btn btn-warning w-100">Register Now</a>
                        </div>
                        <?php else: ?>
                        <div class="card-footer text-center">
                            <a href="registermarathon.php?id=<?php echo $marathon['id']; ?>" class="btn btn-danger w-100 disabled" aria-disabled="true">Registration Closed</a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
