<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use App\Models\Manager;
use App\Models\Client;

use App\Http\Requests\StoreProjectAPIRequest;
use App\Http\Requests\UpdateProjectAPIRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {

        $this->middleware(['auth:sanctum', 'checkUser:ProductOwner,Client'])->only('store', 'delete');
        $this->middleware(['auth:sanctum', 'checkUser:ProductOwner,ProductManager,Admin'])->only('update');
        $this->middleware(['auth:sanctum', 'checkUser:ProductOwner,ProductManager,Client,Admin'])->only('searchProjectByStatus');
        $this->middleware(['auth:sanctum', 'checkUser:ProductOwner,Client,Admin,ProductManager'])->only('searchProjectByUsers');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $projects = Project::all();
            return ProjectResource::collection($projects);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'not found projects'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectAPIRequest $request)
    {
        // <<<<<<< HEAD
        //         $managerWithFewestProjects = $this->getManagerWithFewestProjects();
        //         $ownerWithFewestProjects = $this->getOwnerWithFewestProjects();

        //         $this->updateOrAppendValue($request, 'ProductManager_id', $managerWithFewestProjects);
        //         $this->updateOrAppendValue($request, 'ProductOwner_id', $ownerWithFewestProjects);

        // =======
        //
        // $managerWithFewestProjects =
        //     Manager::join('users', 'managers.user_id', '=', 'users.id')
        //     ->where('users.role', 'ProductManager')
        //     ->whereNotIn('managers.id', function ($query) {
        //         $query->select('ProductManager_id')
        //             ->from('projects');
        //     })
        //     ->value('managers.id');

        // $ownerWithFewestProjects =
        //     Manager::join('users', 'managers.user_id', '=', 'users.id')
        //     ->where('users.role', 'ProductOwner')
        //     // ->whereNotIn('managers.id', function ($query) {
        //     //     $query->select('ProductOwner_id')
        //     //         ->from('projects');
        //     // })
        //     ->value('managers.id');
        // // var_dump($ownerWithFewestProjects);

        // if (!$managerWithFewestProjects) {
        //     $managerWithFewestProjects = Project::select('ProductManager_id')
        //         ->groupBy('ProductManager_id')
        //         ->orderByRaw('COUNT(*) ASC')
        //         ->value('ProductManager_id');
        // }

        // if ($ownerWithFewestProjects == "NULL") {
        //     $ownerWithFewestProjects = Project::select('ProductOwner_id')
        //         ->groupBy('ProductOwner_id')
        //         ->orderByRaw('COUNT(*) ASC')
        //         ->value('ProductOwner_id');
        // }

        // // Check if the request has the ProductManager_id input
        // if ($request->has('ProductManager_id')) {
        //     // Update the ProductManager_id with the managerWithFewestProjects value
        //     $request->merge(['ProductManager_id' => $managerWithFewestProjects]);
        // } else {
        //     // Append the new value to the request
        //     $request->request->add(['ProductManager_id' => $managerWithFewestProjects]);
        // }
        // // Check if the request has the ProductOwner_id input
        // if ($request->has('ProductOwner_id')) {
        //     // Update the ProductManager_id with the ownerWithFewestProjects value
        //     $request->merge(['ProductOwner_id' => $ownerWithFewestProjects]);
        // } else {
        //     // Append the new value to the request
        //     $request->request->add(['ProductOwner_id' => $ownerWithFewestProjects]);
        // }
        $project = Project::create($request->all());
        return new ProjectResource($project);
    }

    // private function getManagerWithFewestProjects()
    // {
    //     $managerWithFewestProjects = Manager::join('users', 'managers.user_id', '=', 'users.id')
    //         ->where('users.role', 'ProductManager')
    //         ->whereNotIn('managers.id', function ($query) {
    //             $query->select('ProductManager_id')
    //                 ->from('projects');
    //         })
    //         ->value('managers.id');

    //     if (!$managerWithFewestProjects) {
    //         $managerWithFewestProjects = Project::select('ProductManager_id')
    //             ->groupBy('ProductManager_id')
    //             ->orderByRaw('COUNT(*) ASC')
    //             ->value('ProductManager_id');
    //     }

    //     return $managerWithFewestProjects;
    // }

    // private function getOwnerWithFewestProjects()
    // {
    //     $ownerWithFewestProjects = Manager::join('users', 'managers.user_id', '=', 'users.id')
    //         ->where('users.role', 'ProductOwner')
    //         ->whereNotIn('managers.id', function ($query) {
    //             $query->select('ProductOwner_id')
    //                 ->from('projects');
    //         })
    //         ->value('managers.id');

    //     if (!$ownerWithFewestProjects) {
    //         $ownerWithFewestProjects = Project::select('ProductOwner_id')
    //             ->groupBy('ProductOwner_id')
    //             ->orderByRaw('COUNT(*) ASC')
    //             ->value('ProductOwner_id');
    //     }

    //     return $ownerWithFewestProjects;
    // }

    // private function updateOrAppendValue($request, $key, $value)
    // {
    //     if ($request->has($key)) {
    //         $request->merge([$key => $value]);
    //     } else {
    //         $request->request->add([$key => $value]);
    //     }
    // }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $project = Project::where('clinte_id', $id)->first();
            return new ProjectResource($project);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['error' => 'Project not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectAPIRequest $request, string $id)
    {
        //
        try {
            $project = Project::findOrFail($id);
            $project->update($request->all());

            return new ProjectResource($project);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'check if project is exist and check it is validation'
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        try {
            $project = Project::findOrFail($id);
            $project->delete();
            return response()->json([
                'success' => "project deleted"
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'check if project is exist '
            ], 404);
        }
    }
    public function searchProjectByStatus($status)
    {
        $searchTerm = $status;

        $results = [];
        if (!Auth::user()) {
            return response()->json([
                'error' => 'unauthentecation'
            ], 404);
        }
        $id = Auth::user()->id;
        if (Auth::user()->role == 'Admin') {
            // Perform your search logic based on the provided search term
            $results = Project::where('project_status', '=', $searchTerm)->get();
        } elseif (Auth::user()->role == 'ProductManager') {
            $manager = Manager::where('user_id', $id)->first();
            $results = Project::where([
                ['ProductManager_id', '=', $manager->id],
                ['project_status', '=', $searchTerm]
            ])->get();
        } elseif (Auth::user()->role == 'ProductOwner') {
            $owner = Manager::where('user_id', $id)->first();
            $results = Project::where([
                ['ProductOwner_id', '=', $owner->id],
                ['project_status', '=', $searchTerm]
            ])->get();
        } elseif (Auth::user()->role == 'Client') {
            $client = Client::where('user_id', $id)->first();
            $results = Project::where([
                ['client_id', '=', $client->id],
                ['project_status', '=', $searchTerm],
            ])->get();
        } else {
            return response()->json([
                'error' => 'Not found project with this status'
            ], 404);
        }
        return ProjectResource::collection($results);
    }
    public function searchProjectByUsers()
    {

        $results = [];
        if (!Auth::user()) {
            return response()->json([
                'error' => 'unauthentecation'
            ], 404);
        }
        $id = Auth::user()->id;
        if (Auth::user()->role == 'Admin') {
            // Perform your search logic based on the provided search term
            $results = Project::all();
        } elseif (Auth::user()->role == 'ProductManager') {
            $manager = Manager::where('user_id', $id)->first();
            $results = Project::where([
                ['ProductManager_id', '=', $manager->id],
            ])->get();
        } elseif (Auth::user()->role == 'ProductOwner') {
            $owner = Manager::where('user_id', $id)->first();
            $results = Project::where([
                ['ProductOwner_id', '=', $owner->id],
            ])->get();
        } elseif (Auth::user()->role == 'Client') {
            $client = Client::where('user_id', $id)->first();
            $results = Project::where([
                ['client_id', '=', $client->id],
            ])->get();
        } else {
            return response()->json([
                'error' => 'Not found project to this user'
            ], 404);
        }
        return   ProjectResource::collection($results);
    }
    public function countProject()
    {
        $count = Project::count();
        return response()->json([
            'countProject' => $count
        ], 200);
    }
}
