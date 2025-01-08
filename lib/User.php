<?php
spl_autoload_register(function($class){
    include __DIR__ . '/' . $class . '.php';
});
class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Validate the user registration form
    public function validate($data) {
        $errors = [];
        // Check if the name is smaller than 3 characters or bigger than 50 characters or contains special characters
        if (strlen($data['name']) < 3 || strlen($data['name']) > 50 || !preg_match('/^[a-zA-Z ]+$/', $data['name'])) {
            $errors['name'] = 'Name must be between 3 and 50 characters and contain only letters and spaces';
        }
        if (isset($data['passport_no']) && !empty($data['passport_no'])) {
            // Check if the passport number is bigger than 20 characters or contains special characters
            if (strlen($data['passport_no']) > 20 || !preg_match('/^[a-zA-Z0-9 ]+$/', $data['passport_no'])) {
                $errors['passport_no'] = 'Passport number must be less than 20 characters and contain only letters, numbers, and spaces';
            }
            // Check passport number uniqueness
            if ($this->db->find('passport_no', $data['passport_no'], 'user')) {
                $errors['passport_no'] = 'Passport number is already taken';
            }
        }

        // Check if the email is not a valid email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email is not valid';
        }
        // Check email uniqueness
        if ($this->db->find('email', $data['email'], 'user')) {
            $errors['email'] = 'Email is already taken';
        }
        // Check if the phone number is not a valid phone number
        if (!preg_match('/^[0-9]{10}$/', $data['phone_number'])) {
            $errors['phone_number'] = 'Phone number must be 10 digits';
        }
        // Check phone number uniqueness
        if ($this->db->find('phone_number', $data['phone_number'], 'user')) {
            $errors['phone_number'] = 'Phone number is already taken';
        }
        // Check if the age is smaller than 18 and bigger than 80
        if ($data['age'] < 18 || $data['age'] > 80) {
            $errors['age'] = 'Age must be between 18 and 80';
        }
        // Check password length
        if (strlen($data['password']) < 6) {
            $errors['password'] = 'Password must be at least 6 characters long';
        }
        // Check if the password and confirm password match
        if ($data['password'] !== $data['password1']) {
            $errors['password'] = 'Passwords do not match';
        }
        return $errors;
    }
    // Register the user
    public function register($data) {
        $errors = $this->validate($data);
        if (count($errors) === 0) {
            
            if(isset($data['passport_no'])) {
                $this->db->create([
                    'name' => $data['name'],
                    'nationality' => $data['nationality'],
                    'passport_no' => $data['passport_no'],
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'],
                    'age' => $data['age'],
                    'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                    'gender' => $data['gender'],
                ], 'user');
            } else {
                try
                {$this->db->create([
                    'name' => $data['name'],
                    'nationality' => $data['nationality'],
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'],
                    'age' => $data['age'],
                    'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                    'gender' => $data['gender'],
                ], 'user');
                }
                catch(Exception $e)
                {
                    echo $e->getMessage();
                }
            }
            return;
        }
        return $errors;
    }
    // Validate the user login form
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
        $user = $this->db->find('email', $data['email'], 'user');
        // Check if the user exists
        if (!$user) {
            $errors['email'] = 'User does not exist';
        } else {
            // Check if the password is correct
            if (!password_verify($data['password'], $user['password'])) {
                $errors['password'] = 'Password is incorrect';
            }
        }
        return $errors;
    }
    // Login the user
    public function login($data) {
        $errors = $this->validateLogin($data);
        if (count($errors) === 0) {
            session_start();
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
    public function getArchive($email) {
        $user = $this->db->find('email', $email, 'user');
        $register_form = $this->db->readUseColumn('participate', 'user_id', $user['id']);
        foreach ($register_form as $key => $value) {
            $marathon = $this->db->find('id', $value['marathon_id'], 'marathon');
            $register_form[$key]['marathon'] = $marathon['name'];
        }
        return $register_form;

    }
    public function validateUpdate($data){
        $errors = [];
        // Check if the name is smaller than 3 characters or bigger than 50 characters or contains special characters
        if (strlen($data['name']) < 3 || strlen($data['name']) > 50 || !preg_match('/^[a-zA-Z ]+$/', $data['name'])) {
            $errors['name'] = 'Name must be between 3 and 50 characters and contain only letters and spaces';
        }

        // Check if the phone number is not a valid phone number
        if (!preg_match('/^[0-9]{10}$/', $data['phone_number'])) {
            // Check if the phone number is not the same as the current user's phone number
            $errors['phone_number'] = 'Phone number must be 10 digits';
        }
        // Check phone number uniqueness
        if ($this->db->find('phone_number', $data['phone_number'], 'user')) {
            // Find user have this phone number
            $user = $this->db->find('phone_number', $data['phone_number'], 'user'); 
            // Check if the phone number is not the same as the current user's phone number
            if ($user){
               if ($user['phone_number'] !== $data['phone_number']) {
                $errors['phone_number'] = 'Phone number is already taken';
            } 
            }
            
        }
        // Check if the age is smaller than 18 and bigger than 80
        if ($data['age'] < 18 || $data['age'] > 80) {
            $errors['age'] = 'Age must be between 18 and 80';
        }
        return $errors;
    }
    public function update($data) {
        $errors = $this->validateUpdate($data);
        if (count($errors) === 0) {
            $user = $this->db->find('email', $_SESSION['email'], 'user');
            $this->db->update([
                'name' => $data['name'],
                'phone_number' => $data['phone_number'],
                'age' => $data['age'],
                'gender' => $data['gender'],
            ], 'user',$user['id']);
            $_SESSION['email'] = $data['email'];
            $_SESSION['success'] = 'Your information has been updated';
            header('Location: index.php');
            exit();
        }
        return $errors;
    }
}
?>