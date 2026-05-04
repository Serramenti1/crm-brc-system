<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use App\Models\Ordine;
use Illuminate\Support\ServiceProvider;

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
     View::composer('partials.menu', function ($view) {
        $conteggiOrdini = [
            'in_lavorazione' => Ordine::where('stato', 'in_lavorazione')->count(),
            'completo_attesa_merce' => Ordine::where('stato', 'completo_attesa_merce')->count(),
            'attesa_saldo_merce' => Ordine::where('stato', 'attesa_saldo_merce')->count(),
            'programmare_posa' => Ordine::where('stato', 'programmare_posa')->count(),
        ];

        $view->with('conteggiOrdini', $conteggiOrdini);
    });
    }
}
