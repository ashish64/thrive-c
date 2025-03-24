<?php
declare(strict_types = 1);

namespace Src\Controllers;

use Src\Interfaces\ProductRepositoryInterface;
use Src\Repositories\ProductRepository;
use Src\ToolsClass;

class WidgetController extends ToolsClass {

    public ProductRepositoryInterface $productRepository;
    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    /**
     * Returns all products
     * @return string
     */
    public function index(): string
    {
        return $this->ok($this->productRepository->getAll());
    }

    public function order() {
        return "order";
    }
}