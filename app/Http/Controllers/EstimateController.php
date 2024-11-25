<?php

namespace App\Http\Controllers;

use App\Models\Estimate;
use App\Models\Service;
use App\Models\TermsAndCondition;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EstimateRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EstimateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $estimates = Estimate::paginate();

        return view('estimate.index', compact('estimates'))
            ->with('i', ($request->input('page', 1) - 1) * $estimates->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $estimate = new Estimate();
        $services = Service::all();
        $termsAndConditions = TermsAndCondition::all();

        return view('estimate.create', compact('estimate','services', 'termsAndConditions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse //EstimateRequest
    {
        Estimate::create($request->validated());

        return Redirect::route('estimates.index')
            ->with('success', 'Estimate created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $estimate = Estimate::find($id);
        

        return view('estimate.show', compact('estimate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $estimate = Estimate::find($id);
        $services = Service::all();
        $termsAndConditions = TermAsndCondition::all();

        return view('estimate.edit', compact('estimate','services', 'termsAndConditions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EstimateRequest $request, Estimate $estimate): RedirectResponse
    {
        $estimate->update($request->validated());

        return Redirect::route('estimates.index')
            ->with('success', 'Estimate updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Estimate::find($id)->delete();

        return Redirect::route('estimates.index')
            ->with('success', 'Estimate deleted successfully');
    }
}
