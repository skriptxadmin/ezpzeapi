<?php 

namespace App\Controllers\Home;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController extends Controller
{

    public function index(Request $request, Response $response, array $args): Response
    {
        $select = [
            'id',
            'slug',
            'title',
            'parent_id'
        ];

        $where = [
            'deleted_at'=>null,
        ];
       
        $categories = $this->db->select('categories', $select, $where);

       $parents = [];
$childrenMap = [];

// Single pass
foreach ($categories as $category) {

    if (empty($category['parent_id'])) {
        $category['children'] = [];
        $parents[$category['id']] = $category;
    } else {
        $childrenMap[$category['parent_id']][] = $category;
    }
}

// Attach children to parents
foreach ($parents as &$parent) {
    $parent['children'] = $childrenMap[$parent['id']] ?? [];
    unset($parent['id']);
    unset($parent['parent_id']);
    $parent['img'] = $_ENV['APP_URL'].'/images/placeholder-50x50.png';
    foreach($parent['children'] as &$child){
    $child['img'] = $_ENV['APP_URL'].'/images/placeholder-50x50.png';

        unset($child['id']);
        unset($child['parent_id']);
    }
}
unset($parent);

// If you need indexed array
$categories = array_values($parents);


    

        $heroads = [
  "http://localhost:9999/images/placeholder-600x400.png?v=1",
  "http://localhost:9999/images/placeholder-600x400.png?v=2",
  "http://localhost:9999/images/placeholder-600x400.png?v=3",
  "http://localhost:9999/images/placeholder-600x400.png?v=5",
  
];

        $featuredCategories = ['restaurants-food', 'healthcare', 'education-training', 'home-services'];

        $serviceCategories = ['automotive', 'real-estate'];


        return $this->json(compact('categories', 'heroads', 'featuredCategories', 'serviceCategories'));
    }

  
}
