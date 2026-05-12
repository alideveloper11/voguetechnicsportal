<?php

namespace App\Http\Controllers;
use App\Http\Requests\RoleRequest;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-roles', only: ['index', 'show']),
            new Middleware('permission:create-roles', only: ['create', 'store']),
            new Middleware('permission:edit-roles', only: ['edit', 'update']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $query = Role::where('name', '!=', 'Admin');
            return DataTables::eloquent($query)->addIndexColumn()->make(true);
        }
        return view('admin.user_management.roles.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all()
        ->groupBy('parent_name')
        ->map(function ($permissionsGroup) {
            return [
                'permissions' => $permissionsGroup
            ];
        });
        return view('admin.user_management.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try {
            $data = $request->validated();
            $role = Role::create($data);
            if(isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }
            return response()->json(['status' => 'success', 'message' => 'Role Created Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::find($id);
        $role->permissions = $role->permissions->pluck('id')->toArray();
        $permissions = Permission::all()
        ->groupBy('parent_name')
        ->map(function ($permissionsGroup) {
            return [
                'permissions' => $permissionsGroup
            ];
        });
        return view('admin.user_management.roles.edit', compact('permissions','role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $role = Role::find($id);
            $role->update($data);
            if(isset($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }
            return response()->json(['status' => 'success', 'message' => 'Role Updated Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
