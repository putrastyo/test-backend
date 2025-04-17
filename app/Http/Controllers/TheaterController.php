<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTheaterRequest;
use App\Http\Requests\UpdateTheaterRequest;
use App\Services\TheaterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TheaterController extends Controller
{
    protected $service;

    public function __construct(TheaterService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $theaters = $this->service->all($request);
        return response()->json([
            "status_code" => 200,
            "data" => $theaters,
        ]);
    }

    public function show($id)
    {
        try {
            $theater = $this->service->find($id);
            return response()->json([
                "status_code" => 200,
                "data" => $theater,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status_code" => 404,
                "message" => $e->getMessage(),
            ], 404);
        }
    }

    public function store(StoreTheaterRequest $request)
    {
        $data = $request->validated();
        $data['logo'] = $request->file('logo')->store('logos', 'public');
        $theater = $this->service->create($data);

        return response()->json([
            "status_code" => 201,
            "data" => $theater,
        ], 201);
    }

    public function update(UpdateTheaterRequest $request, $id)
    {
        $theater = $this->service->find($id);
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($theater->logo && Storage::disk('public')->exists($theater->logo)) {
                Storage::disk('public')->delete($theater->logo);
            }

            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $theater = $this->service->update($data, $id);
        return response()->json([
            "status_code" => 200,
            "data" => $theater,
        ]);
    }

    public function destroy($id)
    {
        $theater = $this->service->delete($id);
        return response()->json([
            "status_code" => 200,
            "data" => $theater,
        ]);
    }
}
