<?php
class ProductController extends Controller
{
    // listar todos los productos
    public function index(): void
    {
        $productModel = new Product();
        $products = $productModel->getAllActiveProducts();

        $this->render('products/index', [
            'title'    => 'Menu',
            'products' => $products
        ]);
    }

    // mostrar producto
    public function show(): void
    {
        if (!isset($_GET['id'])) {
            $this->redirect('index.php?controller=product&action=index');
        }

        $id = (int)$_GET['id'];

        $productModel = new Product();
        $product = $productModel->getProductById($id);

        if (!$product) {
            $this->redirect('index.php?controller=product&action=index');
        }

        $this->render('products/show', [
            'title'   => $product['nombre'],
            'product' => $product
        ]);
    }
}
