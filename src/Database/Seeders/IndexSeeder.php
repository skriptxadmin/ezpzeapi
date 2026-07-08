<?php
namespace App\Database\Seeders;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IndexSeeder
{

    public function index(Request $request, Response $response, array $args): Response
    {

        $path = __DIR__ . '/seeds/*.php'; // folder path + pattern

        $files = glob($path);

        $dbconn = new \App\Helpers\DB;

        foreach ($files as $file) {

            $filename = pathinfo($file, PATHINFO_FILENAME);

            $values = require __DIR__ . '/./seeds/' . $filename . '.php';

            if (! empty($values)) {

                $dbconn->db->insert($filename, $values);

            }

        }

        $html = "Seeding Successful";

        $response->getBody()->write($html);

        return $response;
    }


     public function area(Request $request, Response $response, array $args): Response
    {

    
$data = require __DIR__.'/./areas.php';

$chunks = array_chunk($data, 1000);
        $dbconn = new \App\Helpers\DB;

foreach ($chunks as $rows) {
 $dbconn->db->insert('loc_areas', $rows);
}

        $html = "Areas Seeding Successful";

        $response->getBody()->write($html);

        return $response;

    }

}
