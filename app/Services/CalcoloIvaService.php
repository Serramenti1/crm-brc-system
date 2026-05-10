<?php

namespace App\Services;

class CalcoloIvaService
{
    public function calcolaDaPreventivo($preventivo)
    {
        $totaleProdottiCliente = 0;
        $totaleServiziCliente = 0;

        $prodottiNonSignificativiCliente = 0;
        $beniSignificativiCosto = 0;
        $markupBeniSignificativi = 0;

        foreach ($preventivo->righeProdotti as $riga) {
            $quantita = (float) ($riga->quantita ?? 1);

            $totaleProdottiCliente += (float) $riga->totale_cliente;

            foreach ($riga->servizi as $servizio) {
                $totaleServiziCliente += (float) $servizio->prezzo_cliente * $quantita;
            }

            if ($riga->bene_significativo) {
                $costoBene = (float) $riga->totale_costo;
                $prezzoClienteBene = (float) $riga->totale_cliente;

                $beniSignificativiCosto += $costoBene;
                $markupBeniSignificativi += max(0, $prezzoClienteBene - $costoBene);
            } else {
                $prodottiNonSignificativiCliente += (float) $riga->totale_cliente;
            }
        }

        $totaleCliente = $totaleProdottiCliente + $totaleServiziCliente;

        $tipoIntervento = null;

        if ($preventivo->commessa) {
            $tipoIntervento = $preventivo->commessa->tipoIntervento;
        }

        $imponibile4 = 0;
        $imponibile10 = 0;
        $imponibile22 = 0;

        $iva4 = 0;
        $iva10 = 0;
        $iva22 = 0;

        $beniSignificativiAl10 = 0;
        $beniSignificativiAl22 = 0;

        if ($tipoIntervento && $tipoIntervento->modalita_iva == 'beni_significativi') {

            $aliquotaAgevolata = $tipoIntervento->ivaPrincipale
                ? (float) $tipoIntervento->ivaPrincipale->aliquota
                : 10;

            $aliquotaOrdinaria = $tipoIntervento->ivaSecondaria
                ? (float) $tipoIntervento->ivaSecondaria->aliquota
                : 22;

            $quotaAgevolata = $totaleServiziCliente + $prodottiNonSignificativiCliente + $markupBeniSignificativi;

            $beniSignificativiAgevolati = min($beniSignificativiCosto, $quotaAgevolata);
            $beniSignificativiOrdinari = max(0, $beniSignificativiCosto - $quotaAgevolata);

            $beniSignificativiAl10 = $beniSignificativiAgevolati;
            $beniSignificativiAl22 = $beniSignificativiOrdinari;

            if ($aliquotaAgevolata == 4) {
                $imponibile4 = $quotaAgevolata + $beniSignificativiAgevolati;
                $iva4 = $imponibile4 * ($aliquotaAgevolata / 100);
            } elseif ($aliquotaAgevolata == 10) {
                $imponibile10 = $quotaAgevolata + $beniSignificativiAgevolati;
                $iva10 = $imponibile10 * ($aliquotaAgevolata / 100);
            } else {
                $imponibile22 += $quotaAgevolata + $beniSignificativiAgevolati;
                $iva22 += ($quotaAgevolata + $beniSignificativiAgevolati) * ($aliquotaAgevolata / 100);
            }

            if ($aliquotaOrdinaria == 4) {
                $imponibile4 += $beniSignificativiOrdinari;
                $iva4 += $beniSignificativiOrdinari * ($aliquotaOrdinaria / 100);
            } elseif ($aliquotaOrdinaria == 10) {
                $imponibile10 += $beniSignificativiOrdinari;
                $iva10 += $beniSignificativiOrdinari * ($aliquotaOrdinaria / 100);
            } else {
                $imponibile22 += $beniSignificativiOrdinari;
                $iva22 += $beniSignificativiOrdinari * ($aliquotaOrdinaria / 100);
            }

        } elseif ($tipoIntervento && $tipoIntervento->ivaPrincipale) {

            $aliquota = (float) $tipoIntervento->ivaPrincipale->aliquota;

            if ($aliquota == 4) {
                $imponibile4 = $totaleCliente;
                $iva4 = $imponibile4 * ($aliquota / 100);
            } elseif ($aliquota == 10) {
                $imponibile10 = $totaleCliente;
                $iva10 = $imponibile10 * ($aliquota / 100);
            } else {
                $imponibile22 = $totaleCliente;
                $iva22 = $imponibile22 * ($aliquota / 100);
            }

        } else {

            $imponibile22 = $totaleCliente;
            $iva22 = $imponibile22 * 0.22;

        }

        $totaleIva = $iva4 + $iva10 + $iva22;
        $totaleConIva = $totaleCliente + $totaleIva;

        
            return [
            'totale_prodotti_cliente' => $totaleProdottiCliente,
            'totale_servizi_cliente' => $totaleServiziCliente,
            'totale_cliente' => $totaleCliente,

            'prodotti_non_significativi_cliente' => $prodottiNonSignificativiCliente,
            'beni_significativi_costo' => $beniSignificativiCosto,
            'markup_beni_significativi' => $markupBeniSignificativi,

            'beni_significativi_al_10' => $beniSignificativiAl10,
            'beni_significativi_al_22' => $beniSignificativiAl22,

            'imponibile_4' => $imponibile4,
            'imponibile_10' => $imponibile10,
            'imponibile_22' => $imponibile22,

            'iva_4' => $iva4,
            'iva_10' => $iva10,
            'iva_22' => $iva22,

            'totale_iva' => $totaleIva,
            'totale_con_iva' => $totaleConIva,
        ];
    }
}