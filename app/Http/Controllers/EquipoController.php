<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Equipo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipoController extends Controller
{
    public function index(): View
    {
        $equipos = Equipo::query()->with('cliente')->orderBy('marca')->get();

        return view('equipos.index', compact('equipos'));
    }

    public function create(): View
    {
        $clientes = Cliente::query()->orderBy('apellido')->orderBy('nombre')->get();

        return view('equipos.create', compact('clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
        ]);

        Equipo::query()->create($data);

        return redirect()->route('equipos.index')->with('status', 'Equipo creado.');
    }

    public function edit(Equipo $equipo): View
    {
        $clientes = Cliente::query()->orderBy('apellido')->orderBy('nombre')->get();

        return view('equipos.edit', compact('equipo', 'clientes'));
    }

    public function update(Request $request, Equipo $equipo): RedirectResponse
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'marca' => ['required', 'string', 'max:255'],
            'modelo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
        ]);

        $equipo->update($data);

        return redirect()->route('equipos.index')->with('status', 'Equipo actualizado.');
    }

    public function destroy(Equipo $equipo): RedirectResponse
    {
        $equipo->delete();

        return redirect()->route('equipos.index')->with('status', 'Equipo eliminado.');
    }
}
