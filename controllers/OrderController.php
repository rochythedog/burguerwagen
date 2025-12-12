<?php
require_once 'models/OrderDAO.php';
require_once 'models/OrderItemDAO.php';
require_once 'models/ProductDAO.php';

class OrderController
{
    public function index()
    {
        if (isset($_SESSION['identity'])) {
            $userId = $_SESSION['user_id'];
            $orderDAO = new OrderDAO();
            // Obtener pedidos como objetos
            $orders = $orderDAO->getAllByUser($userId);
            
            require_once 'views/layout/header.php';
            require_once 'views/order/my_orders.php';
            require_once 'views/layout/footer.php';
        } else {
            header("Location: index.php?controller=user&action=login");
        }
    }

    public function add()
    {
        if (isset($_GET['id'])) {
            $producto_id = $_GET['id'];
        } else {
            header("Location: index.php");
            return;
        }

        if (isset($_SESSION['carrito'])) {
            $counter = 0;
            foreach ($_SESSION['carrito'] as $indice => $elemento) {
                if ($elemento['id_producto'] == $producto_id) {
                    $_SESSION['carrito'][$indice]['unidades']++;
                    $counter++;
                }
            }
        }

        if (!isset($counter) || $counter == 0) {
            $productDAO = new ProductDAO();
            $producto = $productDAO->getById($producto_id);

            if ($producto) {
                // Creamos un objeto estándar para guardar en sesión
                $prodStd = new stdClass();
                $prodStd->id = $producto->getId();
                $prodStd->nombre = $producto->getNombre();
                $prodStd->precio = $producto->getPrecio();
                $prodStd->imagen = $producto->getImagen();

                $_SESSION['carrito'][] = array(
                    "id_producto" => $producto->getId(),
                    "precio" => $producto->getPrecio(),
                    "unidades" => 1,
                    "producto" => $prodStd
                );
            }
        }

        header("Location: index.php?controller=order&action=cart");
    }

    public function cart()
    {
        require_once 'views/layout/header.php';
        require_once 'views/order/cart.php';
        require_once 'views/layout/footer.php';
    }

    public function delete_all()
    {
        unset($_SESSION['carrito']);
        header("Location: index.php?controller=order&action=cart");
    }

    public function confirm()
    {
        if (isset($_SESSION['identity'])) {
            require_once 'views/layout/header.php';
            require_once 'views/order/confirm.php';
            require_once 'views/layout/footer.php';
        } else {
            header("Location: index.php?controller=user&action=login");
        }
    }

    public function make()
    {
        if (isset($_SESSION['identity'])) {
            if (isset($_POST) && isset($_SESSION['carrito'])) {
                $usuario_id = $_SESSION['user_id'];
                $provincia = isset($_POST['provincia']) ? $_POST['provincia'] : '';
                $localidad = isset($_POST['localidad']) ? $_POST['localidad'] : '';
                $direccion = isset($_POST['direccion']) ? $_POST['direccion'] : '';

                $coste = 0;
                foreach ($_SESSION['carrito'] as $indice => $elemento) {
                    $coste += $elemento['precio'] * $elemento['unidades'];
                }

                $order = new Order();
                $order->setUsuarioId($usuario_id);
                $order->setProvincia($provincia);
                $order->setLocalidad($localidad);
                $order->setDireccion($direccion);
                $order->setCoste($coste);
                $order->setEstado('confirm');

                $orderDAO = new OrderDAO();
                $save = $orderDAO->save($order);

                if ($save) {
                    $orderItemDAO = new OrderItemDAO();
                    foreach ($_SESSION['carrito'] as $elemento) {
                        $item = new OrderItem();
                        $item->setPedidoId($order->getId());
                        $item->setProductoId($elemento['id_producto']);
                        $item->setUnidades($elemento['unidades']);
                        $orderItemDAO->save($item);
                    }

                    unset($_SESSION['carrito']);
                    header("Location: index.php?controller=order&action=confirmed&id=" . $order->getId());
                } else {
                    header("Location: index.php?controller=order&action=confirm");
                }
            } else {
                header("Location: index.php");
            }
        } else {
            header("Location: index.php?controller=user&action=login");
        }
    }
    
    public function confirmed()
    {
        if(isset($_SESSION['identity']) && isset($_GET['id'])){
            $orderDAO = new OrderDAO();
            $order = $orderDAO->getById($_GET['id']);
            
            // Verificar que el pedido pertenece al usuario
            if ($order && $order->getUsuarioId() == $_SESSION['user_id']) {
                $orderItemDAO = new OrderItemDAO();
                $productos = $orderItemDAO->getByOrderId($_GET['id']);
                
                require_once 'views/layout/header.php';
                require_once 'views/order/confirmed.php';
                require_once 'views/layout/footer.php';
            } else {
                header("Location: index.php?controller=order&action=index");
            }
        } else {
            header("Location: index.php");
        }
    }
}
