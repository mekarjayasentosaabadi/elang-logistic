<?php

use Illuminate\Support\Facades\Route;

if (!function_exists('listRoutes')) {
    function listRoutes()
    {
        $listRoutes = Route::getRoutes()->getRoutes();
        $dataUrl = [];
        foreach ($listRoutes as $url) {
            //buat array dengan key route name dan value uri
            $dataUrl[$url->getName()] = $url->uri();
        }
        echo json_encode($dataUrl);
    }
}
