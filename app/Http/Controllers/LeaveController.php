<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Resource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LeaveRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $old = $request->query('old');
        $search = $request->query('search');

        // if old then don't filter by end_date 
        // if search then filter by leave->resource->full_name
        // if not old then end_date >=now
        // assemble the query based on old and search values

        $query = Leave::query();

        if (!$old) {
            $query->where('end_date', '>=', now());
        }

        if ($search) {
            $query->whereHas('resource', function ($resourceQuery) use ($search) {
                $resourceQuery->where('full_name', 'like', "%$search%");
            });
        }

        $leaves = $query->paginate();

        return view('leave.index', compact('leaves'))
            ->with('i', ($request->input('page', 1) - 1) * $leaves->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $leave = new Leave();

        $resources = Resource::all(); // Retrieve all resources

        return view('leave.create', compact('leave','resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LeaveRequest $request): RedirectResponse
    {
        Leave::create($request->validated());

        return Redirect::route('leaves.index')
            ->with('success', 'Leave created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $leave = Leave::find($id);

        return view('leave.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $leave = Leave::find($id);

        $resources = Resource::all(); // Retrieve all resources

        return view('leave.edit', compact('leave','resources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LeaveRequest $request, Leave $leave): RedirectResponse
    {
        $leave->update($request->validated());

        return Redirect::route('leaves.index')
            ->with('success', 'Leave updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Leave::find($id)->delete();

        return Redirect::route('leaves.index')
            ->with('success', 'Leave deleted successfully');
    }
}
