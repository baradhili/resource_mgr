<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestRequest;
use App\Models\DemandRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DemandRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $perPage = max(1, min((int) $request->input('perPage', 10), 100));
        $requests = DemandRequest::paginate($perPage);

        return view('request.index', compact('requests'))
            ->with('i', ($request->input('page', 1) - 1) * $requests->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $request = new DemandRequest;

        return view('request.create', compact('request'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestRequest $request): RedirectResponse
    {
        DemandRequest::create($request->validated());

        return Redirect::route('requests.index')
            ->with('success', 'Request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $request = DemandRequest::find($id);

        return view('request.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $request = DemandRequest::find($id);

        return view('request.edit', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DemandRequest $demandRequest): RedirectResponse
    {
        $demandRequest->update($demandRequest->validated());

        return Redirect::route('requests.index')
            ->with('success', 'Request updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DemandRequest::find($id)->delete();

        return Redirect::route('requests.index')
            ->with('success', 'Request deleted successfully');
    }
}
