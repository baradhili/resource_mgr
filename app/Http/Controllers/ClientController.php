<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ClientRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $clients = Client::paginate();

        return view('client.index', compact('clients'))
            ->with('i', ($request->input('page', 1) - 1) * $clients->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $client = new Client();

        return view('client.create', compact('client'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request): RedirectResponse
    {
        Client::create($request->validated());

        return Redirect::route('clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $client = Client::find($id);

        return view('client.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $client = Client::find($id);

        return view('client.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return Redirect::route('clients.index')
            ->with('success', 'Client updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Client::find($id)->delete();

        return Redirect::route('clients.index')
            ->with('success', 'Client deleted successfully');
    }
}
