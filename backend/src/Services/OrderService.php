<?php
namespace Src\Services;

use Src\Interfaces\ProductRepositoryInterface;
use Src\Repositories\ProductRepository;
use Src\ToolsClass;

class OrderService extends ToolsClass {
    public ProductRepositoryInterface $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    /**
     * @param array $order
     * @return array
     */
    public function calculateProductCost(array $order): array
    {

        $products =  $this->productRepository->getAll();


        $quantity = array_count_values(
            array_filter(
                $order,
                function($item) use ($products) {
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
     * @param float|int $preDeliveryCost
     * @return float|int
     */
    public function calculateDeliveryCost(float|int $preDeliveryCost): float|int
    {
        $deliveryCost = match(true) {
            $preDeliveryCost < 50 => 4.95,
            $preDeliveryCost < 90 => 2.95,
            default => 0
        };

        return $deliveryCost;
    }

    /**
     * @param array $productsTotal
     * @return float
     */
    public function calculateTotalCost(array $productsTotal): float
    {
        $costBeforeShipping = 0;

        foreach ($productsTotal as $product) {
            // isolate R01 for test
            if($product['product_code'] === 'R01')
            {
                $redFullPricedCount = ceil($product['quantity']/2);
                $redHalfPricedCount = floor($product['quantity']/2);

                $costBeforeShipping += ($redFullPricedCount * $product['price']) + ($redHalfPricedCount * $product['price'] * 0.5);
                continue;
            }

            $costBeforeShipping += $product['price']*$product['quantity'];

        }

        $total =  $costBeforeShipping + $this->calculateDeliveryCost($costBeforeShipping);
        return round($total, 2);
    }

    /**
     * @param array $order
     * @return array
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