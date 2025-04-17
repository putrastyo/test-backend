<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Services\MovieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    protected $service;

    public function __construct(MovieService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $movies = $this->service->all($request);
        return response()->json([
            "status_code" => 200,
            "data" => $movies,
        ]);
    }

    public function show($id)
    {
        try {
            $movie = $this->service->find($id);
            return response()->json([
                "status_code" => 200,
                "data" => $movie,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status_code" => 404,
                "message" => $e->getMessage(),
            ], 404);
        }
    }

    public function store(StoreMovieRequest $request)
    {
        $data = $request->validated();
        $data['poster'] = $request->file('poster')->store('posters', 'public');
        $movie = $this->service->create($data);

        return response()->json([
            "status_code" => 201,
            "data" => $movie,
        ], 201);
    }

    public function update(UpdateMovieRequest $request, $id)
    {
        $movie = $this->service->find($id);
        $data = $request->validated();

        if ($request->hasFile('poster')) {
            if ($movie->poster && Storage::disk('public')->exists($movie->poster)) {
                Storage::disk('public')->delete($movie->poster);
            }

            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $movie = $this->service->update($data, $id);
        return response()->json([
            "status_code" => 200,
            "data" => $movie,
        ]);
    }

    public function destroy($id)
    {
        $movie = $this->service->delete($id);
        return response()->json([
            "status_code" => 200,
            "data" => $movie,
        ]);
    }
}
