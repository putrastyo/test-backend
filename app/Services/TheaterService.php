<?php

namespace App\Services;

use App\Models\Theater;

class TheaterService
{
    protected $theater;

    public function __construct(Theater $theater)
    {
        $this->theater = $theater;
    }

    public function all($request = null)
    {
        $query = $this->theater->query();

        // fitur search
        if ($request && $request->has('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', "%{$searchTerm}%")
                ->orWhere('founder', 'LIKE', "%{$searchTerm}%");
        }

        // fitur sort
        $sortField = $request && $request->has('sort_by') ? $request->sort_by : 'name';
        $sortDirection = $request && $request->has('sort_direction') ? $request->sort_direction : 'asc';

        $allowedSortFields = ['name', 'founder', 'created_at', 'updated_at'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'name';
        }

        if (!in_array(strtolower($sortDirection), ['asc', 'desc'])) {
            $sortDirection = 'asc';
        }

        $query->orderBy($sortField, $sortDirection);

        return $query->paginate();
    }

    public function find($id)
    {
        return $this->theater->findOrFail($id);
    }

    public function create($data)
    {
        return $this->theater->create($data);
    }

    public function update($data, $id)
    {
        $theater = $this->find($id);
        $theater->update($data);
        return $theater;
    }

    public function delete($id)
    {
        $theater = $this->find($id);
        $theater->delete();
        return $theater;
    }
}
