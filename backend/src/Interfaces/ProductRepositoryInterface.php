<?php


namespace Src\Interfaces;
interface ProductRepositoryInterface
{

    /**
     * @return array[]
     */
    public function getAll(): array;
}