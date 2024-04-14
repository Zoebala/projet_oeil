<?php

namespace App\Filament\Widgets;

use App\Models\Section;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class SectionChart extends ChartWidget
{
    protected static ?string $heading = 'Effectifs par Section';
    protected static ?int $sort = 8;


    protected function getData(): array
    {
        $data = Trend::model(Section::class)
        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
        )
        ->perDay()
        ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
