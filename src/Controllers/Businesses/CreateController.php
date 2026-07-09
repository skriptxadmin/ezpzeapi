<?php

namespace App\Controllers\Businesses;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateController extends Controller
{
    public function create(Request $request, Response $response,array $args): Response {
       
        $userId = $request->getAttribute(
            'user_id'
        );

        if (!$userId) {

            return $this->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $category = $this->db->get(
            'categories',
            ['id'],
            [
                'slug' => $data['category'],
                'deleted_at' => null
            ]
        );

        $subcategory = $this->db->get(
            'categories',
            ['id'],
            [
                'slug' => $data['subcategory'],
                'deleted_at' => null
            ]
        );

        if (
            !$category ||
            !$subcategory
        ) {

            return $this->json([
                'error' =>
                    'Invalid category or subcategory'
            ], 400);
        }

        $db = new App\Helpers\DB();
        $businessSlug = $db->create_slug('businesses', $data['bname']);

        $locationId = null;

        if(!empty($data['location'])){

            $location =
                $this->db->get(
                    'loc_areas',
                    [
                        'id'
                    ],
                    [
                        'slug' =>
                            $data['location']
                    ]
                );

            $locationId =
                $location['id']
                ?? null;
        }

        $this->db->insert(
            'businesses',
            [
                'slug' =>
                    $businessSlug,

                'bname' =>
                    $data['bname'],

                'description' =>
                    $data['description'],

                'image' => !empty($data['image'])
                    ? $data['image']
                    : null,

                'category_id' =>
                    $category['id'],

                'subcategory_id' =>
                    $subcategory['id'],

                'contact' =>
                    $data['contact'],

                'whatsapp' =>
                    $data['whatsapp'],

                'website' =>
                    $data['website'],

                'opening_hours' =>
                    $data['opening_hours'],

                'location_id' =>
                    $locationId,

                'created_by' =>
                    $userId,
            ]
        );

        $bid = $this->db->id();

        $keywords = explode(
            ',',
            $data['keywords']
        );

        foreach (
            $keywords as $keywordSlug
        ) {

            $keywordSlug = trim(
                strtolower(
                    $keywordSlug
                )
            );

            if (!$keywordSlug) {
                continue;
            }

            $keyword = $this->db->get(
                'keywords',
                ['id'],
                [
                    'slug' =>
                        $keywordSlug,
                    'deleted_at' =>
                        null
                ]
            );

            if ($keyword) {

                $kid =
                    $keyword['id'];

            } else {

                $this->db->insert(
                    'keywords',
                    [
                        'slug' =>
                            $keywordSlug,

                        'keyword' =>
                            ucwords(
                                str_replace(
                                    '-',
                                    ' ',
                                    $keywordSlug
                                )
                            ),

                        'created_by' =>
                            $userId,
                    ]
                );

                $kid =
                    $this->db->id();
            }

            $this->db->insert(
                'business_keywords',
                [
                    'bid' =>
                        $bid,

                    'kid' =>
                        $kid,

                    'created_by' =>
                        $userId,
                ]
            );
        }

        return $this->json([
            'message' =>
                'Business created successfully'
        ]);
    }

 
}