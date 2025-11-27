<?php
class UserController extends Controller
{
    // CREATE (register)
    public function register(): void
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name      = trim($_POST['name'] ?? '');
            $lastName  = trim($_POST['last_name'] ?? '');
            $email     = trim($_POST['email'] ?? '');
            $password  = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';

            if ($name === '' || $email === '' || $password === '') {
                $errors[] = "Name, email and password are required.";
            }
            if ($password !== $password2) {
                $errors[] = "Passwords do not match.";
            }

            $userModel = new User();

            if (empty($errors)) {
                if ($userModel->getUserByEmail($email)) {
                    $errors[] = "User with this email already exists.";
                } else {
                    if ($userModel->createUser($name, $lastName, $email, $password)) {
                        $this->redirect('index.php?controller=user&action=login');
                    } else {
                        $errors[] = "Error creating user.";
                    }
                }
            }
        }

        $this->render('user/register', [
            'title'  => 'Register',
            'errors' => $errors
        ]);
    }

    // LOGIN
    public function login(): void
    {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($email === '' || $password === '') {
                $errors[] = "Email and password are required.";
            } else {
                $userModel = new User();
                $user = $userModel->getUserByEmail($email);

                if ($user && password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_role'] = $user['rol'];
                    $this->redirect('index.php');
                } else {
                    $errors[] = "Wrong email or password.";
                }
            }
        }

        $this->render('user/login', [
            'title'  => 'Login',
            'errors' => $errors
        ]);
    }

    // LOGOUT
    public function logout(): void
    {
        session_destroy();
        $this->redirect('index.php');
    }

    // SHOW (profile)
    public function profile(): void
    {
        $this->requireLogin();

        $userModel    = new User();
        $addressModel = new Address();

        $user      = $userModel->getUserById($_SESSION['user_id']);
        $addresses = $addressModel->getAddressesByUser($_SESSION['user_id']);

        $this->render('user/profile', [
            'title'     => 'My account',
            'user'      => $user,
            'addresses' => $addresses
        ]);
    }
}
