<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $productModel = new Product();
        $products = $productModel->getAllActiveProducts();

        $this->render('home/index', [
            'title'    => 'Welcome to BurguerWagen',
            'products' => $products
        ]);
    }
}
