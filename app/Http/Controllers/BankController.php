<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Website;
use App\Http\Requests\BankRequest;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class BankController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-banks', only: ['index', 'show']),
            new Middleware('permission:create-banks', only: ['create', 'store']),
            new Middleware('permission:edit-banks', only: ['edit', 'update']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $query = Bank::with('website:id,name');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->addColumn('status', function ($bank) {
                    return (int) $bank->active;
                })
                ->make(true);
        }

        $websites = Website::where('status', 1)->get(['id', 'name']);
        return view('admin.bank_management.index', compact('websites'));
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
    public function store(BankRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['is_vat'] = $request->has('is_vat') ? 1 : 0;
                $data['vat'] = $data['is_vat'] ? ($data['vat'] ?? 0) : 0;
                $data['active'] = $request->has('status') ? 1 : 0;
                unset($data['status']);

                Bank::create($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Bank Added Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        $data = $bank->toArray();
        $data['status'] = (int) $bank->active;

        return response()->json([
            'data' => $data,
            'fields' => ['website_id', 'name', 'account_title', 'account_number', 'branch_name', 'sort_code', 'is_vat', 'vat', 'status'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BankRequest $request, Bank $bank)
    {
        try {
            DB::transaction(function () use ($request, $bank) {
                $data = $request->validated();
                $data['is_vat'] = $request->has('is_vat') ? 1 : 0;
                $data['vat'] = $data['is_vat'] ? ($data['vat'] ?? 0) : 0;
                $data['active'] = $request->has('status') ? 1 : 0;
                unset($data['status']);

                $bank->update($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Bank Updated Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        //
    }
}
