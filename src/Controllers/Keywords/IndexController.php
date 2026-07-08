<?php

namespace App\Controllers\Keywords;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController extends Controller
{
     public function index(Request $request, Response $response, array $args): Response
    {
         $select = [
            
            'slug',
            'keyword',
        ];
         $where = [
            'deleted_at'=>null
        ];
        $keywords = $this->db->select('keywords', $select, $where);


        return $this->json(compact('keywords'));
    }


    public function search(
        Request $request,
        Response $response,
        array $args
    ): Response {

        $query =
            trim(
                $request
                ->getQueryParams()['q']
                ?? ''
            );

        if (
            strlen($query) < 3
        ) {
            return $this->json([
                'keywords' => []
            ]);
        }

        $keywords =
            $this->db->select(
                'keywords',
                [
                    'slug',
                    'keyword'
                ],
                [
                    'deleted_at' => null,
                    'keyword[~]' => $query,
                    'LIMIT' => 10
                ]
            );

        return $this->json(
            compact(
                'keywords'
            )
        );
    }

    
}