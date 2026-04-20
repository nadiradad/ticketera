<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('equipo_id')->constrained('equipos');

            $table->foreignId('tecnico_id')->nullable()->constrained('usuarios');
            $table->foreignId('estado_actual_id')->constrained('estados');

            $table->text('descripcion');
            $table->decimal('monto_servicio', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->timestamp('fecha_creacion')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
