<?php
require_once 'models/ProductDAO.php';
require_once 'models/CategoryDAO.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $productDAO = new ProductDAO();
        $categoryDAO = new CategoryDAO();
        
        // Obtenemos todas las categorías
        $categoriesObjects = $categoryDAO->getAll();
        $categories = [];
        foreach ($categoriesObjects as $cat) {
            $categories[] = [
                'id' => $cat->getId(),
                'nombre' => $cat->getNombre()
            ];
        }
        
        // Obtenemos todos los productos (el filtrado se hace en JS con .filter())
        $productsObjects = $productDAO->getAll();

        // Convertimos objetos a array para compatibilidad con la vista
        $products = [];
        foreach ($productsObjects as $p) {
            $products[] = [
                'id' => $p->getId(),
                'nombre' => $p->getNombre(),
                'precio' => $p->getPrecio(),
                'imagen' => $p->getImagen(),
                'descripcion' => $p->getDescripcion(),
                'categoria_id' => $p->getCategoriaId()
            ];
        }

        $this->render('home/index', [
            'title'      => 'Welcome to BurguerWagen',
            'products'   => $products,
            'categories' => $categories
        ]);
    }
}
