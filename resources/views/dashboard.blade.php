@extends('layouts.app')

@section('content')    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white w- overflow-hidden shadow-sm sm:rounded-lg w-1/4">
                <div class="p-5 flex flex-col">
                    <h5 class="text-blue-700 ">Book now your next trim</h5>
                    <a class="text-gray-900 border rounded p-1 w-1/2 text-white bg-blue-500 mt-2" href="">Book</a>
                    
                </div>
            </div>
        </div>
    </div>
    
@endsection
