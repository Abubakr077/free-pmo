<?php

namespace App\Http\Controllers\Partners;

use App\Entities\Partners\Vendor;
use App\Entities\Users\User;
use App\Entities\Users\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Version;

class VendorsController extends Controller
{
    /**
     * Display a listing of the vendor.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $editableVendor = null;
        $vendorQuery = User::query();
        $vendorQuery->where('name', 'like', '%'.request('q').'%');
        $vendorQuery->with('roles');
        $vendors = $vendorQuery->paginate(25);


        if (in_array(request('action'), ['edit', 'delete']) && request('id') != null) {
            $editableVendor = Vendor::find(request('id'));
        }

        return view('vendors.index', compact('vendors', 'editableVendor'));
    }

    /**
     * Store a newly created vendor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $adminData = $request->only('name', 'email', 'password');

        $adminData['api_token'] = Str::random(32);
        $adminData['password'] = bcrypt($adminData['password']);

        $admin = User::create($adminData);
        $admin->assignRole('student');


        flash(__('vendor.created'), 'success');


        return redirect()->route('vendors.index');
    }

    /**
     * Update the specified vendor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Entities\Partners\Vendor  $vendor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Vendor $vendor)
    {

        $vendor->update($request->validate([
            'name'      => 'required|max:60',
            'notes'     => 'nullable|max:255',
            'email'     => 'nullable|max:255',
            'password'     => 'nullable|max:255',
            'website'   => 'nullable|url|max:255',
            'is_active' => 'required|boolean',
        ]));

        flash(__('vendor.updated'), 'success');

        return redirect()->route('vendors.index', request(['page', 'q']));
    }

    /**
     * Show vendor detail page.
     *
     * @param  \App\Entities\Partners\Vendor  $vendor
     * @return \Illuminate\View\View
     */
    public function show(Vendor $vendor)
    {
        return view('vendors.show', compact('vendor'));
    }

    /**
     * Remove the specified vendor from storage.
     *
     * @param  \App\Entities\Partners\Vendor  $vendor
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Vendor $vendor)
    {
        request()->validate(['vendor_id' => 'required']);

        if (request('vendor_id') == $vendor->id && $vendor->delete()) {
            flash(__('vendor.deleted'), 'warning');

            return redirect()->route('vendors.index', request(['page', 'q']));
        }

        flash(__('vendor.undeleted'), 'danger');

        return back();
    }
}
