<?php

namespace App\Services;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Models\Movie;

class MovieService
{
    protected $movie;

    public function __construct(Movie $movie)
    {
        $this->movie = $movie;
    }

    public function all($request = null)
    {
        $query = $this->movie->query();

        // fitur search
        if ($request && $request->has('search')) {
            $searchTerm = $request->search;
            $query->where('title', 'LIKE', "%{$searchTerm}%");
        }

        // fitur sort
        $sortField = $request && $request->has('sort_by') ? $request->sort_by : 'name';
        $sortDirection = $request && $request->has('sort_direction') ? $request->sort_direction : 'asc';

        $allowedSortFields = ['title', 'year', 'duration', 'created_at', 'updated_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'title';
        }

        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $query->orderBy($sortField, $sortDirection);

        // fitur filter
        if ($request && $request->has('year')) {
            if (is_array($request->year)) {
                $query->whereBetween('year', $request->year);
            } elseif (strpos($request->year, 'lt') !== false) {
                $year = (int)str_replace('lt', '', $request->year);
                $query->where('year', '<', $year);
            } elseif (strpos($request->year, 'gt') !== false) {
                $year = (int)str_replace('gt', '', $request->year);
                $query->where('year', '>', $year);
            } else {
                $query->where('year', $request->year);
            }
        }

        return $query->paginate();
    }

    public function find($id)
    {
        return $this->movie->findOrFail($id);
    }

    public function create($data)
    {
        return $this->movie->create($data);
    }

    public function update($data, $id)
    {
        $movie = $this->find($id);
        $movie->update($data);
        return $movie;
    }

    public function delete($id)
    {
        $movie = $this->find($id);
        $movie->delete();
        return $movie;
    }
}
