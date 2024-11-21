<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ContractRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ContractController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * TODO: change these once allocation perms are seeded
     * The middleware configured here will be assigned to this controller's
     * routes.
     */
    public function __construct() 
    {
        // $this->middleware('teamowner', ['only' => ['create','store','update','edit','destroy']]);  
        // $this->middleware('contract:view', ['only' => ['index']]);
        // $this->middleware('contract:create', ['only' => ['create','store']]);
        // $this->middleware('contract:update', ['only' => ['update','edit']]);
        // $this->middleware('contract:delete', ['only' => ['destroy']]);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $contracts = Contract::paginate();

        return view('contract.index', compact('contracts'))
            ->with('i', ($request->input('page', 1) - 1) * $contracts->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $contract = new Contract();

        $resources = Resource::all(); // Retrieve all resources

        return view('contract.create', compact('contract', 'resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContractRequest $request): RedirectResponse
    {
        Contract::create($request->validated());

        return Redirect::route('contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $contract = Contract::find($id);

        return view('contract.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $contract = Contract::find($id);

        $resources = Resource::all(); // Retrieve all resources

        return view('contract.edit', compact('contract', 'resources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContractRequest $request, Contract $contract): RedirectResponse
    {
        $contract->update($request->validated());
        
        return Redirect::route('contracts.index')
            ->with('success', 'Contract updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Contract::find($id)->delete();

        return Redirect::route('contracts.index')
            ->with('success', 'Contract deleted successfully');
    }
}
