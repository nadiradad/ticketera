<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Services\StaffProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserStaffController extends Controller
{
    public function __construct(
        private readonly StaffProfileService $staffProfileService,
    ) {}

    public function index(): View
    {
        $users = User::query()->orderBy('name')->get();

        return view('admin.staff.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.staff.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'rol' => ['required', Rule::in(['tecnico', 'recepcionista'])],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'rol' => $data['rol'],
        ]);

        $this->staffProfileService->syncUsuarioProfile($user);

        return redirect()->route('admin.staff.index')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function edit(User $staff): View|RedirectResponse
    {
        if ($staff->isAdministrador()) {
            abort(403);
        }

        return view('admin.staff.edit', ['user' => $staff]);
    }

    public function update(Request $request, User $staff): RedirectResponse
    {
        if ($staff->isAdministrador()) {
            abort(403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staff->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'rol' => ['required', Rule::in(['tecnico', 'recepcionista'])],
        ]);

        $staff->name = $data['name'];
        $staff->email = $data['email'];
        $staff->rol = $data['rol'];

        if (! empty($data['password'])) {
            $staff->password = $data['password'];
        }

        $staff->save();

        $this->staffProfileService->syncUsuarioProfile($staff);

        return redirect()->route('admin.staff.index')
            ->with('status', 'Usuario actualizado.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->id === auth()->id()) {
            return redirect()->route('admin.staff.index')
                ->withErrors(['delete' => 'No podés eliminar tu propio usuario.']);
        }

        if ($staff->isAdministrador()) {
            return redirect()->route('admin.staff.index')
                ->withErrors(['delete' => 'No se puede eliminar un usuario administrador.']);
        }

        if ($staff->usuario && Ticket::query()->where('tecnico_id', $staff->usuario->id)->exists()) {
            return redirect()->route('admin.staff.index')
                ->withErrors(['delete' => 'No se puede eliminar: el técnico tiene tickets asignados.']);
        }

        $staff->usuario?->delete();
        $staff->delete();

        return redirect()->route('admin.staff.index')
            ->with('status', 'Usuario eliminado.');
    }
}
