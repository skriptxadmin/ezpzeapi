<?php
namespace App\Controllers\User;

use App\Controllers\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RefreshController extends Controller
{

    public function index(Request $request, Response $response, array $args): Response
    {
       $validator = new \App\Helpers\Validator();
        $data      = $request->getParsedBody();
        $rules     = [
            'refresh' => 'required|string',
        ];
        $messages = [

        ];
        $validationResult = $validator->make($data, $rules, $messages);
        if ($validationResult !== true) {
            return $this->json(['errors' => $validationResult], 422);
        }
        $validData = $validator->validData;

         $jwt = new \App\Helpers\JWT;

        $decoded = $jwt->decode($validData->refresh);

         if (is_string($decoded)) {

            return $this->json(["error"=>$decoded],422);
        }

        if (empty($decoded->data->username)) {

            return $this->json(['error'=>"Unidentified user"], 422);
        }


        $refresh_user_id = $this->db->get('users', 'id', ['username' => $decoded->data->username]);



 if (empty($refresh_user_id)) {
    return $this->json(['error'=>'Unable to refresh token'], 422);
 }



       $user_id =  $request->getAttribute('user_id');

       if($user_id !== $refresh_user_id){

    return $this->json(['error'=>'Unable to refresh token'], 422);

       }

       $where = [
        'id' => $user_id
       ];

        $user = (object) $this->db->get('users', '*', $where);

        $role = $this->db->get('roles', 'slug', ['id' => $user->role_id]);

         $data = [
            'username' => $user->username,
            'role' => $role
        ];


        $token = $jwt->encode($data);


       
        return $this->json(['token' => $token]);

    }

}
