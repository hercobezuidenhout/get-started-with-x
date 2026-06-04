<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ChirpController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chirps = Chirp::with('user')
            ->latest()
            ->take(50)
            ->get();

        return view('home', ['chirps' => $chirps]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // This is the perfect method because it is creating a resource.
        $validatedRequest = $request->validate([
            'message' => 'required|string|max:255|min:5',
        ], [
            'message.required' => 'Please type something to chirp!',
            'message.max' => 'Chirps must be 255 characters or less.',
            'message.min' => 'Surely you have more to say than that?',
        ]);

        auth()->user()->chirps()->create($validatedRequest);

        return redirect('/')->with('success', 'Chirp has been posted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        return view('chirps.edit', compact('chirp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp)
    {
        $this->authorize('update', $chirp);

        $validatedRequest = $request->validate([
            'message' => 'required|string|max:255|min:5',
        ], [
            'message.required' => 'Please type something to chirp!',
            'message.max' => 'Chirps must be 255 characters or less.',
            'message.min' => 'Surely you have more to say than that?',
        ]);

        $chirp->update($validatedRequest);

        return redirect('/')->with('success', 'Chirp has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp)
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        return redirect('/')->with('success', 'Chirp has been deleted!');
    }
}
