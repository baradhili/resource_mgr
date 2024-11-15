<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ServiceRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $services = Service::paginate();

        return view('service.index', compact('services'))
            ->with('i', ($request->input('page', 1) - 1) * $services->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $service = new Service();

        return view('service.create', compact('service'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceRequest $request): RedirectResponse
    {

        // Service::create($request->validated());
        $validatedData = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'required_skills' => 'nullable|string',
            'hours_cost' => 'nullable|numeric|min:0',
        ]);

        // Convert the skills string to an array
        $skillsArray = explode(',', $validatedData['required_skills']);
        $skillsArray = array_map('trim', $skillsArray); // Trim whitespace from each skill
        $skills=json_encode($skillsArray);

        // Convert the description field so that all new lines are converted to correct markdown
        $description = str_replace(["\r\n", "\r", "\n"], "  \n", $validatedData['description']);

        // Create the service catalogue entry
        $serviceCatalogue = Service::create([
            'service_name' => $validatedData['service_name'],
            'description' => $description,
            'required_skills' => $skills, 
            'hours_cost' => $validatedData['hours_cost'],
        ]);

        return Redirect::route('services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $service = Service::find($id);

        return view('service.show', compact('service'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
  
        $service = Service::find($id);


        return view('service.edit', compact('service'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        // $service->update($request->validated());

        // Validate the request
        $validatedData = $request->validate([
            'service_name' => 'required|string|max:255',
            'description' => 'required|string',
            'required_skills' => 'nullable|string',
            'hours_cost' => 'nullable|numeric|min:0',
        ]);

        // Convert the skills string to an array
        $skillsArray = explode(',', $validatedData['required_skills']);
        $skillsArray = array_map('trim', $skillsArray); // Trim whitespace from each skill
        $skills=json_encode($skillsArray);

        // Convert the description field so that all new lines are converted to correct markdown
        $description = str_replace(["\r\n", "\r", "\n"], "  \n", $validatedData['description']);

        // Update the service catalogue entry
        $service->update([
            'service_name' => $validatedData['service_name'],
            'description' => $description,
            'required_skills' => $skills, 
            'hours_cost' => $validatedData['hours_cost'],
        ]);

        return Redirect::route('services.index')
            ->with('success', 'Service updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Service::find($id)->delete();

        return Redirect::route('services.index')
            ->with('success', 'Service deleted successfully');
    }
}
