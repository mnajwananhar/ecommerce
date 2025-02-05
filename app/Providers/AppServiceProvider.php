<?php

namespace App\Providers;

use App\Models\CartItem;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\View\Composers\CategoryDropdownComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (config('app.env') !== 'local') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $cartItemCount = Auth::check()
                ? CartItem::where('user_id', Auth::id())->count()
                : 0; // Jika user belum login, jumlah keranjang 0

            // Bagikan variabel $cartItemCount ke semua view
            $view->with('cartItemCount', $cartItemCount);
        });

        View::composer('components.category-dropdown', CategoryDropdownComposer::class);
    }
}
