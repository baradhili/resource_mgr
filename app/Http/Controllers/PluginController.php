<?php

namespace App\Http\Controllers;

use App\Http\Requests\PluginRequest;
use App\Models\Plugin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PluginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $plugins = Plugin::paginate($request->input('perPage', 10));

        return view('plugin.index', compact('plugins'))
            ->with('i', ($request->input('page', 1) - 1) * $plugins->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $plugin = new Plugin;

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
        if (! $plugin) {
            abort(404, 'Plugin not found');
        }

        return view('plugin.show', compact('plugin'));
    }

    /**
     * Remove the specified plugin from the disk.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        $plugin = Plugin::find($id);
        if ($plugin) {
            // Delete the plugin entry from the database
            $plugin->delete();

            // Construct the path to the module directory
            $modulePath = base_path('Modules/'.$plugin->name);

            // Delete the module directory
            if (is_dir($modulePath)) {
                $this->deleteDirectory($modulePath);
            }

            return Redirect::route('plugins.index')
                ->with('success', 'Plugin deleted successfully');
        }

        return Redirect::route('plugins.index')
            ->with('error', 'Plugin not found');
    }

    /**
     * Recursively delete a directory
     */
    protected function deleteDirectory($dir)
    {


        // Security check to ensure we're only deleting directories within the Modules folder
        $modulesPath = base_path('Modules');
        if (!str_starts_with(realpath($dir), realpath($modulesPath))) {
            return false;
        }
        if (!file_exists($dir)) {
            return true;
        }

        if (! is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (! $this->deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) {
                return false;
            }
        }

        return rmdir($dir);
    }
}
