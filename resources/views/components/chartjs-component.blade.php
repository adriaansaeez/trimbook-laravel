@props(['chart'])

<div>
    @if($chart)
        {!! $chart->render() !!}
    @else
        <div class="flex items-center justify-center h-full text-gray-500">
            <p>No hay datos disponibles para este período</p>
        </div>
    @endif
</div> 