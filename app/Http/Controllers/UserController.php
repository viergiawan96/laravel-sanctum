<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserController extends Controller
{
    public function index()
    {
        $user = request()->user(); //GET USER LOGIN
        //JIKA USER MEMILIKI ABILITIES USER:INDEX
        if ($user->tokenCan('user:index')) {
        //MAKA DATANYA AKAN DITAMPILKAN
        $users = User::orderBy('created_at', 'DESC')->paginate(10);
        return response()->json(['status' => 'success', 'data' =>$users]);
    }
    //JIKA TIDAK, MAKA BERIKAN RESPON UNAUTHORIZED
    return response()->json(['status' => 'failed', 'data' => 'Unauthorized']);
    }
    
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
            'type' => 'required'
        ]);
    
        $user = User::where('email', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'failed', 'data' => 'Password Anda Salah']);
        }
    
        //JADI, JIKA DIA ADMIN, MAKA MEMILIKI DUA ABILITY, MELIHAT DAN NEMBAHKAN. JIKA DIA USER MAKA HANYA BISA MELIHAT DATA USER.
        $abilities = strtoupper($user->role) == 'ADMIN' ? ['user:index', 'user:create']:['user:index'];
        return response()->json([
            'status' => 'success', 
            //LALU PADA METHOD createToken(), TAMBAHKAN PARAMETER ABILITIESNYA
            'data' => $user->createToken($user->name, $abilities)->plainTextToken
        ]);
    }
    public function store(Request $request)
        {
            //VALIDASI DATA YANG AKAN DITAMBAHKAN
            $this->validate($request, [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required',
                'role' => 'required',
            ]);

            $user = $request->user(); //GET USER LOGIN
            //CEK ABILITIES DARI USER TERSEBUT
            if ($user->tokenCan('user:create')) {
                //JIKA IYA, MAKA BUAT DATA BARU
                User::create($request->all());
                return response()->json(['status' => 'success']);
            }
            //JIKA GAGAL, BERIKAN RESPON GAGAL
            return response()->json(['status' => 'faield', 'data' => 'Unauthorized']);
        }
    public function getAllUserToken()
        {
            $users = request()->user();
            return response()->json([
                'status' => 'success',
                'data' => $users->tokens
            ]);
        }
    public function revokeToken()
        {
            $user = request()->user(); //GET USER LOGIN
            //JIKA TOKEN ID NYA ADA
            if (request()->token_id) {
                //MAKA HAPUS BERDASARKAN ID TOKEN TERSEBUT
                $user->tokens()->where('id', request()->token_id)->delete();
                return response()->json(['status' => 'success']);
            }
            //SELAIN ITU, HAPUS SEMUA DATA TOKEN
            $user->tokens()->delete();
            return response()->json(['status' => 'success']);
        }
}
