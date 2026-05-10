<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    /**
     * GET /api/locations/states
     * Returns all Venezuelan states ordered alphabetically.
     */
    public function states()
    {
        $states = DB::table('estados')
            ->orderBy('estado')
            ->get(['id_estado as id', 'estado as name']);

        return response()->json($states);
    }

    /**
     * GET /api/locations/states/{id}/municipalities
     * Returns all municipalities for a given state.
     */
    public function municipalities($stateId)
    {
        $municipalities = DB::table('municipios')
            ->where('id_estado', $stateId)
            ->orderBy('municipio')
            ->get(['id_municipio as id', 'municipio as name']);

        return response()->json($municipalities);
    }

    /**
     * GET /api/locations/municipalities/{id}/parishes
     * Returns all parishes for a given municipality.
     */
    public function parishes($municipioId)
    {
        $parishes = DB::table('parroquias')
            ->where('id_municipio', $municipioId)
            ->orderBy('parroquia')
            ->get(['id_parroquia as id', 'parroquia as name']);

        return response()->json($parishes);
    }
}
