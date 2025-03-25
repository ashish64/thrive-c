<?php

declare(strict_types=1);

namespace Src\Controllers;

use Src\Interfaces\ProductRepositoryInterface;
use Src\Repositories\ProductRepository;
use Src\Services\OrderService;
use Src\ToolsClass;

/**
 * WidgetController handles product and order related requests.
 *
 * This controller utilizes ProductRepository and OrderService to fetch product data
 * and process orders. It extends ToolsClass for common utility functions.
 */
class WidgetController extends ToolsClass
{
    /**
     * @var ProductRepositoryInterface $productRepository Instance of ProductRepositoryInterface for product data access.
     */
    public ProductRepositoryInterface $productRepository;

    /**
     * @var OrderService $orderService Instance of OrderService for order processing.
     */
    public OrderService $orderService;

    /**
     * Constructor initializes ProductRepository and OrderService.
     */
    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->orderService = new OrderService();
    }

    /**
     * Retrieves all products and returns them as a JSON response.
     *
     * @return string JSON string containing all products.
     */
    public function index(): string
    {
        return $this->ok($this->productRepository->getAll());
    }

    /**
     * Processes an order based on product codes provided in the request.
     *
     * @param array<string> $request Array of product codes to be ordered.
     *
     * @return string JSON string containing the calculated order details.
     */
    public function order(array $request): string
    {
        $order = $this->orderService->shoppingCart($request);

        return $this->ok($order);
    }
}