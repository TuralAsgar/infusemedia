<?php

require_once 'classes/Database.php';
require_once 'classes/Image.php';
require_once 'Config.php';

function JSONResponse($data): void
{
    header('Content-Type: application/json');
    echo json_encode($data);
}