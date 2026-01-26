<?php

namespace App\Http\Controllers;

use App\Http\Requests\DomainRequest;
use App\Models\Domain;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $domains = Domain::paginate($request->input('perPage', 10));

        return view('domain.index', compact('domains'))
            ->with('i', ($request->input('page', 1) - 1) * $domains->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $domain = new Domain;

        return view('domain.create', compact('domain'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DomainRequest $request): RedirectResponse
    {
        Domain::create($request->validated());

        return Redirect::route('domains.index')
            ->with('success', 'Domain created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $domain = Domain::find($id);

        return view('domain.show', compact('domain'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $domain = Domain::find($id);

        return view('domain.edit', compact('domain'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DomainRequest $request, Domain $domain): RedirectResponse
    {
        $domain->update($request->validated());

        return Redirect::route('domains.index')
            ->with('success', 'Domain updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Domain::find($id)->delete();

        return Redirect::route('domains.index')
            ->with('success', 'Domain deleted successfully');
    }
}
