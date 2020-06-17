<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $all_posts = DB::table('posts')->orderByRaw('id DESC')->get();
        return response()->json($all_posts);

    }

    public function edit(Request $request, $id)
    {
        // validate request
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        return DB::table('posts')->where('id', $id)->update([
            'name' => $request->name,
            'description' => $request->description,
            'created_at'=>date("Y-m-d H:i:s", time())
        ]);
    }

    public function add(Request $request)
    {
        // validate request
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);
        return DB::table('posts')->insert(
            ['name' => $request->name, 'description' => $request->description, 'created_at'=>date("Y-m-d H:i:s", time())]

        );
    }

    public function delete($id)
    {
        DB::table('posts')->where('id', $id)->delete();
        return response()->json('success',200);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;

        $user = Auth::user();
        $response = [
            'user' => $user,
            'token' => $token
        ];


        return response($response, 201);
    }
}
