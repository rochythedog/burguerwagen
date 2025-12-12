<?php
require_once 'models/OrderDAO.php';
require_once 'models/OrderItemDAO.php';
require_once 'models/ProductDAO.php';
require_once 'models/AddressDAO.php';

class OrderController
{
    public function index()
    {
        if (isset($_SESSION['identity'])) {
            $userId = $_SESSION['user_id'];
            $orderDAO = new OrderDAO();
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
                
                // Recoger datos del formulario
                $direccion = $_POST['direccion'] ?? '';
                $ciudad = $_POST['ciudad'] ?? '';
                $cp = $_POST['cp'] ?? '';
                $pais = $_POST['pais'] ?? 'España';

                // 1. Guardar la dirección primero
                $address = new Address();
                $address->setUsuarioId($usuario_id);
                $address->setAlias('Envío ' . date('d/m/Y'));
                $address->setDireccion($direccion);
                $address->setCiudad($ciudad);
                $address->setCp($cp);
                $address->setPais($pais);

                $addressDAO = new AddressDAO();
                $savedAddr = $addressDAO->save($address);

                if ($savedAddr) {
                    // 2. Calcular total
                    $total = 0;
                    foreach ($_SESSION['carrito'] as $elemento) {
                        $total += $elemento['precio'] * $elemento['unidades'];
                    }

                    // 3. Crear el pedido
                    $order = new Order();
                    $order->setUsuarioId($usuario_id);
                    $order->setDireccionId($address->getId()); // Usamos el ID de la dirección guardada
                    $order->setTotal($total);
                    $order->setEstado('confirm');
                    $order->setMoneda('EUR');

                    $orderDAO = new OrderDAO();
                    $save = $orderDAO->save($order);

                    if ($save) {
                        // 4. Guardar líneas de pedido
                        $orderItemDAO = new OrderItemDAO();
                        foreach ($_SESSION['carrito'] as $elemento) {
                            $item = new OrderItem();
                            $item->setPedidoId($order->getId());
                            $item->setProductoId($elemento['id_producto']);
                            $item->setCantidad($elemento['unidades']);
                            $item->setPrecioUnitario($elemento['precio']);
                            $orderItemDAO->save($item);
                        }

                        unset($_SESSION['carrito']);
                        header("Location: index.php?controller=order&action=confirmed&id=" . $order->getId());
                    } else {
                        header("Location: index.php?controller=order&action=confirm");
                    }
                } else {
                    // Error al guardar dirección
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
