<?php
require_once 'models/ProductDAO.php';

class HomeController extends Controller
{
    public function index(): void
    {
        // Usamos el DAO para obtener los datos
        $productDAO = new ProductDAO();
        
        // Obtenemos los productos (asumiendo que quieres mostrar 6 destacados o todos)
        // Nota: En la vista index.php espera un array de arrays o objetos. 
        // Si tu vista usa $p['nombre'], necesitarás adaptar la vista a $p->getNombre() 
        // o convertir los objetos a array aquí. Para mantener compatibilidad rápida:
        $productsObjects = $productDAO->getAll();
        
        // Convertimos objetos a array para que la vista actual (que usa $p['nombre']) no falle
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
        
        // Limitamos a 6 para el home si hay muchos
        $products = array_slice($products, 0, 6);

        $this->render('home/index', [
            'title'    => 'Welcome to BurguerWagen',
            'products' => $products
        ]);
    }
}
