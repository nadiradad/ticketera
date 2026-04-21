<?php

namespace App\Services;

use App\Models\User;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class StaffProfileService
{
    /**
     * Crea o actualiza el registro en `usuarios` vinculado al usuario de la aplicación.
     */
    public function syncUsuarioProfile(User $user): ?Usuario
    {
        return DB::transaction(function () use ($user) {
            $rolUsuario = match ($user->rol) {
                'administrador' => 'admin',
                'tecnico' => 'tecnico',
                'recepcionista' => 'recepcionista',
                default => 'recepcionista',
            };

            $usuario = Usuario::query()->where('user_id', $user->id)->first()
                ?? Usuario::query()->where('email', $user->email)->first();

            $base = [
                'user_id' => $user->id,
                'nombre' => $user->name,
                'email' => $user->email,
                'rol' => $rolUsuario,
            ];

            if ($usuario === null) {
                return Usuario::query()->create($base + [
                    'password' => $user->getRawOriginal('password'),
                ]);
            }

            if ($user->wasChanged('password')) {
                $base['password'] = $user->getRawOriginal('password');
            }

            $usuario->update($base);

            return $usuario->fresh();
        });
    }
}
