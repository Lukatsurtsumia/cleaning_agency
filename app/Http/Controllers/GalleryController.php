<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\ImageManager;

class GalleryController extends Controller
{
    /** Mirrors the four service families so a job is filed under what was done. */
    public const CATEGORIES = ['hotelier', 'immeubles', 'bureaux', 'specifiques'];

    public function index(Request $request)
    {
        $category = $request->query('category');

        $galleries = Gallery::query()
            ->when($category, fn ($query) => $query->where('category', $category))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('gallery.index', [
            'galleries' => $galleries,
            'categories' => self::CATEGORIES,
            'activeCategory' => $category,
        ]);
    }

    public function show(Gallery $gallery)
    {
        $gallery->load('images');

        return view('gallery.show', compact('gallery'));
    }

    public function create()
    {
        return view('gallery.create', ['categories' => self::CATEGORIES]);
    }

    public function store(Request $request)
    {
        $data = $this->validateGallery($request);

        $gallery = Gallery::create([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
        ]);

        if ($request->hasFile('cover_image')) {
            $gallery->update(['cover_image' => $this->storeResizedImage($request->file('cover_image'), $gallery->id)]);
        }

        foreach ($request->file('images', []) as $image) {
            GalleryImage::create([
                'gallery_id' => $gallery->id,
                'path' => $this->storeResizedImage($image, $gallery->id),
            ]);
        }

        return redirect()->route('gallery.show', $gallery)->with('status', 'Project added.');
    }

    public function edit(Gallery $gallery)
    {
        $gallery->load('images');

        return view('gallery.edit', ['gallery' => $gallery, 'categories' => self::CATEGORIES]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $data = $this->validateGallery($request);

        $gallery->update([
            'title' => $data['title'],
            'category' => $data['category'],
            'description' => $data['description'] ?? null,
        ]);

        if ($request->hasFile('cover_image')) {
            $gallery->update(['cover_image' => $this->storeResizedImage($request->file('cover_image'), $gallery->id)]);
        }

        foreach ($request->file('images', []) as $image) {
            GalleryImage::create([
                'gallery_id' => $gallery->id,
                'path' => $this->storeResizedImage($image, $gallery->id),
            ]);
        }

        return redirect()->route('gallery.show', $gallery)->with('status', 'Project updated.');
    }

    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return redirect()->route('gallery.index')->with('status', 'Project removed.');
    }

    private function validateGallery(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', Rule::in(self::CATEGORIES)],
            'description' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'images.*' => ['nullable', 'image', 'max:5120'],
        ]);
    }

    private function storeResizedImage(UploadedFile $file, int $galleryId): string
    {
        $manager = new ImageManager(new Driver);

        $image = $manager->decodePath($file->getRealPath())
            ->scaleDown(width: 1600)
            ->encode(new JpegEncoder(quality: 82));

        $path = "gallery/{$galleryId}/".Str::uuid().'.jpg';

        Storage::disk('public')->put($path, (string) $image);

        return $path;
    }
}
