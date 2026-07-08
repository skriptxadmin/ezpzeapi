<?php

namespace App\Controllers\Locations;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController extends Controller
{

    public function index(Request $request,Response $response,array $args): Response {


    }

    
    public function search(
        Request $request,
        Response $response,
        array $args
    ): Response {

        $query = trim(
            $request
                ->getQueryParams()['q']
                ?? ''
        );

        if (strlen($query) < 3) {

            return $this->json([
                'locations' => []
            ]);
        }

        $locations = $this->db->select(
            'loc_areas',
            [
                'slug',
                'area'
            ],
            [
                'area[~]' => $query,
                'LIMIT' => 20
            ]
        );

        return $this->json([
            'locations' => $locations
        ]);
    }
}