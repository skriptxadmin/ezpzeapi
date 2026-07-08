<?php 

namespace App\Controllers\Categories;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexController extends Controller
{

    public function index(Request $request, Response $response, array $args): Response
    {
        $select = [
            
            'slug',
            'title',
            'parent_id'
        ];

        $where = [
            'deleted_at'=>null
        ];
       
        $categories = $this->db->select('categories', $select, $where);

        foreach($categories as &$category){
            if(!empty($category['slug'])){
                // $category['img'] = $_ENV['APP_URL'].'/images/'.$category['slug'];
                $category['img'] = $_ENV['APP_URL'].'/images/placeholder-50x50.png';
            }
        }

        return $this->json(compact('categories'));
    }

  
}
