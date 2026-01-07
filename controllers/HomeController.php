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
        
        // Verificamos si hay un filtro por categoría
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;
        
        if ($categoryId > 0) {
            $productsObjects = $productDAO->getByCategory($categoryId);
        } else {
            $productsObjects = $productDAO->getAll();
        }
        
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
        
        // Limitamos a 6 para el home si hay muchos
        $products = array_slice($products, 0, 6);

        $this->render('home/index', [
            'title'      => 'Welcome to BurguerWagen',
            'products'   => $products,
            'categories' => $categories,
            'selectedCategory' => $categoryId
        ]);
    }
}
