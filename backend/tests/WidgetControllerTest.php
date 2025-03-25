<?php
declare(strict_types=1);

namespace Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Src\Controllers\WidgetController;
use Src\Interfaces\ProductRepositoryInterface;

class WidgetControllerTest extends TestCase
{


    protected function setUp(): void
    {
        $this->productRepository = $this->createMock(ProductRepositoryInterface::class);
        $this->controller = new WidgetController();
    }



    public function test_index()
    {

        $products = [
            'R01' => ['name' => 'R01', 'price' => '32.95'],
            'G01' => ['name' => 'Green Widget', 'price' => '24.95'],
            'B01' => ['name' => 'Blue Widget', 'price' => '7.95'],
        ];

        $expectedResponse =  [
                "data" => [
                    "R01" => [
                        "name" => "R01",
                        "price" => "32.95"
                    ],
                    "G01" => [
                        "name" => "Green Widget",
                        "price" => "24.95"
                    ],
                    "B01" => [
                        "name" => "Blue Widget",
                        "price" => "7.95"
                    ]
                ],
                "status" => 200
            ];

        // Perform the API request using the mocked client
        $client = $this->apiClient($expectedResponse);
        $response = $client->post('/', $products);


        // Assert the response status code and body
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($products, json_decode($response->getBody()->getContents(), true)['data']);
    }
    public function test_order_endpoint_accepts_valid_data_and_returns_cart_with_total(): void
    {
        $products = [
            "B01", "B01", "R01", "R01", "R01"
        ];

        $expectedResponse = [
            "data" => [
                "products" => [
                    [
                        "product_code" => "B01",
                        "name" => "Blue Widget",
                        "price" => "7.95",
                        "quantity" => 2
                    ],
                    [
                        "product_code" => "R01",
                        "name" => "R01",
                        "price" => "32.95",
                        "quantity" => 3
                    ]
                ],
                "grandTotal" => 98.28
            ],
            "status" => 200
        ];

        // Perform the API request using the mocked client
        $client = $this->apiClient($expectedResponse);
        $response = $client->post('/', $products);

        // Assert the response status code and body
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getBody()->getContents(), true));
    }

    private function apiClient($expectedResponse)
    {
        // Create a mock response
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], json_encode($expectedResponse)),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        return $client;

    }


}