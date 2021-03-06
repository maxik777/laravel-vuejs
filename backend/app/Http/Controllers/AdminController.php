<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function index()
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
}
