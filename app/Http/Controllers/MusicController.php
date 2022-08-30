<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Music;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class MusicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $musics = Music::all();

        return response([
            "musics" => $musics,
        ], 200);
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
        $category = Category::find($request->category_id);

        if (!$user || !$category) {
            return response([
                'music' => null,
                'message' => 'Korisnik/ kategorija nije pronadjena.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'category_id' => 'required|integer',
            'name' => 'required|string',
            'lyrics' => 'required|string',
            'tags' => 'required|string',
            'length' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response([
                'music' => null,
                'message' => 'Neuspela validacija.',
                'errors' => $validator->messages(),
            ], 400);
        }

        $music = new Music();

        $music->user_id = auth()->user()->id;
        $music->category_id = $request->category_id;
        $music->name = $request->name;
        $music->lyrics = $request->lyrics;
        $music->tags = $request->tags;
        $music->length = $request->length;

        $music->save();
        $music = $music->fresh(['user', 'category']);

        return response([
            "music" => $music,
            "message" => "kreirana muzika.",
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $music = Music::find($id);

        if (!$music) {
            return response([
                'music' => null,
                'message' => 'muzika nije pronadjena.',
            ], 404);
        }

        // VALIDATE DATA
        $validator = Validator::make($request->all(), [
        
            'name' => 'required|string',
            'lyrics' => 'required|string',
            'length' => 'required|numeric',



        ]);

        if ($validator->fails()) {
            return response([
                'music' => $music,
                'message' => 'Neuspela validacija.',
                'errors' => $validator->messages(),
            ], 400);
        }

        $music->name = $request->name;
        $music->lyrics = $request->lyrics;
        $music->length = $request->length;
        $music->save();

        return response([
            "music" => $music,
            "message" => "muzika azurirana.",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $music = Music::find($id);

        if (!$music) {
            return response([
                'music' => null,
                'message' => 'muzika nije pronadjena.',
            ], 404);
        }

        if (auth()->user()->id != $music->user_id) {
            return response([
                "music" => $music,
                "message" => "Neautorizovano.",
            ], 401);
        }

        $music->delete();

        return response([
            "music" => $music,
            "message" => "muzika obrisana.",
        ], 200);
    }
}
