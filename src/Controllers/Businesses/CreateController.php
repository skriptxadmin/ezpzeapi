<?php

namespace App\Controllers\Businesses;

use App\Controllers\Controller;
use App\Helpers\DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CreateController extends Controller
{
    public function create(Request $request, Response $response, array $args): Response
    {
        $validator = new \App\Helpers\Validator();

        $data = $request->getParsedBody();

        $rules = [
            'bname' => 'required',
            'description' => 'required',
            'category' => 'required',
            'subcategory' => 'required',
            'contact' => 'required',
            'whatsapp' => 'required',
            'opening_hours' => 'required',
           
        ];

        $messages = [
            'bname.required' => 'Business name is required',
            'description.required' => 'Description is required',
            'category.required' => 'Category is required',
            'subcategory.required' => 'Subcategory is required',
            'contact.required' => 'Contact is required',
            'whatsapp.required' => 'Whatsapp is required',
            'opening_hours.required' => 'Opening hours is required',
            
        ];

        $validationResult = $validator->make($data, $rules, $messages);

        if ($validationResult !== true) {
            return $this->json(['errors' => $validationResult], 422);
        }

        $validData = $validator->validData;

        $userId = $request->getAttribute('user_id');

        if (!$userId) {
            return $this->json([
                'error' => 'Unauthorized'
            ], 401);
        }

        $category = $this->db->get(
            'categories',
            ['id'],
            [
                'slug' => $validData->category,
                'deleted_at' => null
            ]
        );

        $subcategory = $this->db->get(
            'categories',
            ['id'],
            [
                'slug' => $validData->subcategory,
                'deleted_at' => null
            ]
        );

        if (!$category || !$subcategory) {
            return $this->json([
                'error' => 'Invalid category or subcategory'
            ], 400);
        }

        $db = new DB();

        $businessSlug = $db->create_slug(
            'businesses',
            $validData->bname
        );

        $locationId = null;

        if (!empty($validData->location)) {

            $location = $this->db->get(
                'loc_areas',
                ['id'],
                [
                    'slug' => $validData->location
                ]
            );

            $locationId = $location['id'] ?? null;
        }

        $this->db->insert(
            'businesses',
            [
                'slug' => $businessSlug,
                'bname' => $validData->bname,
                'description' => $validData->description,
                'image' => !empty($validData->image)
                    ? $validData->image
                    : null,
                'category_id' => $category['id'],
                'subcategory_id' => $subcategory['id'],
                'contact' => $validData->contact,
                'whatsapp' => $validData->whatsapp,
                'website' => $validData->website ?? '',
                'opening_hours' => $validData->opening_hours,
                'location_id' => $locationId,
                'created_by' => $userId
            ]
        );

        $bid = $this->db->id();

        $keywordSlugs = array_filter(
            array_unique(
                array_map(
                    fn ($keyword) => trim(strtolower($keyword)),
                    explode(',', $validData->keywords)
                )
            )
        );

        if (!empty($keywordSlugs)) {

            $existingKeywords = $this->db->select(
                'keywords',
                ['id', 'slug'],
                [
                    'slug' => $keywordSlugs,
                    'deleted_at' => null
                ]
            );

            $existingMap = [];

            foreach ($existingKeywords as $keyword) {
                $existingMap[$keyword['slug']] = $keyword['id'];
            }

            $missingKeywords = array_diff(
                $keywordSlugs,
                array_keys($existingMap)
            );

            foreach ($missingKeywords as $keywordSlug) {

                $this->db->insert(
                    'keywords',
                    [
                        'slug' => $keywordSlug,
                        'keyword' => ucwords(
                            str_replace('-', ' ', $keywordSlug)
                        ),
                        'created_by' => $userId
                    ]
                );
            }

            $allKeywords = $this->db->select(
                'keywords',
                ['id', 'slug'],
                [
                    'slug' => $keywordSlugs,
                    'deleted_at' => null
                ]
            );

            foreach ($allKeywords as $keyword) {

                $this->db->insert(
                    'business_keywords',
                    [
                        'bid' => $bid,
                        'kid' => $keyword['id'],
                        'created_by' => $userId
                    ]
                );
            }
        }

        return $this->json([
            'message' => 'Business created successfully'
        ]);
    }
}

