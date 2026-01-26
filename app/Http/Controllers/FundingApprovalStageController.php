<?php

namespace App\Http\Controllers;

use App\Http\Requests\FundingApprovalStageRequest;
use App\Models\FundingApprovalStage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class FundingApprovalStageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $fundingApprovalStages = FundingApprovalStage::paginate($request->input('perPage', 10));

        return view('funding-approval-stage.index', compact('fundingApprovalStages'))
            ->with('i', ($request->input('page', 1) - 1) * $fundingApprovalStages->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $fundingApprovalStage = new FundingApprovalStage;

        return view('funding-approval-stage.create', compact('fundingApprovalStage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FundingApprovalStageRequest $request): RedirectResponse
    {
        FundingApprovalStage::create($request->validated());

        return Redirect::route('funding-approval-stages.index')
            ->with('success', 'FundingApprovalStage created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $fundingApprovalStage = FundingApprovalStage::find($id);

        return view('funding-approval-stage.show', compact('fundingApprovalStage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $fundingApprovalStage = FundingApprovalStage::find($id);

        return view('funding-approval-stage.edit', compact('fundingApprovalStage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FundingApprovalStageRequest $request, FundingApprovalStage $fundingApprovalStage): RedirectResponse
    {
        $fundingApprovalStage->update($request->validated());

        return Redirect::route('funding-approval-stages.index')
            ->with('success', 'FundingApprovalStage updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        FundingApprovalStage::find($id)->delete();

        return Redirect::route('funding-approval-stages.index')
            ->with('success', 'FundingApprovalStage deleted successfully');
    }
}
