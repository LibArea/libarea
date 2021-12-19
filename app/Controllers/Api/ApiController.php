<?php

namespace App\Controllers\Api;

use Hleb\Scheme\App\Controllers\MainController;
use App\Models\{FacetModel, WebModel};

class ApiController extends MainController
{
    public function index()
    {
        return true;
    }

    public function topics()
    {
        $data = FacetModel::getFacetsAll(1, 25, 0, 'all');

        header('Content-Type: application/json');

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }

    public function items()
    {
        $data = WebModel::getItemsAll(1, 25, 0);

        header('Content-Type: application/json');

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
