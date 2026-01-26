<?php

namespace App\Http\Controllers;

use App\Http\Requests\TermsAndConditionRequest;
use App\Models\TermsAndCondition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TermsAndConditionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $termsAndConditions = TermsAndCondition::paginate(max(1, min((int) $request->input('perPage', 10), 100)));

        return view('terms-and-condition.index', compact('termsAndConditions'))
            ->with('i', ($request->input('page', 1) - 1) * $termsAndConditions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $termsAndCondition = new TermsAndCondition;

        return view('terms-and-condition.create', compact('termsAndCondition'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TermsAndConditionRequest $request): RedirectResponse
    {
        TermsAndCondition::create($request->validated());

        return Redirect::route('terms-and-conditions.index')
            ->with('success', 'TermsAndCondition created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $termsAndCondition = TermsAndCondition::find($id);

        return view('terms-and-condition.show', compact('termsAndCondition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $termsAndCondition = TermsAndCondition::find($id);

        return view('terms-and-condition.edit', compact('termsAndCondition'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TermsAndConditionRequest $request, TermsAndCondition $termsAndCondition): RedirectResponse
    {
        $termsAndCondition->update($request->validated());

        return Redirect::route('terms-and-conditions.index')
            ->with('success', 'TermsAndCondition updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        TermsAndCondition::find($id)->delete();

        return Redirect::route('terms-and-conditions.index')
            ->with('success', 'TermsAndCondition deleted successfully');
    }
}
