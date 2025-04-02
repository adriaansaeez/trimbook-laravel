<?php 
  
namespace App\View\Components;

use App\Models\Reserva;
use Illuminate\View\Component;

class ListadoReservas extends Component
{
    public $reservas;
    public $esEstilista;

    public function __construct($clienteId = null, $estilistaId = null)
    {
        $this->esEstilista = $estilistaId !== null;

        if ($this->esEstilista) {
            $this->reservas = Reserva::with(['cliente', 'servicio'])
                ->where('estilista_id', $estilistaId)
                ->orderBy('fecha', 'desc')
                ->get();
        } elseif ($clienteId !== null) {
            $this->reservas = Reserva::with(['estilista.user', 'servicio'])
                ->where('user_id', $clienteId)
                ->orderBy('fecha', 'desc')
                ->get();
        } else {
            $this->reservas = collect(); // vacío si no hay id
        }
    }

    public function render()
    {
        return view('components.listado-reservas');
    }
}
