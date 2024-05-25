<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
  public function getAllUsers() {
      $user = User::get()->toJson(JSON_PRETTY_PRINT);
      return response($user, 200);
  }

  public function createUser(Request $request) {
    $user = new User;
    $user->name = $request->name;
    $user->course = $request->password;
    $user->save();

    return response()->json([
        "message" => "user record created",
        "user" => $user
    ], 201);
  }

  public function getUser($id) {
    if (User::where('id', $id)->exists()) {
      $user = User::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
      return response($user, 200);
    } else {
      return response()->json([
        "message" => "user not found"
      ], 404);
    }
  }

  public function getUserAuth(Request $request) {
    $credentials = $request->only('email', 'password');

    if (User::where('email', $credentials['email'])->exists()) {
        $user = User::where('email', $credentials['email'])->first();        
        return response()->json($user, 200);
    } 

    return response()->json([
        "message" => "Unauthorized - Invalid credentials"
    ], 401);

  }

  public function vinculaAlexa(Request $request) {
    $req = $request->only('userId', 'state');
    $email = $req['state'];
    $emailWithoutPrefix = substr($email, 2);
    var_dump($emailWithoutPrefix);
    $user = User::where('email', $emailWithoutPrefix)->first();
    if ($user) {
        $user->update([
            'alexaid' => $req['userId'],
        ]);

        if ($user->wasChanged()) {
            return response()->json([
                "message" => "Dados atualizados com sucesso",
                "user" => $user
            ], 200);
        } else {
            return response()->json([
                "message" => "Nenhum dado foi alterado",
            ], 200);
        }
    } else {
        return response()->json([
            "message" => "Usuário não encontrado com o email fornecido",
        ], 404);
    }
    
  }
  
  public function updateUser(Request $request, $id) {
    if (Student::where('id', $id)->exists()) {
        $user = User::find($id);
        $user->name = is_null($request->name) ? $user->name : $request->name;
        $user->course = is_null($request->password) ? $user->password : $request->password;
        $user->save();

        return response()->json([
            "message" => "records updated successfully",
            "user" => $user
        ], 200);
        } else {
        return response()->json([
            "message" => "user not found"
        ], 404);

    }
  }

  public function deleteStudent ($id) {
    if(User::where('id', $id)->exists()) {
      $user = User::find($id);
      $user->delete();

      return response()->json([
        "message" => "records deleted",
        "user" => $user
      ], 202);
    } else {
      return response()->json([
        "message" => "user not found"
      ], 404);
    }
  }
}
