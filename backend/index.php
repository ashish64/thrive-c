<?php

require_once __DIR__ . '/vendor/autoload.php';

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

$app = new Src\RouterClass();

$app->destination($uri);

