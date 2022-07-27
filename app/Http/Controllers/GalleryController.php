<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
use App\Models\Image;

class GalleryController extends Controller
{
    public function index(Request $request)
    {

        $userId = $request->query('userId', '');
        $term = $request->query('term', '');
        $galleries = Gallery::searchByTerm($term, $userId)
            ->latest()
            ->paginate(10);

        return response()->json($galleries);
    }

    public function show($id)
    {
        $gallery = Gallery::with(['images', 'user', 'comments', 'comments.user'])->find($id);
        return response()->json($gallery);
    }

    public function store(CreateGalleryRequest $request)
    {
        $data = $request->validated();
        $gallery = Gallery::create([
            'user_id' => Auth::user()->id,
            'title' => $data['title'],
            'description' => $data['description']
        ]);

        $imagesArr = [];
        foreach ($data['images'] as $image) {
            $imagesArr[] = Image::create([
                'gallery_id' => $gallery->id,
                'url' => $image
            ]);
        }
        $gallery->load('images', 'user', 'comments', 'comments.user');

        return response()->json($gallery, 201);
    }

    public function update($id, UpdateGalleryRequest $request)
    {
        $data = $request->validated();
        $gallery = Gallery::findOrFail($id);
        $gallery->update($data);

        $imagesArr = [];
        foreach ($data['images'] as $image) {
            $imagesArr[] = Image::create([
                'gallery_id' => $gallery->id,
                'url' => $image
            ]);
        }
        $gallery->load('images', 'user', 'comments', 'comments.user');

        return response()->json($gallery, 201);

        return response()->json($gallery);
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
