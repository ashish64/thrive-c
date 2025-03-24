<?php
declare(strict_types = 1);

namespace Src;

use Src\Controllers\WidgetController;

class RouterClass {


    public function destination(string $request): void
    {
        $routedTo = $this->pointers($request);

        $controller = $routedTo[0];
        $method = $routedTo[1];

        if(!(class_exists($controller) && method_exists($controller, $method))) {
            $this->dumpThis("it does not exists");
        }
        $payload =  json_decode(file_get_contents('php://input'), true);
        $controllerInstance = new $controller();
        $controllerInstance->$method($this->requestValidator($payload));

    }

    /**
     * @param string $request
     * @return string|string[]
     */
    private function pointers(string $request)
    {

        $routes = [
            'GET' => [
                '/' => [WidgetController::class, 'index'],
            ],
            'POST' => [
                '/order' => [WidgetController::class, 'order']
            ]
        ];

        $method = $this->getMethod();

        return $routes[$method][$request] ?? $this->doesntExist("This end point doesnt exist", 404);

    }

    /**
     * @return mixed
     */
    private function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @param mixed $request
     * @return array|null
     */
    private function requestValidator(mixed $request): array| null
    {
        // if post data is empty
        if($request === null || empty($request))
        {
            return null;
        }

        // if request doesnt appear to be an array
        if(!is_array($request))
        {
            $this->dumpThis("Payload processing failed");

        }

        $sanitizedData = [];

        foreach($request as $product)
        {
            if(!is_string($product))
            {
                $this->dumpThis("Product name should be string");
            }

            // Sanitize the string
            $sanitized = trim($product); // Remove whitespace
            $sanitized = strip_tags($sanitized); // Remove HTML/PHP tags
            $sanitized = htmlspecialchars($sanitized, ENT_QUOTES | ENT_HTML5, 'UTF-8'); // Convert special chars

            $sanitized = preg_replace('/[\x00-\x1F\x7F]/u', '', $sanitized);

            // Verify the item is not empty after sanitization
            if (empty($sanitized)) {
                $this->dumpThis("Item is empty after sanitization");
            }

            $sanitizedData[] = $sanitized;

        }

        return $sanitizedData;



    }

}