<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomerTypeRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CustomerTypeController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-customer-types', only: ['index', 'show']),
            new Middleware('permission:create-customer-types', only: ['create', 'store']),
            new Middleware('permission:edit-customer-types', only: ['edit', 'update']),
            new Middleware('permission:delete-customer-types', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $query = CustomerType::with('user');

            return DataTables::eloquent($query)->addIndexColumn()->make(true);
        }
        return view('admin.customers.customer_types.index');
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
    public function store(CustomerTypeRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['is_active'] = $request->has('is_active') ? 1 : 0;
                $data['created_by'] = auth()->id();

                CustomerType::create($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Customer Type Added Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomerType $customerType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerType $customerType)
    {
        return response()->json([
            'fields' => ['name', 'is_active'],
            'data' => $customerType,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerTypeRequest $request, CustomerType $customerType)
    {
        try {
            DB::transaction(function () use ($request, $customerType) {
                $data = $request->validated();
                $data['is_active'] = $request->has('is_active') ? 1 : 0;

                $customerType->update($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Customer Type Updated Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomerType $customerType)
    {
        try {
            $customerType->delete();
            return response()->json(['status' => 'success', 'message' => 'Customer Type Deleted Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }
}
