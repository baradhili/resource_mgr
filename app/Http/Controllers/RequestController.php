<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RequestRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $requests = Request::paginate();

        return view('request.index', compact('requests'))
            ->with('i', ($request->input('page', 1) - 1) * $requests->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $request = new Request();

        return view('request.create', compact('request'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestRequest $request): RedirectResponse
    {
        Request::create($request->validated());

        return Redirect::route('requests.index')
            ->with('success', 'Request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $request = Request::find($id);

        return view('request.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $request = Request::find($id);

        return view('request.edit', compact('request'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestRequest $request, Request $request): RedirectResponse
    {
        $request->update($request->validated());

        return Redirect::route('requests.index')
            ->with('success', 'Request updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Request::find($id)->delete();

        return Redirect::route('requests.index')
            ->with('success', 'Request deleted successfully');
    }
}
