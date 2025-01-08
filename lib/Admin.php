<?php
spl_autoload_register(function($class){
    include __DIR__ . '/' . $class . '.php';
});
class Admin {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Validate the admin login form
    public function validateLogin($data) {
        $errors = [];
        // Check if the email is not a valid email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is not valid';
        }
        // Check if the password is smaller than 6 characters
        if (strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long';
        }
        $admin = $this->db->find('email', $data['email'], 'user');
        // Check if the admin exists
        if (!$admin) {
            $errors['email'] = 'Admin does not exist';
        } else {
            // Check if the password is correct
            if (!password_verify($data['password'], $admin['password'])) {
                $errors['password'] = 'Password is incorrect';
            }
        }
        // Check if is a admin
        if (!$admin['admin']==1){
            $errors['admin'] = "You don't have permission to access this page";
        }
        return $errors;
    }
    // Login the admin
    public function login($data) {
        $errors = $this->validateLogin($data);
        if (count($errors) === 0) {
            $_SESSION['email'] = $data['email'];
            $_SESSION['success'] = 'You are now logged in';
            header('Location: index.php');
            exit();
        }
        return $errors;
    }
    public function getInformation($email) {
        return $this->db->find('email', $email, 'user');
    }
    public function getAllAccounts() {
        return $this->db->read('user');
    }

}
?>