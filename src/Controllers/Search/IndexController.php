<?php

namespace App\Controllers\Search;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class IndexController extends Controller{
public function index(
    Request $request,
    Response $response,
    array $args
): Response {

    $locationSlug = $args['locationSlug'];
    $keyword = trim($args['keyword']);

    /*
    |--------------------------------------------------------------------------
    | Location ID
    |--------------------------------------------------------------------------
    */

    $locationId = $this->db->get(
        'loc_areas',
        'id',
        [
            'slug' => $locationSlug
        ]
    );

    if(!$locationId){

        return $this->json([
            'business' => []
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Keyword ID
    |--------------------------------------------------------------------------
    */

    $keywordId = $this->db->get(
        'keywords',
        'id',
        [
            'keyword[~]' => $keyword
        ]
    );

    if(!$keywordId){

        return $this->json([
            'business' => []
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Business IDs
    |--------------------------------------------------------------------------
    */

    $businessIds = $this->db->select(
        'business_keywords',
        'bid',
        [
            'kid' => $keywordId
        ]
    );

    if(empty($businessIds)){

        return $this->json([
            'business' => []
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Businesses
    |--------------------------------------------------------------------------
    */

    $join = [

        '[>]categories(c1)' => [
            'b.category_id' => 'id'
        ],

        '[>]categories(c2)' => [
            'b.subcategory_id' => 'id'
        ]

    ];

    $select = [

        'b.id',

        'b.slug',

        'b.bname',

        'b.description',

        'b.image',

        'b.contact',

        'c1.title(categoryTitle)',

        'c2.title(subcategoryTitle)'

    ];

    $where = [

        'b.id' => $businessIds,

        'b.location_id' => $locationId,

        'b.deleted_at' => null

    ];

    $business = $this->db->select(
        'businesses(b)',
        $join,
        $select,
        $where
    );

    foreach($business as &$b){

        unset($b['id']);

    }

    return $this->json(
        compact('business')
    );
}
}