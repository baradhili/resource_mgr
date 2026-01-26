<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $skills = Skill::paginate($request->input('perPage', 10));

        return view('skill.index', compact('skills'))
            ->with('i', ($request->input('page', 1) - 1) * $skills->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $skill = new Skill;

        return view('skill.create', compact('skill'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkillRequest $request): RedirectResponse
    {
        Skill::create($request->validated());

        return Redirect::route('skills.index')
            ->with('success', 'Skill created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $skill = Skill::find($id);

        return view('skill.show', compact('skill'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $skill = Skill::find($id);

        return view('skill.edit', compact('skill'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SkillRequest $request, Skill $skill): RedirectResponse
    {
        $skill->update($request->validated());

        return Redirect::route('skills.index')
            ->with('success', 'Skill updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Skill::find($id)->delete();

        return Redirect::route('skills.index')
            ->with('success', 'Skill deleted successfully');
    }

    public function importRsd(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'files.*' => 'required|file|mimes:json',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $files = $request->file('files');
        $count = count($files);

        foreach ($files as $file) {
            // Get the file
            $filePath = $file->getRealPath();

            // Read and decode the JSON file
            $json = File::get($filePath);
            $data = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON file'], 422);
            }

            // Map JSON data to Skill model attributes
            $skillData = [
                'id' => Str::uuid(),
                'skill_name' => $data['skillName'],
                'skill_description' => $data['skillStatement'],
                'context' => $data['@context'],
                'employers' => json_encode($data['employers']),
                'keywords' => json_encode($data['keywords']),
                'category' => $data['category'],
                'certifications' => json_encode($data['certifications']),
                'occupations' => json_encode($data['occupations']),
                'license' => $data['license'],
                'derived_from' => json_encode($data['derivedFrom']),
                'source_id' => $data['id'],
                'type' => $data['type'],
                'authors' => json_encode([$data['author']]),
            ];

            // Create or update the skill in the database
            Skill::updateOrCreate(
                ['source_id' => $data['id']],
                $skillData
            );
        }

        return Redirect::route('skills.index')
            ->with('success', "{$count} Skills imported successfully");
    }
}
