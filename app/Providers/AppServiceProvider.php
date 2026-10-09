<?php

namespace App\Providers;

use App\Models\Campus;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::defaultView('pagination.campusbazar');
        Paginator::defaultSimpleView('pagination.campusbazar');

        View::composer(['layouts.app', 'layouts.admin'], function ($view) {
            $categories = collect();
            $campuses = collect();

            if (Schema::hasTable('categories')) {
                $categories = Category::active()->get();
            }

            if (Schema::hasTable('campuses')) {
                $campuses = Campus::active()->orderBy('name')->get();
            }

            $view->with('navCategories', $categories)
                ->with('navCampuses', $campuses);
        });
    }
}
