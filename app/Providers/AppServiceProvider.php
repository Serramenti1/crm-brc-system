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
    View::composer(['partials.menu', 'dashboard'], function ($view) {

        $conteggiOrdini = [
            'preparazione_contratto' => Ordine::where('stato', 'preparazione_contratto')->count(),
            'in_lavorazione' => Ordine::where('stato', 'in_lavorazione')->count(),
            'completo_attesa_merce' => Ordine::where('stato', 'completo_attesa_merce')->count(),
            'attesa_saldo_merce' => Ordine::where('stato', 'attesa_saldo_merce')->count(),
            'programmare_posa' => Ordine::where('stato', 'programmare_posa')->count(),
            'concluso' => Ordine::where('stato', 'concluso')->count(),
            'archiviato' => Ordine::where('stato', 'archiviato')->count(),
        ];

        $view->with('conteggiOrdini', $conteggiOrdini);
    });
}
}