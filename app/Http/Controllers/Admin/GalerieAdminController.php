<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Galerie;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class GalerieAdminController extends Controller
{
    public function index()
    {
        $images = Galerie::orderBy('ordre')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.galerie.index', compact('images'));
    }

    public function create()
    {
        return view('admin.galerie.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie' => 'required|in:terrain,formation,recolte,producteurs,evenement',
            'lieu' => 'nullable|string',
            'date_prise' => 'required|date',
            'image' => 'required|image|max:5120'
        ]);

        if ($request->hasFile('image')) {
            $result = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'galerie'
            ]);

            $validated['image'] = $result->getSecurePath();
            $validated['image_public_id'] = $result->getPublicId();
        }

        $validated['est_publie'] = true;
        $validated['ordre'] = Galerie::max('ordre') + 1;

        Galerie::create($validated);

        return redirect()->route('admin.galerie.index')
            ->with('success', 'Image ajoutée à la galerie');
    }

    public function edit(Galerie $galerie)
    {
        return view('admin.galerie.edit', compact('galerie'));
    }

    public function update(Request $request, Galerie $galerie)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'categorie' => 'required|in:terrain,formation,recolte,producteurs,evenement',
            'lieu' => 'nullable|string',
            'date_prise' => 'required|date',
            'est_publie' => 'boolean',
            'image' => 'nullable|image|max:5120'
        ]);

        if ($request->hasFile('image')) {
            if ($galerie->image_public_id) {
                Cloudinary::destroy($galerie->image_public_id);
            }
            $result = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'galerie'
            ]);

            $validated['image'] = $result->getSecurePath();
            $validated['image_public_id'] = $result->getPublicId();
        }

        $galerie->update($validated);

        return redirect()->route('admin.galerie.index')
            ->with('success', 'Image mise à jour');
    }

    public function destroy(Galerie $galerie)
    {
        if ($galerie->image_public_id) {
            Cloudinary::destroy($galerie->image_public_id);
        }
        $galerie->delete();
        return redirect()->route('admin.galerie.index')
            ->with('success', 'Image supprimée');
    }

    public function reorder(Request $request)
    {
        foreach ($request->ordre as $id => $position) {
            Galerie::where('id', $id)->update(['ordre' => $position]);
        }
        return response()->json(['success' => true]);
    }
}
