<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::paginate(6);
        return view('admin.projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::all();
        return view('admin.projects.create', compact('types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|',
            'author' => 'required|string|max:100',
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg',
            'type_id' => 'nullable|exists:types,id'
        ], [
            'title.required' => 'il titolo è obbligatorio',
            'title.max' => 'il titolo deve essere massimo di 100 caratteri',
            'description.required' => 'la descrizione è obbligatoria',
            'author.required' => 'l\'autore è obbligatorio',
            'author.max' => 'il nome dell\'autore deve essere massimo di 100 caratteri',
            'cover_image.image' => 'il file deve essere un\'immagine',
            'cover_image.mimes' => 'il file deve essere di tipo jpeg, jpg, png.',
            'type_id.exists' => 'id del tipo non è valido'

        ]);

        $data = $request->all();

        if (Arr::exists($data, 'cover_image')) {
            $img_path = Storage::put('uploads/projects', $data['cover_image']);
        } else {
            $img_path = Null;
        }

        $project = new Project;
        $project->title = $data['title'];
        $project->description = $data['description'];
        $project->author = $data['author'];
        $project->cover_image = $img_path;
        $project->type_id = $data['type'];
        $project->save();
        return redirect()->route('projects.show', compact('project'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $types = Type::all();
        return view('admin.projects.edit', compact('project', 'types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|',
            'author' => 'required|string|max:100',
            'cover_image' => 'nullable|image|mimes:jpg,png,jpeg',
            'type_id' => 'nullable|exists:types,id'
        ], [
            'title.required' => 'il titolo è obbligatorio',
            'title.max' => 'il titolo deve essere massimo di 100 caratteri',
            'description.required' => 'la descrizione è obbligatoria',
            'author.required' => 'l\'autore è obbligatorio',
            'author.max' => 'il nome dell\'autore deve essere massimo di 100 caratteri',
            'cover_image.image' => 'il file deve essere un\'immagine',
            'cover_image.mimes' => 'il file deve essere di tipo jpeg, jpg, png.',
            'type_id.exists' => 'id del tipo non è valido'

        ]);

        $data = $request->all();
        $project->title = $data['title'];
        $project->description = $data['description'];
        $project->author = $data['author'];
        $project->type_id = $data['type'];
        $project->save();

        return redirect()->route('projects.show', $project);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index');
    }
}
