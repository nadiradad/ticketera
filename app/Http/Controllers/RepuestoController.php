<?php

namespace App\Http\Controllers;

use App\Models\Repuesto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RepuestoController extends Controller
{
    public function index(): View
    {
        $repuestos = Repuesto::query()->orderBy('nombre')->get();

        return view('repuestos.index', compact('repuestos'));
    }

    public function create(): View
    {
        return view('repuestos.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio_base' => ['required', 'numeric', 'min:0'],
        ]);

        Repuesto::query()->create($data);

        return redirect()->route('repuestos.index')->with('status', 'Repuesto creado.');
    }

    public function edit(Repuesto $repuesto): View
    {
        return view('repuestos.edit', compact('repuesto'));
    }

    public function update(Request $request, Repuesto $repuesto): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'precio_base' => ['required', 'numeric', 'min:0'],
        ]);

        $repuesto->update($data);

        return redirect()->route('repuestos.index')->with('status', 'Repuesto actualizado.');
    }

    public function destroy(Repuesto $repuesto): RedirectResponse
    {
        $repuesto->delete();

        return redirect()->route('repuestos.index')->with('status', 'Repuesto eliminado.');
    }
}
