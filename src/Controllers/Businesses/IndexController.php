<?php

namespace App\Controllers\Businesses;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController extends Controller
{
     public function index(Request $request, Response $response, array $args): Response
    {

        return $this->json([]);
    }

    public function my(Request $request,Response $response,array $args): Response {

    $user_id = $request->getAttribute('user_id');

    $join = [
        '[>]categories(c1)' => [
            'b.category_id' => 'id'
        ],
        '[>]categories(c2)' => [
            'b.subcategory_id' => 'id'
        ],
      
    ];

    $select = [
        'b.id',
        'b.slug',
        'b.bname',
        'b.description',
        'b.image',
        'b.contact',
      


        'c1.title(categoryTitle)',
         
          
        'c2.title(subcategoryTitle)',
     
      
    ];

    $where = [
        'OR' => [
        'b.created_by' => $user_id,
      
        ],
       
        'b.deleted_at' => null
    ];

    $business = $this->db->select(
        'businesses(b)',
        $join,
        $select,
        $where
    );

    if (!empty($business)) {

        $businessIds = array_column(
            $business,
            'id'
        );    
    }

    foreach($business as &$b){
        unset($b['id']);
    }

    return $this->json(
        compact('business')
    );
}

public function business(Request $request, Response $response, array $args): Response
{

    $slug = $args['slug'];
    $join = [
        '[>]categories(c1)' => ['category_id' => 'id'],
        '[>]categories(c2)' => ['subcategory_id' => 'id'],
        '[>]loc_areas(l)' => ['location_id' => 'id'],
        '[>]loc_taluks(t)' => ['l.taluk_id' => 'id'],
        '[>]loc_districts(d)' => ['t.district_id' => 'id'],
        '[>]loc_states(s)' => ['d.state_id' => 'id'],
        '[>]loc_pincodes(p)' => ['l.pincode_id' => 'id']
    ];
    $select = [

        'b.slug',
        'b.bname',
        'b.description',
        'b.image',

        'c1.title(categoryTitle)',
        'c1.slug(categorySlug)',

        'c2.title(subcategoryTitle)',
        'c2.slug(subcategorySlug)',

        'b.contact',
        'b.whatsapp',
        'b.website',
        'b.opening_hours',

        'l.slug(locationSlug)',
        'l.area(locationArea)',

        't.taluk(locationTaluk)',

        'd.district(locationDistrict)',

        's.state(locationState)',

        'p.pincode(locationPincode)',
    ];
    $business = $this->db->get(
        'businesses(b)',
        $join,
        $select,
        [
            'b.slug' => $slug,
            'b.deleted_at' => null
        ]
    );

    if (!$business) {

        return $this->json([
            'error' => 'Business not found'
        ], 404);
    }

    $businessId = $this->db->get(
        'businesses',
        'id',
        [
            'slug' => $slug
        ]
    );

    /*
    |--------------------------------------------------------------------------
    | Keywords
    |--------------------------------------------------------------------------
    */

    $keywords = $this->db->select(
        'business_keywords(bk)',
        [
            '[>]keywords(k)' => [
                'bk.kid' => 'id'
            ]
        ],
        [
            'k.slug',
            'k.keyword'
        ],
        [
            'bk.bid' => $businessId
        ]
    );

    $business['keywords'] =
        $keywords ?: [];

    /*
    |--------------------------------------------------------------------------
    | Review Summary
    |--------------------------------------------------------------------------
    */

    $reviewCount = $this->db->count(
        'reviews',
        [
            'b_id' => $businessId,
            'deleted_at' => null
        ]
    );

    $averageRating = $this->db->avg(
        'reviews',
        'rating',
        [
            'b_id' => $businessId,
            'deleted_at' => null
        ]
    );
    $business['averageRating'] = $averageRating ? round($averageRating, 1) : 0;
    $business['reviewCount'] = (int) $reviewCount;

    /*
    |--------------------------------------------------------------------------
    | Reviews
    |--------------------------------------------------------------------------
    */

    $reviews = $this->db->select(
        'reviews(r)',
        [
            '[>]users(u)' => [
                'r.user_id' => 'id'
            ]
        ],
        [
            'u.username',
            'r.rating',
            'r.comment',
            'r.created_at'
        ],
        [
            'r.b_id' => $businessId,
            'r.deleted_at' => null,
            'ORDER' => [
                'r.created_at' => 'DESC'
            ]
        ]
    );

    $business['reviews'] =
        $reviews ?: [];

    $business['location'] = implode(
        ', ',
        array_filter([
            $business['locationArea'] ?? null,
            $business['locationTaluk'] ?? null,
            $business['locationDistrict'] ?? null,
            $business['locationState'] ?? null,
            $business['locationPincode'] ?? null,
        ])
    );

    unset(
      
        $business['locationTaluk'],
        $business['locationDistrict'],
        $business['locationState'],
        $business['locationPincode']
    );

    return $this->json(
        compact('business')
    );
}
}