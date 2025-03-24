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
}