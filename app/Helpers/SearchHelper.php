<?php

namespace App\Helpers;

class SearchHelper
{
    public static function applyMultiWordSearch($query, $campi, $valore)
    {
        if (!$valore) return $query;

        $parole = explode(' ', trim($valore));

        return $query->where(function ($q) use ($parole, $campi) {
            foreach ($parole as $parola) {
                $q->where(function ($sub) use ($parola, $campi) {
                    foreach ($campi as $campo) {
                        $sub->orWhere($campo, 'like', $parola . '%');
                    }
                });
            }
        });
    }
}