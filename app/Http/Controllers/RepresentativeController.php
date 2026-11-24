<?php

namespace App\Http\Controllers;

use App\Models\Representative;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isDocente()) {
            abort(403);
        }

        $query = Representative::withCount('students');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('second_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('second_last_name', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone_whatsapp', 'like', "%{$search}%");
            });
        }

        $representatives = $query->orderBy('first_name')
            ->orderBy('last_name')
            ->paginate(20)
            ->withQueryString();

        return view('representatives.index', compact('representatives'));
    }

    public function edit(Representative $representative)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isDocente()) {
            abort(403);
        }

        $representative->load('students');
        return view('representatives.edit', compact('representative'));
    }

    public function update(Request $request, Representative $representative)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isDocente()) {
            abort(403);
        }

        if ($request->filled('cedula')) {
            $request->merge(['cedula' => preg_replace('/\D/', '', (string) $request->cedula)]);
        }

        $validated = $request->validate([
            'cedula'            => 'nullable|digits_between:6,10',
            'relationship'      => 'required|string|max:100',
            'first_name'        => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_name'       => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'last_name'         => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_last_name'  => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'email'             => ['required', 'string', 'max:255', new \App\Rules\StrictEmail()],
            'phone_whatsapp'    => ['required', 'string', 'max:50', new \App\Rules\VenezuelanPhone()],
            'phone_local'       => ['nullable', 'string', 'max:50', new \App\Rules\VenezuelanPhone()],
            'facebook'          => 'nullable|string|max:255',
            'workplace_address' => 'nullable|string',
            'job_title'         => 'nullable|string|max:255',
        ]);

        $validated['email']          = mb_strtolower(trim($validated['email']), 'UTF-8');
        $validated['phone_whatsapp'] = \App\Rules\VenezuelanPhone::normalize($validated['phone_whatsapp']);
        $validated['phone_local']    = !empty($validated['phone_local']) ? \App\Rules\VenezuelanPhone::normalize($validated['phone_local']) : null;

        $representative->update($validated);

        return redirect()->route('representatives.index')->with('success', 'Datos de contacto del representante actualizados.');
    }
}
