<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use App\Http\Requests\WebsiteRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class WebsiteController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-websites', only: ['index', 'show']),
            new Middleware('permission:create-websites', only: ['create', 'store']),
            new Middleware('permission:edit-websites', only: ['edit', 'update']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $query = Website::query();

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('logo', function ($website) {
                    return $website->logo ? asset($website->logo) : null;
                })
                ->make(true);
        }
        return view('admin.website_management.index');
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
    public function store(WebsiteRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                unset($data['remove_logo']);

                if ($request->hasFile('logo')) {
                    $data['logo'] = uploadFile($request->file('logo'), 'uploads/websites');
                }

                Website::create($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Website Added Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Website $website)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Website $website)
    {
        return response()->json([
            'fields' => ['name', 'url', 'email', 'phone', 'landline', 'address', 'status'],
            'data' => $website,
            'dropzones' => [[
                'selector' => '#website-logo-dropzone',
                'url' => $website->logo ? asset($website->logo) : null,
                'name' => $website->logo ? basename($website->logo) : null,
            ]],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WebsiteRequest $request, Website $website)
    {
        try {
            DB::transaction(function () use ($request, $website) {
                $data = $request->validated();
                $data['status'] = $request->has('status') ? 1 : 0;
                $removeLogo = !empty($data['remove_logo']);
                unset($data['remove_logo']);

                if ($removeLogo && $website->logo && File::exists(public_path($website->logo))) {
                    File::delete(public_path($website->logo));
                    $data['logo'] = null;
                }

                if ($request->hasFile('logo')) {
                    if ($website->logo && File::exists(public_path($website->logo))) {
                        File::delete(public_path($website->logo));
                    }

                    $data['logo'] = uploadFile($request->file('logo'), 'uploads/websites');
                }

                $website->update($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Website Updated Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Website $website)
    {
        //
    }
}
