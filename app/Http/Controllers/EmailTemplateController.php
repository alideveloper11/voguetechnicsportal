<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use App\Http\Requests\EmailTemplateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class EmailTemplateController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-email-templates', only: ['index', 'show']),
            new Middleware('permission:create-email-templates', only: ['create', 'store']),
            new Middleware('permission:edit-email-templates', only: ['edit', 'update']),
            new Middleware('permission:view-email-templates', only: ['sendTest']),
            new Middleware('permission:create-email-templates|edit-email-templates', only: ['uploadImage']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            return DataTables::eloquent(EmailTemplate::query())->addIndexColumn()->make(true);
        }

        return view('admin.email_template_management.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.email_template_management.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmailTemplateRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['is_active'] = $request->has('is_active') ? 1 : 0;

                EmailTemplate::create($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Email Template Added Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EmailTemplate $emailTemplate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('admin.email_template_management.edit', compact('emailTemplate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmailTemplateRequest $request, EmailTemplate $emailTemplate)
    {
        try {
            DB::transaction(function () use ($request, $emailTemplate) {
                $data = $request->validated();
                $data['is_active'] = $request->has('is_active') ? 1 : 0;

                $emailTemplate->update($data);
            });

            return response()->json(['status' => 'success', 'message' => 'Email Template Updated Successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'Failure', 'error_message' => $e->getMessage()], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $path = uploadFile($request->file('image'), 'uploads/email-templates');

        return response()->json([
            'url' => asset($path),
            'path' => $path,
        ], 200);
    }

    public function sendTest(EmailTemplate $emailTemplate)
    {
        $user = auth()->user();

        if (! $user || ! $user->email) {
            return redirect()->back()->with('error', 'No email address is available for the logged in user.');
        }

        $variables = [
            'customer_name' => $user->name ?? 'Test Customer',
            'customer_email' => $user->email,
            'customer_phone' => $user->phone ?? '0300-0000000',
            'customer_address' => $user->address ?? 'Sample Address',
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Corolla',
            'vehicle_year' => '2024',
            'customer_city' => 'Sample City',
            'customer_postcode' => '12345',
            'price' => '$25,000',
            'reference_no' => 'REF-001',
        ];

        $subject = $emailTemplate->renderSubject($variables);
        $body = $emailTemplate->renderBody($variables);

        Mail::html($body, function ($message) use ($user, $subject) {
            $message->to($user->email, $user->name)->subject($subject);
        });

        return redirect()->back()->with('success', 'Test email sent successfully to ' . $user->email . '.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmailTemplate $emailTemplate)
    {
        //
    }
}
