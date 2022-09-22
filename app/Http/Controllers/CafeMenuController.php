<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Statamic\Facades\Entry;
use Statamic\Facades\Term;

class CafeMenuController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index($categorySlug = false)
    {
        switch ($categorySlug) {
            case false:
                return $this->categoriesView();

            case 'build-a-bowl':
                $this->shareBreadcrumbs('Build A Bowl');
                return view('site.cafe-menu.build-a-bowl');

            case 'build-a-burger':
                $this->shareBreadcrumbs('Build A Burger');
                return view('site.cafe-menu.build-a-burger');

            default:
                return $this->menuItemsView($categorySlug);
        }
    }

    private function categoriesView()
    {
        $categories = collect([
            'Snacks',
            'Build A Bowl',
            'Salads',
            'Build A Burger',
            'Wraps',
            'Flatbreads',
            "Makin' Muscle Corner",
            'Kids',
            'Smoothies',
            'Sides',
        ]);

        View::share('page', (object) ['slug' => 'cafe-menu', 'title' => 'Cafe Menu']);

        return view('site.cafe-menu.index', compact('categories'));
    }

    private function menuItemsView($categorySlug)
    {
        $category = Term::query()
            ->where('slug', $categorySlug)
            ->where('taxonomy', 'cafe_menu_categories')
            ->first();

        if (!$category) {
            abort(404, 'Page not found');
        }

        $menuItems = Entry::query()
            ->where('collection', 'cafe_menu_items')
            ->whereTaxonomy('cafe_menu_categories::' . $categorySlug)
            ->orderBy('order')
            ->get();

        $this->shareBreadcrumbs($category->title);

        return view('site.cafe-menu.items', compact('category', 'menuItems'));
    }

    private function shareBreadcrumbs($title)
    {
        View::share('breadcrumbs', [
            (object) ['url' => '/cafe-menu', 'title' => 'Cafe Menu'],
            (object) ['title' => $title],
        ]);
    }
}
