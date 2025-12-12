<?php
require_once 'models/UserDAO.php';

class UserController
{
    public function index()
    {
        if (isset($_SESSION['identity'])) {
            header("Location: index.php?controller=user&action=profile");
        } else {
            header("Location: index.php?controller=user&action=login");
        }
    }

    public function register()
    {
        if (isset($_POST) && !empty($_POST)) {
            $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : false;
            $apellidos = isset($_POST['apellidos']) ? $_POST['apellidos'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : false;
            $password = isset($_POST['password']) ? $_POST['password'] : false;

            if ($nombre && $email && $password) {
                $user = new User();
                $user->setNombre($nombre);
                $user->setApellidos($apellidos);
                $user->setEmail($email);
                $user->setPassword($password);
                $user->setRol('customer');

                $userDAO = new UserDAO();
                
                try {
                    $save = $userDAO->create($user);
                    if ($save) {
                        $_SESSION['register'] = "complete";
                        header("Location: index.php?controller=user&action=login");
                        return;
                    } else {
                        $_SESSION['register'] = "failed";
                    }
                } catch (Exception $e) {
                    $_SESSION['register'] = "failed";
                }
            } else {
                $_SESSION['register'] = "failed";
            }
        }
        require_once 'views/layout/header.php';
        require_once 'views/user/register.php';
        require_once 'views/layout/footer.php';
    }

    public function login()
    {
        if (isset($_POST) && !empty($_POST)) {
            $userDAO = new UserDAO();
            $user = $userDAO->getByEmail($_POST['email']);

            if ($user && password_verify($_POST['password'], $user->getPassword())) {
                $_SESSION['identity'] = true;
                $_SESSION['user_id'] = $user->getId();
                $_SESSION['user_name'] = $user->getNombre();
                $_SESSION['user_email'] = $user->getEmail();
                $_SESSION['user_role'] = $user->getRol();

                if ($user->getRol() == 'admin') {
                    $_SESSION['admin'] = true;
                }
                header("Location: index.php");
                return;
            } else {
                $_SESSION['error_login'] = 'Identificación fallida !!';
            }
        }
        require_once 'views/layout/header.php';
        require_once 'views/user/login.php';
        require_once 'views/layout/footer.php';
    }

    public function logout()
    {
        if (isset($_SESSION['identity'])) {
            session_unset();
            session_destroy();
        }
        header("Location: index.php");
    }

    public function profile()
    {
        if (!isset($_SESSION['identity'])) {
            header("Location: index.php?controller=user&action=login");
            return;
        }

        $userDAO = new UserDAO();
        $user = $userDAO->getById($_SESSION['user_id']);
        
        require_once 'views/layout/header.php';
        require_once 'views/user/profile.php';
        require_once 'views/layout/footer.php';
    }

    public function update()
    {
        if (isset($_POST) && isset($_SESSION['identity'])) {
            $userDAO = new UserDAO();
            $user = $userDAO->getById($_SESSION['user_id']);
            
            $user->setNombre($_POST['nombre']);
            $user->setApellidos($_POST['apellidos']);
            $user->setEmail($_POST['email']);

            $update = $userDAO->update($user);

            if ($update) {
                $_SESSION['user_name'] = $user->getNombre();
                $success = "Datos actualizados correctamente";
            } else {
                $errors = ["Error al actualizar los datos"];
            }
        }
        $this->profile();
    }
}
