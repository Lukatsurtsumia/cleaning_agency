<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class MainController extends Controller
{
    public function index()
    {
        // Service copy lives in the language files so FR and EN stay in step.
        $services = __('site.services.items');

        $pricing = collect(config('azurclean.pricing'))
            ->map(fn (array $tier) => $tier + [
                'name' => __("site.pricing.tiers.{$tier['key']}.name"),
                'for' => __("site.pricing.tiers.{$tier['key']}.for"),
                'includes' => __("site.pricing.tiers.{$tier['key']}.includes"),
            ])
            ->all();

        // Homepage preview: the most recent job from each of the four service
        // families, in service order, so the four cards always read as a clean
        // one-per-category spread rather than repeating a category. The full
        // set is on the dedicated gallery page.
        $order = ['immeubles', 'hotelier', 'bureaux', 'specifiques'];

        $galleries = Gallery::with('images')->latest()->get()
            ->unique('category')
            ->sortBy(fn ($gallery) => array_search($gallery->category, $order))
            ->take(4)
            ->values();

        return view('page.index', compact(
            'services',
            'pricing',
            'galleries',
        ));
    }
}
