<?php

declare(strict_types=1);

namespace Src;

use Src\Controllers\WidgetController;

/**
 * RouterClass handles request routing and dispatches requests to the appropriate controller and method.
 */
class RouterClass extends ToolsClass
{
    /**
     * Dispatches the request to the appropriate controller and method based on the URL.
     *
     * @param string $request The requested URL.
     *
     * @return void
     */
    public function destination(string $request): void
    {
        $routedTo = $this->pointers($request);

        $controller = $routedTo[0];
        $method = $routedTo[1];

        if (!(class_exists($controller) && method_exists($controller, $method))) {
            $this->dumpThis("it does not exists");
        }

        $payload = json_decode(file_get_contents('php://input'), true);
        $controllerInstance = new $controller();
        $controllerInstance->$method($this->requestValidator($payload));
    }

    /**
     * Maps URLs to controller and method pairs.
     *
     * @param string $request The requested URL.
     *
     * @return array<string>|string The controller and method array, or an error response.
     */
    private function pointers(string $request): array|string
    {
        $routes = [
            'GET' => [
                '/' => [WidgetController::class, 'index'],
                '/about' => [WidgetController::class, 'about'],
            ],
            'POST' => [
                '/order' => [WidgetController::class, 'order'],
            ],
        ];

        $method = $this->getMethod();

        return $routes[$method][$request] ?? $this->doesntExist("This end point doesnt exist", 404);
    }

    /**
     * Retrieves the HTTP request method.
     *
     * @return string The HTTP request method.
     */
    private function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Validates and sanitizes the request payload.
     *
     * @param mixed $request The request payload.
     *
     * @return array<string>|null The sanitized data array, or null if validation fails.
     */
    private function requestValidator(mixed $request): ?array
    {
        // if post data is empty
        if (empty($request)) {
            $this->dumpThis("Payload is empty");
        }

        // if request doesnt appear to be an array
        if (!is_array($request)) {
            $this->dumpThis("Payload processing failed");
        }

        $sanitizedData = [];

        foreach ($request as $product) {
            if (!is_string($product)) {
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