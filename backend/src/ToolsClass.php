<?php
namespace Src;

class ToolsClass {
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


    protected function ok($message)
    {
        return $this->success($message, 200);
    }

    protected function success($message, $statusCode = 200): string
    {
        return $this->response([
            'data' => $message,
            'status' => $statusCode
        ]);
    }

    protected function doesntExist($message, $statusCode = 404): string
    {
        return $this->response([
            'message' => $message,
            'status' => $statusCode
        ]);
    }


    protected function response(array $payload): string
    {
        header_remove();

        header("Content-Type: application/json; charset=UTF-8");

        http_response_code($payload['status']);

        echo json_encode($payload);

        exit;
    }
}