<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicHolidayRequest;
use App\Models\PublicHoliday;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PublicHolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $publicHolidays = PublicHoliday::paginate($request->input('perPage', 10));

        return view('public-holiday.index', compact('publicHolidays'))
            ->with('i', ($request->input('page', 1) - 1) * $publicHolidays->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $publicHoliday = new PublicHoliday;

        return view('public-holiday.create', compact('publicHoliday'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PublicHolidayRequest $request): RedirectResponse
    {
        PublicHoliday::create($request->validated());

        return Redirect::route('public-holidays.index')
            ->with('success', 'PublicHoliday created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $publicHoliday = PublicHoliday::find($id);

        return view('public-holiday.show', compact('publicHoliday'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $publicHoliday = PublicHoliday::find($id);

        return view('public-holiday.edit', compact('publicHoliday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PublicHolidayRequest $request, PublicHoliday $publicHoliday): RedirectResponse
    {
        $publicHoliday->update($request->validated());

        return Redirect::route('public-holidays.index')
            ->with('success', 'PublicHoliday updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        PublicHoliday::find($id)->delete();

        return Redirect::route('public-holidays.index')
            ->with('success', 'PublicHoliday deleted successfully');
    }
}
