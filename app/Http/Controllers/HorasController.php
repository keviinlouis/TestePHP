<?php

namespace App\Http\Controllers;

use App\Models\Hora;
use App\Services\RandomService;

class HorasController extends Controller
{
    /**
     * @var RandomService
     */
    private $randomService;

    /**
     * HorasController constructor.
     * @param RandomService $randomService
     */
    public function __construct(RandomService $randomService)
    {
        $this->randomService = $randomService;
    }

    /**
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getHoras()
    {
        $horas = $this->randomService->getHoras();
        $horasOcorrencias = collect();
        $intervaloOcorrencia = [
            'maior' => 0,
            'menor' => 0,
        ];
        $quantidadeMaiorDeOcorrencia = 0;
        $horaMaiorOcorrencia = null;

        for ($i = 0; $i < $horas->count(); $i++) {
            /** @var Hora $hora */
            $hora = $horas[$i];

            $horaOcorrencia = $horasOcorrencias->get($hora->getHora(), 0);

            $quantidadeOcorrenciaHora = $horaOcorrencia + 1;

            $horasOcorrencias->put($hora->getHora(), $quantidadeOcorrenciaHora);

            if ($quantidadeOcorrenciaHora > $quantidadeMaiorDeOcorrencia) {
                $horaMaiorOcorrencia = $hora->getHora();
                $quantidadeMaiorDeOcorrencia = $quantidadeOcorrenciaHora;
            }
            if ($i > 0) {
                /** @var Hora $horaAnterior */
                $horaAnterior = $horas[$i - 1];
                $diferenca = $horaAnterior->toCarbon()->diff($hora->toCarbon());
                if (!$intervaloOcorrencia['maior'] instanceOf \DateInterval || $diferenca->h > $intervaloOcorrencia['maior']->h) {
                    $intervaloOcorrencia['maior'] = $diferenca;
                } else if ($diferenca->h == $intervaloOcorrencia['maior']->h && $diferenca->i > $intervaloOcorrencia['maior']->i) {
                    $intervaloOcorrencia['maior'] = $diferenca;
                }

                if (!$intervaloOcorrencia['menor'] instanceOf \DateInterval || $diferenca->h < $intervaloOcorrencia['menor']->h) {
                    $intervaloOcorrencia['menor'] = $diferenca;
                } else if ($diferenca->h == $intervaloOcorrencia['menor']->h && $diferenca->i < $intervaloOcorrencia['menor']->i) {
                    $intervaloOcorrencia['menor'] = $diferenca;
                }
            }
        }

        $mediaOcorrenciaHora = $horasOcorrencias->avg();


        return response()->json([
            'mediaOcorrenciaHora' => $mediaOcorrenciaHora,
            'horaMaiorOcorrencia' => $horaMaiorOcorrencia,
            'intervaloOcorrencia' => [
                'maior_diferenca_em_minutos' => $intervaloOcorrencia['maior']->i,
                'menor_diferenca_em_minutos'=> $intervaloOcorrencia['menor']->i
            ],
            'horasOcorrencias' => $horasOcorrencias
        ]);

    }


}
