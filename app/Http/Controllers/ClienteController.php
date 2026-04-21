<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::query()->orderBy('apellido')->orderBy('nombre')->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create(): View
    {
        return view('clientes.create');
    }

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
