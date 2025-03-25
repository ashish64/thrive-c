<?php

declare(strict_types=1);

namespace Src\Services;

use Src\Interfaces\ProductRepositoryInterface;
use Src\Repositories\ProductRepository;
use Src\ToolsClass;

/**
 * OrderService handles order-related calculations and processing.
 */
class OrderService extends ToolsClass
{
    /**
     * @var ProductRepositoryInterface $productRepository Instance of ProductRepositoryInterface for product data access.
     */
    public ProductRepositoryInterface $productRepository;

    /**
     * Constructor initializes ProductRepository.
     */
    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    /**
     * Calculates the cost of products in the order.
     *
     * @param array<string> $order Array of product codes in the order.
     *
     * @return array<array<string|float|int>> Array of product details with calculated costs.
     */
    public function calculateProductCost(array $order): array
    {
        $products = $this->productRepository->getAll();

        $quantity = array_count_values(
            array_filter(
                $order,
                function ($item) use ($products) {
                    return isset($products[$item]);
                }
            )
        );

        $result = [];

        foreach ($quantity as $item => $qty) {
            $result[] = [
                'product_code' => $item,
                'name' => $products[$item]['name'],
                'price' => $products[$item]['price'],
                'quantity' => $qty,
            ];
        }

        return $result;

    }

    /**
     * Calculates the delivery cost based on the pre-delivery cost.
     *
     * @param float|int $preDeliveryCost The cost of products before delivery.
     *
     * @return float|int The calculated delivery cost.
     */
    public function calculateDeliveryCost(float|int $preDeliveryCost): float|int
    {
        return match (true) {
            $preDeliveryCost < 50 => 4.95,
            $preDeliveryCost < 90 => 2.95,
            default => 0,
        };
    }

    /**
     * Calculates the total cost of the order, including delivery.
     *
     * @param array<array<string|float|int>> $productsTotal Array of product details with calculated costs.
     *
     * @return float The total cost of the order.
     */
    public function calculateTotalCost(array $productsTotal): float
    {
        $costBeforeShipping = 0;

        foreach ($productsTotal as $product) {
            // isolate R01 for test
            if ($product['product_code'] === 'R01') {
                $redFullPricedCount = ceil($product['quantity'] / 2);
                $redHalfPricedCount = floor($product['quantity'] / 2);

                $costBeforeShipping += ($redFullPricedCount * $product['price']) + ($redHalfPricedCount * $product['price'] * 0.5);
                continue;
            }

            $costBeforeShipping += $product['price'] * $product['quantity'];
        }

        $total = $costBeforeShipping + $this->calculateDeliveryCost($costBeforeShipping);

        return round($total, 2);
    }

    /**
     * Processes the shopping cart and calculates the total cost.
     *
     * @param array<string> $order Array of product codes in the order.
     *
     * @return array<string, array<array<string|float|int>>|float> Array containing product details and the grand total.
     */
    public function shoppingCart(array $order): array
    {
        $productsTotal = $this->calculateProductCost($order);
        $grandTotal = $this->calculateTotalCost($productsTotal);

        $cart['products'] = $productsTotal;
        $cart['grandTotal'] = $grandTotal;

        return $cart;
    }
}