<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;
use App\Models\Music;
use App\Models\User;
use Validator;

class LikeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likes = Like::all();

        return response([
            "likes" => $likes,
            "message" => "lajkovi pronadjeni",
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $user = User::find($request->user_id);
        $music = Music::find($request->music_id);

        if (!$user || !$music) {
            return response([
                'like' => null,
                'message' => 'Korisnik/muzika nije pronadjena.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'music_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response([
                'like' => null,
                'message' => 'Neuspesna validacija.',
                'errors' => $validator->messages(),
            ], 400);
        }

        $like = new Like();

        $like->user_id = auth()->user()->id;
        $like->music_id = $request->music_id;

        $like->save();

        return response([
            "like" => $like,
            "message" => "Kreiran lajk.",
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $like = Like::find($id);

        if (!$like) {
            return response([
                'like' => null,
                'message' => 'lajk nije pronadjen.',
            ], 404);
        }

        if (auth()->user()->id != $like->user_id) {
            return response([
                "like" => $like,
                "message" => "Neautorizovano.",
            ], 401);
        }

        $like->delete();

        return response([
            "like" => $like,
            "message" => "lajk obrisan.",
        ], 200);
    }
}
