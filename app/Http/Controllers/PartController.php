<?php

namespace App\Http\Controllers;

use App\Models\Part;
use Illuminate\Http\Request;
use App\Http\Requests\PartRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PartController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-parts', only: ['index', 'show']),
            new Middleware('permission:create-parts', only: ['create', 'store']),
            new Middleware('permission:edit-parts', only: ['edit', 'update']),
            new Middleware('permission:delete-parts', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $parts = Part::with('createdBy');
            return DataTables::eloquent($parts)->addIndexColumn()->make(true);
        }

        return view('admin.parts.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PartRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['created_by'] = auth()->id();
                $data['is_active'] = $request->has('is_active') ? 1 : 0;
                Part::create($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Part Added Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Part $part)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Part $part)
    {
        $fields = $part->getFillable();
        return response()->json(['data' => $part, 'fields' => $fields]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartRequest $request, Part $part)
    {
        try {
            DB::transaction(function () use ($request, $part) {
                $data = $request->validated();
                $data['is_active'] = $request->has('is_active') ? 1 : 0;
                $part->update($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Part Updated Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part)
    {
        try {
            DB::transaction(function () use ($part) {
                $part->delete();
            });

            return response()->json(['status' => 'success', 'message' => 'Part Deleted Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }
}
