<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zone;
use App\Models\IntrantStock;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function index()
    {
        $zones = Zone::withCount('intrantStocks')->get();
        return view('admin.zones.index', compact('zones'));
    }

    public function create()
    {
        return view('admin.zones.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|unique:zones|max:255']);
        Zone::create($validated);
        return redirect()->route('admin.zones.index')->with('success', 'Zone créée avec succès.');
    }

    public function edit(Zone $zone)
    {
        return view('admin.zones.edit', compact('zone'));
    }

    public function update(Request $request, Zone $zone)
    {
        $validated = $request->validate(['name' => 'required|string|unique:zones,name,' . $zone->id . '|max:255']);
        $zone->update($validated);
        return redirect()->route('admin.zones.index')->with('success', 'Zone mise à jour avec succès.');
    }

    public function destroy(Zone $zone)
    {
        // Vérifier si la zone est utilisée
        if (IntrantStock::where('zone', $zone->name)->exists()) {
            return back()->with('error', 'Impossible de supprimer cette zone car elle est associée à des stocks.');
        }

        $zone->delete();
        return redirect()->route('admin.zones.index')->with('success', 'Zone supprimée avec succès.');
    }
}
