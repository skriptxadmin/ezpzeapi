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
        ]
    ];

    $select = [
        'b.id',
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
        'b.opening_hours'
    ];

    $where = [
        'OR' => [
        'b.created_by' => $user_id,
        'b.owner_id' => $user_id,
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

        $keywordRows = $this->db->select(
            'business_keywords(bk)',
            [
                '[>]keywords(k)' => [
                    'bk.kid' => 'id'
                ]
            ],
            [
                'bk.bid',
                'k.slug',
                'k.keyword'
            ],
            [
                'bk.bid' => $businessIds
            ]
        );

        $keywordMap = [];

        foreach ($keywordRows as $row) {

            $keywordMap[$row['bid']][] = [
                'slug' => $row['slug'],
                'keyword' => $row['keyword']
            ];
        }

        foreach ($business as &$b) {

            $b['keywords'] =
                $keywordMap[$b['id']]
                ?? [];
        }
    }

    foreach($business as &$b){
        unset($b['id']);
    }

    return $this->json(
        compact('business')
    );
}
}