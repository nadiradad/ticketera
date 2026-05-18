<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $query = Cliente::query()->orderBy('apellido')->orderBy('nombre');

        if ($request->filled('dni')) {
            $query->where('dni', 'like', '%'.$request->dni.'%');
        }

        $clientes = $query->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

    /*
        public function store(Request $request): RedirectResponse
        {
            $data = $request->validate([
                'nombre' => ['required', 'string', 'max:255'],
                'apellido' => ['required', 'string', 'max:255'],
                'dni' => ['required', 'string', 'max:32', 'unique:clientes,dni'],
                'telefono' => ['required', 'string', 'max:50'],
                'correo' => ['nullable', 'email', 'max:255'],
            ]);

            Cliente::query()->create($data);

            return redirect()->route('clientes.index')->with('status', 'Cliente creado.');
        }*/
    public function store(Request $request)
    {
        $cliente = Cliente::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'dni' => $request->dni,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
        ]);

        Equipo::create([
            'cliente_id' => $cliente->id,
            'marca' => $request->marca,
            'modelo' => $request->modelo,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('clientes.index');
    }

    public function edit(Cliente $cliente): View
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'dni' => ['required', 'string', 'max:32', 'unique:clientes,dni,'.$cliente->id],
            'telefono' => ['required', 'string', 'max:50'],
            'correo' => ['nullable', 'email', 'max:255'],
        ]);

        $cliente->update($data);

        return redirect()->route('clientes.index')->with('status', 'Cliente actualizado.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('status', 'Cliente eliminado.');
    }
}
