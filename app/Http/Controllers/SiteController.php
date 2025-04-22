<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteRequest;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $sites = Site::paginate();

        return view('site.index', compact('sites'))
            ->with('i', ($request->input('page', 1) - 1) * $sites->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $site = new Site;

        return view('site.create', compact('site'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SiteRequest $request): RedirectResponse
    {
        Site::create($request->validated());

        return Redirect::route('sites.index')
            ->with('success', 'Site created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $site = Site::find($id);

        return view('site.show', compact('site'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $site = Site::find($id);

        return view('site.edit', compact('site'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SiteRequest $request, Site $site): RedirectResponse
    {
        $site->update($request->validated());

        return Redirect::route('sites.index')
            ->with('success', 'Site updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Site::find($id)->delete();

        return Redirect::route('sites.index')
            ->with('success', 'Site deleted successfully');
    }
}
