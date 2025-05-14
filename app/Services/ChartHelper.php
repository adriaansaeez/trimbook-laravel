<?php

namespace App\Services;

use IcehouseVentures\LaravelChartjs\Facades\Chartjs;

class ChartHelper
{
    /**
     * Crea un gráfico vacío para mostrar cuando no hay datos
     * 
     * @return \IcehouseVentures\LaravelChartjs\Builder
     */
    public static function emptyChart()
    {
        return Chartjs::build()
            ->name("EmptyChart-" . uniqid())
            ->type("bar")
            ->size(["width" => 400, "height" => 200])
            ->labels([])
            ->datasets([
                [
                    'data' => [],
                    'label' => 'Sin datos',
                    'backgroundColor' => 'rgba(200, 200, 200, 0.2)',
                    'borderColor' => 'rgba(200, 200, 200, 0.5)'
                ]
            ])
            ->options([
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                        'display' => false
                    ],
                    'x' => [
                        'display' => false
                    ]
                ],
                'plugins' => [
                    'title' => [
                        'display' => true,
                        'text' => 'No hay datos disponibles para el período seleccionado',
                        'font' => [
                            'size' => 16
                        ]
                    ],
                    'legend' => [
                        'display' => false
                    ]
                ]
            ]);
    }
} 