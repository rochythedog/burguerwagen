<?php
require_once 'models/ProductDAO.php';
require_once 'models/CategoryDAO.php';

class ProductController
{
    // listar todos los productos
    public function index(): void
    {
        $productDAO = new ProductDAO();
        $productsObjects = $productDAO->getAll();
        
        // Conversión a array para compatibilidad con vistas existentes
        $products = [];
        foreach ($productsObjects as $p) {
            $products[] = [
                'id' => $p->getId(),
                'nombre' => $p->getNombre(),
                'precio' => $p->getPrecio(),
                'imagen' => $p->getImagen(),
                'descripcion' => $p->getDescripcion()
            ];
        }

        require_once 'views/layout/header.php';
        require_once 'views/product/index.php';
        require_once 'views/layout/footer.php';
    }

    // mostrar producto
    public function show(): void
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $productDAO = new ProductDAO();
            $productObj = $productDAO->getById($id);

            if ($productObj) {
                // Convertir a array para la vista
                $product = [
                    'id' => $productObj->getId(),
                    'nombre' => $productObj->getNombre(),
                    'precio' => $productObj->getPrecio(),
                    'descripcion' => $productObj->getDescripcion(),
                    'imagen' => $productObj->getImagen(),
                    'stock' => $productObj->getStock()
                ];
                require_once 'views/layout/header.php';
                // Aseguramos que la ruta sea correcta
                if (file_exists('views/product/show.php')) {
                    require_once 'views/product/show.php';
                } else {
                    echo "Error: La vista views/product/show.php no existe.";
                }
                require_once 'views/layout/footer.php';
            } else {
                header("Location: index.php?controller=product&action=index");
            }
        } else {
             header("Location: index.php?controller=product&action=index");
        }
    }
}
