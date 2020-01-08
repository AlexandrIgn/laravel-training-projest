<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Region;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AdvertController extends Controller
{

    public function index(Region $region = null, Category $category = null)
    {
        $query = Advert::active()->with(['category', 'region'])->orderByDesc('id');

        if ($category) {
            $query->forCategory($category);
        }

        if ($region) {
            $query->forRgion($region);
        }

        $regions = $region
            ? $region->children()->orderBy('name')->getModels()
            : Region::roots()->orderBy('name')->getModels();

        $categories = $category
            ? $category->children()->defoultOrder('name')->getModels()
            : Category::whereIsRoot()->orderBy('name')->getModels();

        $adverts = $query->paginate(20);
        return view('adverts.index', compact('category', 'region', 'adverts', 'categories', 'regions'));
    }

    public function show(Advert $advert)
    {
        if (!($advert->isActive() || Gate::allows('show-advert', $advert))) {
            abort(403);
        }
        $user = Auth::user();

        return view('adverts.show', compact('advert'));
    }
}
