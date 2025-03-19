<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ListadoDinamico extends Component
{
    public $items;
    public $columns;
    public $editRoute;
    public $deleteRoute;

    public function __construct($items, $columns, $editRoute = null, $deleteRoute = null)
    {
        $this->items = $items;
        $this->columns = $columns;
        $this->editRoute = $editRoute;
        $this->deleteRoute = $deleteRoute;
    }

    public function render()
    {
        return view('components.listado-dinamico');
    }
}
