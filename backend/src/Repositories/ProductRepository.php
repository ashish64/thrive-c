<?php

namespace Src\Repositories;

use Src\Interfaces\ProductRepositoryInterface;

/**
 * ProductRepository implements ProductRepositoryInterface and provides access to product data.
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Retrieves all products and their details.
     *
     * @return array<string, array<string, string>> An associative array of products.
     */
    public function getAll(): array
    {
        return [
            'R01' => [
                'name' => 'R01',
                'price' => '32.95',
            ],
            'G01' => [
                'name' => 'Green Widget',
                'price' => '24.95',
            ],
            'B01' => [
                'name' => 'Blue Widget',
                'price' => '7.95',
            ],
        ];
    }
}