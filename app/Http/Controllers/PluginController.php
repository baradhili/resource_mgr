<?php

namespace App\Http\Controllers;

use App\Models\Plugin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PluginRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PluginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $plugins = Plugin::paginate();

        return view('plugin.index', compact('plugins'))
            ->with('i', ($request->input('page', 1) - 1) * $plugins->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $plugin = new Plugin();

        return view('plugin.create', compact('plugin'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PluginRequest $request): RedirectResponse
    {
        Plugin::create($request->validated());

        return Redirect::route('plugins.index')
            ->with('success', 'Plugin created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $plugin = Plugin::find($id);

        return view('plugin.show', compact('plugin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $plugin = Plugin::find($id);

        return view('plugin.edit', compact('plugin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PluginRequest $request, Plugin $plugin): RedirectResponse
    {
        $plugin->update($request->validated());

        return Redirect::route('plugins.index')
            ->with('success', 'Plugin updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Plugin::find($id)->delete();

        return Redirect::route('plugins.index')
            ->with('success', 'Plugin deleted successfully');
    }
}
