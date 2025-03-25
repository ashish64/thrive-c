<?php

namespace Src;

/**
 * ToolsClass provides utility methods for common operations like debugging and response formatting.
 */
class ToolsClass
{
    /**
     * Dumps a variable's contents with styled output and terminates script execution.
     *
     * @param mixed $payload The variable to dump.
     *
     * @return void
     */
    protected function dumpThis($payload): void
    {
        echo "<pre style='
        background-color: #020618;
        color: #7ccf00;
        '>";
        var_dump($payload);
        echo "</pre>";

        die();
    }

    /**
     * Returns a successful response with a 200 status code.
     *
     * @param mixed $message The data to include in the response.
     *
     * @return string JSON string containing the success response.
     */
    protected function ok($message): string
    {
        return $this->success($message, 200);
    }

    /**
     * Returns a generic successful response with an optional status code.
     *
     * @param mixed $message The data to include in the response.
     * @param int   $statusCode The HTTP status code (default: 200).
     *
     * @return string JSON string containing the success response.
     */
    protected function success($message, int $statusCode = 200): string
    {
        return $this->response([
            'data' => $message,
            'status' => $statusCode,
        ]);
    }

    /**
     * Returns a "not found" response with a 404 status code.
     *
     * @param string $message The error message.
     * @param int    $statusCode The HTTP status code (default: 404).
     *
     * @return string JSON string containing the error response.
     */
    protected function doesntExist(string $message, int $statusCode = 404): string
    {
        return $this->response([
            'message' => $message,
            'status' => $statusCode,
        ]);
    }

    /**
     * Formats and sends a JSON response with the provided payload and status code.
     *
     * @param array $payload The data to include in the JSON response.
     *
     * @return string JSON string containing the response.
     */
    protected function response(array $payload): string
    {
        header_remove();

        header("Content-Type: application/json; charset=UTF-8");

        http_response_code($payload['status']);

        echo json_encode($payload);

        exit;
    }
}