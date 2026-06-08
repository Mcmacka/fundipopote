<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\TechnicianWork;

class PortfolioController extends Controller
{
    public function index()
    {
        $works = TechnicianWork::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('technician.portfolio.index', compact('works'));
    }

    public function create()
    {
        return view('technician.portfolio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('portfolio', 'public');

                TechnicianWork::create([
                    'user_id' => auth()->id(),
                    'title' => $request->title,
                    'description' => $request->description,
                    'image_path' => $path,
                    'is_visible' => true,
                ]);
            }

            return redirect()->route('technician.portfolio.index')
                ->with('success', 'Your portfolio project has been uploaded successfully!');
        }

        return back()->withErrors(['images' => 'Failed to upload images. Please try again.']);
    }

    /**
     * Toggle visibility for ALL images in the same container container/title
     */
    public function toggle($title)
    {
        // Vuta picha zote za fundi huyu zenye kichwa hiki cha habari
        $works = TechnicianWork::where('user_id', auth()->id())
            ->where('title', $title)
            ->get();

        if ($works->isEmpty()) {
            abort(404);
        }

        // Angalia hali ya sasa ya picha ya kwanza kisha ugeuze kwa zote
        $newVisibility = !$works->first()->is_visible;

        foreach ($works as $work) {
            $work->is_visible = $newVisibility;
            $work->save();
        }

        $status = $newVisibility ? 'is now visible to customers.' : 'is now hidden from customers.';
        return redirect()->route('technician.portfolio.index')
            ->with('success', "The project container \"{$title}\" {$status}");
    }

    /**
     * Delete ALL images inside the container from storage and database
     */
    public function destroy($title)
    {
        $works = TechnicianWork::where('user_id', auth()->id())
            ->where('title', $title)
            ->get();

        if ($works->isEmpty()) {
            abort(404);
        }

        // 1. Loop ya kufuta picha zote halisi kwenye disk (Storage)
        foreach ($works as $work) {
            if (Storage::disk('public')->exists($work->image_path)) {
                Storage::disk('public')->delete($work->image_path);
            }
            // 2. Futa rekodi yenyewe kwenye database
            $work->delete();
        }

        return redirect()->route('technician.portfolio.index')
            ->with('success', "The entire project container \"{$title}\" and its photos have been successfully deleted.");
    }
}