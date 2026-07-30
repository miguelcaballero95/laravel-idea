<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CreateIdea;
use App\Actions\UpdateIdea;
use App\Http\Requests\IdeaRequest;
use App\Http\Requests\UpdateIdeaRequest;
use App\IdeaStatus;
use App\Models\Idea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $status = $request->status;

        if (!in_array($status, IdeaStatus::values())) {
            $status = null;
        }

        $ideas = $user
            ->ideas()
            ->when($status, fn($query, $status) => $query->where('status', $status))
            ->latest()
            ->get();

        return view('idea.index', [
            'ideas' => $ideas,
            'statusCounts' => Idea::statusCounts($user),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IdeaRequest $request, CreateIdea $action)
    {
        // (new CreateIdea())->handle($request->safe()->all());
        $action->handle($request->safe()->all());

        return to_route('idea.index')
            ->with('success', 'Idea created successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): void
    {
    }

    /**
     * Display the specified resource.
     */
    public function show(Idea $idea)
    {
        Gate::authorize('work-with', $idea);

        return view('idea.show', [
            'idea' => $idea,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Idea $idea): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Idea $idea)
    {
        Gate::authorize('work-with', $idea);

        $idea->delete();

        return to_route('idea.index');
    }

    public function destroyImage(Idea $idea)
    {
        Gate::authorize('work-with', $idea);

        Storage::disk('public')->delete($idea->image_path);
        $idea->update([
            'image_path' => null,
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IdeaRequest $request, Idea $idea, UpdateIdea $action): void
    {
        Gate::authorize('work-with', $idea);
        $action->handle($request->safe()->all(), $idea);

    }
}
