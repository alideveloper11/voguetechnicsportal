{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}


@extends('admin.layouts.master')
@section('title', 'Edit Profile | Vogue Technics')
@section('content')
    <section>

        <!-- Account -->
        <div class="card mb-6">
            <h5 class="card-header">User Information</h5>
            <div class="card-body">
                <div class="d-flex align-items-start align-items-sm-center gap-6">
                    @if ($user->profile_image)
                        <img src="{{ asset($user->profile_image) }}" alt="">
                    @else
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded"/>
                    @endif
                </div>
                <div class="row gy-4 gx-6 mb-6">
                    <div class="col-12 col-md-4">
                        <label for="name" class="form-label">Name</label>
                        <input class="form-control" type="text" id="name" value="{{ $user->name }}" readonly />
                    </div>
                    <div class="col-12 col-md-4">
                        <label for="email" class="form-label">E-mail</label>
                        <input class="form-control" type="text" id="email" value="{{ $user->email }}" readonly />
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="phoneNumber">Phone Number</label>
                        <input type="text" id="phoneNumber" name="phoneNumber" class="form-control" value="{{ $user->phone }}" placeholder="202 555 0111" readonly />
                    </div>
                </div>

            </div>
        </div>
        <!-- /Account -->

        <!-- Change Password -->
        <div class="card mb-6">
            <h5 class="card-header">Change Password</h5>
            <div class="card-body pt-1">
                <form class="ajax-form" action="{{ route('profile.update') }}" method="POST" data-redirect="{{ url('/profile') }}" class="g-3">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="email" value="{{ $user->email }}">
                    {{-- <div class="row mb-sm-6 mb-2">
                        <div class="col-md-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="currentPassword">Current Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" name="currentPassword" id="currentPassword"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                <span class="input-group-text cursor-pointer"><i
                                        class="icon-base ti tabler-eye-off icon-xs"></i></span>
                            </div>
                        </div>
                    </div> --}}
                    <div class="row gy-sm-6 gy-2 mb-sm-0 mb-2">
                        <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="newPassword">New Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" id="newPassword" name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required />
                                <span class="input-group-text cursor-pointer"><i
                                        class="icon-base ti tabler-eye-off icon-xs"></i></span>
                            </div>
                        </div>

                        <div class="mb-6 col-md-6 form-password-toggle form-control-validation">
                            <label class="form-label" for="password_confirmation">Confirm New Password</label>
                            <div class="input-group input-group-merge">
                                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" required/>
                                <span class="input-group-text cursor-pointer"><i
                                        class="icon-base ti tabler-eye-off icon-xs"></i></span>
                            </div>
                        </div>
                    </div>
                    {{-- <h6 class="text-body">Password Requirements:</h6>
                    <ul class="ps-4 mb-0">
                        <li class="mb-4">Minimum 8 characters long - the more, the better</li>
                        <li class="mb-4">At least one lowercase character</li>
                        <li>At least one number, symbol, or whitespace character</li>
                    </ul> --}}
                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3">Save changes</button>
                        <button type="reset" class="btn btn-label-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
        <!--/ Change Password -->
    </section>

@endsection
