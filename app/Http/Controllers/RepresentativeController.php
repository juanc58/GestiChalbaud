<?php

namespace App\Http\Controllers;

use App\Models\Representative;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    public function index(Request $request)
    {
        // Only Admin and Docentes
        if (!auth()->user()->isAdmin() && !auth()->user()->isDocente()) abort(403);

        $query = Representative::with('user')->whereHas('user', function($q) use ($request) {
            if ($request->has('search')) {
                $search = $request->search;
                $q->where(function($subQ) use ($search) {
                    $subQ->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('cedula', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }
        })->get()->sortBy(function($rep) {
            return $rep->user->first_name;
        });

        // Simulating pagination since sortBy converts to Collection
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 20;
        $representatives = new \Illuminate\Pagination\LengthAwarePaginator(
            $query->forPage($currentPage, $perPage),
            $query->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'query' => $request->query()]
        );

        return view('representatives.index', compact('representatives'));
    }

    public function edit(Representative $representative)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isDocente()) abort(403);

        return view('representatives.edit', compact('representative'));
    }

    public function update(Request $request, Representative $representative)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isDocente()) abort(403);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'second_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'second_last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:255',
            'phone_local' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'workplace_address' => 'nullable|string',
            'job_title' => 'nullable|string|max:255',
        ]);

        $representative->user->update([
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'last_name' => $request->last_name,
            'second_last_name' => $request->second_last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        $representative->update($request->only([
            'phone_local', 'facebook', 'workplace_address', 'job_title'
        ]));

        return redirect()->route('representatives.index')->with('success', 'Ficha del representante actualizada.');
    }
}
