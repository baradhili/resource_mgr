<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $clients = Client::paginate(max(1, min((int) $request->input('perPage', 10), 100)));

        return view('client.index', compact('clients'))
            ->with('i', ($request->input('page', 1) - 1) * $clients->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $client = new Client;

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
     * Display the specified client and its paginated projects.
     *
     * @param int|string $id The ID of the client to display.
     * @return \Illuminate\View\View The view displaying the client and its paginated projects.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If a client with the given ID does not exist.
     */
    public function show($id): View
    {
        // Find the client or fail (404 if not found)
        $client = Client::findOrFail($id);

        // Get the projects associated with this client, paginated
        // You can adjust the number (10) to however many projects you want per page
        $projects = $client->projects()->paginate(10);

        return view('client.show', compact('client', 'projects'));
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