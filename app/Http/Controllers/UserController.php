<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'status' => 'success',
            'code' => 200,
            'message' => 'Lista de categorias',
            'list' => User::all()
        );

        return response()->json($data,$data['code']);
    }

    public function store(Request $request)
    {   
        $validate = Validator::make($request->all(), User::$create_rules,User::$messages);

        if($validate->fails()){ // Falla la validacion
            $data = array(
                'status' => 'error',
                'code' => 200,
                'message' => $validate->errors()
            );
        }else{  // Los datos son valido

            $user = User::create([
                'name' => $request->get('name'),
                'surname' => $request->get('surname'),
                'role' => $request->get('role'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'api_token' => Str::random(80),
            ]);

            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'El usuario se creo OK',
                'user' => $user
            );
        }
        return response()->json($data,$data['code']);
    }

    public function getToken(){
        
        $data = array(
            'status' => 'success',
            'code' => 200,
            'token' => Auth::user()->api_token
        );

        return response()->json($data,$data['code']);
    }

    public function show(User $user){
        // $user->api_token = null;
        $data = array(
            'status' => 'success',
            'code' => 200,
            'user' => $user
        );

        return response()->json($data,$data['code']);
    }

    public function update(Request $request, User $user){
        $rules = Arr::add(User::$update_rules, 'email', 'required|string|email|unique:users,email,'.$user->id);

        $validate = Validator::make($request->all(), $rules,User::$messages);

        if($validate->fails()){ // Falla la validacion
            $data = array(
                'status' => 'error',
                'code' => 200,
                'message' => $validate->errors()
            );
        }else{  // Los datos son valido
            $user->name = $request->get('name');
            $user->surname = $request->get('surname');
            $user->email = $request->get('email');
            $user->role = $request->get('role');
            $user->password = Hash::make($request->get('password'));
            $user->api_token = Str::random(80);
            $user->save();

            $data = array(
                'status' => 'success',
                'code' => 200,
                'message' => 'El usuario se modifico OK',
                'user' => $user
            );
        }
        return response()->json($data,$data['code']);
    }

    public function destroy(User $user){
        
        $user->delete();

        $data = array(
            'status' => 'success',
            'code' => 200,
            'user' => $user
        );

        return response()->json($data,$data['code']);
    }
}