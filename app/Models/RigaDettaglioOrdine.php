<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RigaDettaglioOrdine extends Model
{
    protected $table = 'righe_dettaglio_ordine';

    protected $fillable = [
        'riga_ordine_id',
        'numero_progressivo',
        'posizione',
        'descrizione',
        'pezzi',
        'netto_brc',
        'sconto_1',
        'sconto_2',
        'sconto_3',
        'trasporto',
        'posa',
        'ricarico_percentuale',
    ];

    public function rigaOrdine()
    {
        return $this->belongsTo(RigaOrdine::class, 'riga_ordine_id');
    }

    public function fattoreSconti(): float
    {
        return (1 - $this->sconto_1 / 100)
            * (1 - $this->sconto_2 / 100)
            * (1 - $this->sconto_3 / 100);
    }

    // Listino di partenza del prodotto (unitario), ricostruito dal netto
    public function listinoProdotto(): float
    {
        $fattore = $this->fattoreSconti();

        return $fattore > 0 ? (float) $this->netto_brc / $fattore : 0;
    }

        // Listino di partenza del trasporto (unitario), ricostruito con gli stessi sconti del prodotto
    public function listinoTrasporto(): float
    {
        $fattore = $this->fattoreSconti();

        return $fattore > 0 ? (float) $this->trasporto / $fattore : 0;
    }

    // Totale listino unitario: listino prodotto + listino trasporto
    public function totaleListinoUnitario(): float
    {
        return $this->listinoProdotto() + $this->listinoTrasporto();
    }

    public function nettoClienteProdottoUnitario(): float
    {
        return (float) $this->netto_brc * (1 + $this->ricarico_percentuale / 100);
    }

    public function trasportoClienteUnitario(): float
    {
        return (float) $this->trasporto * (1 + $this->ricarico_percentuale / 100);
    }

    // Totale riga a cliente: (prodotto + trasporto con ricarico + posa) x pezzi
    public function nettoClienteTotale(): float
    {
        $unitario = $this->nettoClienteProdottoUnitario()
            + $this->trasportoClienteUnitario()
            + (float) $this->posa;

        return $unitario * (float) $this->pezzi;
    }

    // Sconto sul totale listino (trasporto incluso), posa esclusa
    public function scontoApplicato(): float
    {
        $totaleListino = $this->totaleListinoUnitario();

        if ($totaleListino <= 0) {
            return 0;
        }

        $nettoCliente = $this->nettoClienteProdottoUnitario() + $this->trasportoClienteUnitario();

        return (($totaleListino - $nettoCliente) / $totaleListino) * 100;
    }
}