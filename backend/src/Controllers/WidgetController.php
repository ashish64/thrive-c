<?php
declare(strict_types = 1);

namespace Src\Controllers;

use Src\Interfaces\ProductRepositoryInterface;
use Src\Repositories\ProductRepository;
use Src\Services\OrderService;
use Src\ToolsClass;

class WidgetController extends ToolsClass {

    public ProductRepositoryInterface $productRepository;
    public OrderService $orderService;
    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->orderService = new OrderService();
    }

    /**
     * Returns all products
     * @return string
     */
    public function index(): string
    {
        return $this->ok($this->productRepository->getAll());
    }

    /**
     * Post product codes and returns calculated cart
     * @param array<string> $request
     * @return string
     */
    public function order(array $request): string
    {
        $order = $this->orderService->shoppingCart($request);

        return $this->ok($order);
    }



}