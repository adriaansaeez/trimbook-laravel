@extends('layouts.app')

@section('content')    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="card p-5 flex flex-col">
                    <x-calendario-semanal :estilista-id="1" :semana="request('semana')" />
                </div>
            </div>
    </div>
    
@endsection
