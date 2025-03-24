<?php
namespace Src\Repositories;

use Src\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface {

    /**
     * @return array[]
     */
    public function getAll(): array
    {
        return [
            'R01' => [
                'name' => 'R01',
                'price' => '32.95'
            ],
            'G01' =>  [
                'name' => 'Green Widget',
                'price' => '24.95'
            ],
            'B01' =>   [
                'name' => 'Blue Widget',
                'price' => '7.95'
            ],
        ];
    }




}