<?php

namespace App\Controllers\Businesses;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UpdateController extends Controller
{
    public function update(
        Request $request,
        Response $response,
        array $args
    ): Response {

        $userId =
            $request->getAttribute(
                'user_id'
            );

        $slug =
            $args['slug'];

        $business =
            $this->db->get(
                'businesses',
                [
                    'id',
                    'created_by',
                    'image'
                ],
                [
                    'slug' => $slug,
                    'deleted_at' => null
                ]
            );

        if (!$business) {

            return $this->json([
                'error' =>
                    'Business not found'
            ], 404);
        }

        if (
            $business['created_by']
            !=
            $userId
        ) {

            return $this->json([
                'error' =>
                    'Unauthorized'
            ], 403);
        }

        $data =
            $request->getParsedBody();

        $category =
            $this->db->get(
                'categories',
                ['id'],
                [
                    'slug' =>
                        $data['category'],
                    'deleted_at' =>
                        null
                ]
            );

        $subcategory =
            $this->db->get(
                'categories',
                ['id'],
                [
                    'slug' =>
                        $data['subcategory'],
                    'deleted_at' =>
                        null
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


            $locationId = null;

            if(!empty($data['location'])){

                $location =
                    $this->db->get(
                        'loc_areas',
                        ['id'],
                        [
                            'slug' =>
                                $data['location']
                        ]
                    );

                $locationId =
                    $location['id']
                    ?? null;
            }

        $this->db->update(
            'businesses',
            [
                'bname' =>
                    $data['bname'],

                'description' =>
                    $data['description'],

              'image' =>
                isset($data['image'])
                    ? (!empty($data['image'])
                        ? $data['image']
                        : null)
                    : $business['image'],

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
                    $locationId
                    ?? $business['location_id'],

                'updated_by' =>
                    $userId,
            ],
            [
                'id' =>
                    $business['id']
            ]
        );

        $this->db->delete(
            'business_keywords',
            [
                'bid' =>
                    $business['id']
            ]
        );

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
                            $userId
                    ]
                );

                $kid =
                    $this->db->id();
            }

            $this->db->insert(
                'business_keywords',
                [
                    'bid' =>
                        $business['id'],

                    'kid' =>
                        $kid,

                    'created_by' =>
                        $userId
                ]
            );
        }

        return $this->json([
            'message' =>
                'Business updated successfully'
        ]);
    }
}