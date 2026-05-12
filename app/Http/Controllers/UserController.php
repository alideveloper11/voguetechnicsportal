<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-users', only: ['index', 'show']),
            new Middleware('permission:create-users', only: ['create', 'store']),
            new Middleware('permission:edit-users', only: ['edit', 'update']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $query = User::with('roles:id,name')->where('name', '!=', 'Admin');

            return DataTables::eloquent($query)->addIndexColumn()->make(true);
        }
        $roles = Role::where('name', '!=', 'Admin')->get(['id', 'name']);
        return view('admin.user_management.users.index', compact('roles'));
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
    public function store(UserRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['password'] = Hash::make($data['password']);

                if ($request->hasFile('profile_image')) {
                    $data['profile_image'] = uploadFile($request->file('profile_image'), 'uploads/users');
                }

                $user = User::create($data);

                if ($request->has('role')) {
                    $role = Role::find($request->role);
                    $user->assignRole($role);
                }
            });
            return response()->json(['status' => 'success', 'message' => 'User Added Successfully!'], 200);
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
    public function edit(User $user)
    {
        $user->profile_image_url = $user->profile_image ? asset($user->profile_image) : null;
        $user->profile_image_name = $user->profile_image ? basename($user->profile_image) : null;

        return response()->json(['user' => $user,'role' => $user->roles[0]->id], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request,  User $user)
    {
        try {
            DB::transaction(function () use ($request,$user) {
                $data = $request->validated();
                $data['status'] = $request->has('status') ? 1 : 0;
                $data['is_mechanic'] = $request->has('is_mechanic') ? 1 : 0;
                if(!empty($data['password'])){
                	$data['password'] = Hash::make($data['password']);
                } else {
                    unset($data['password']);
                }

                $removeProfileImage = !empty($data['remove_profile_image']);
                unset($data['remove_profile_image']);

                if ($removeProfileImage && $user->profile_image && File::exists(public_path($user->profile_image))) {
                    File::delete(public_path($user->profile_image));
                    $data['profile_image'] = null;
                }

                if ($request->hasFile('profile_image')) {
                    if ($user->profile_image && File::exists(public_path($user->profile_image))) {
                        File::delete(public_path($user->profile_image));
                    }

                    $data['profile_image'] = uploadFile($request->file('profile_image'), 'uploads/users');
                }

                $user->update($data);

                if ($request->has('role')) {
                    $role = Role::find($request->role);
                    $user->syncRoles($role);
                }
            });
            return response()->json(['status' => 'success', 'message' => 'User Updated Successfully!'], 200);
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
