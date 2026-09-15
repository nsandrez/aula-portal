@extends('layouts.app')

@section('titulo', 'Matrículas y Estudiantes')
@section('encabezado', 'Módulo de Matrículas Escolares')

@section('contenido')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
                <span>Nómina Escolar 2026</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Registro de Matrículas y Estudiantes
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                Asignación de alumnos a sus respectivos cursos, número de lista y datos de contacto de apoderados.
            </p>
        </div>

        @if(auth()->user()?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex items-center gap-3">
                <button type="button" data-abrir-modal="modal-nueva-matricula" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                    <span>Nueva Matrícula</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Lista de Estudiantes Matriculados en Blanco -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/70">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Estudiantes Matriculados</h3>
            <span class="text-xs text-slate-500 font-mono">{{ $matriculas->count() }} alumnos activos</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/70 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 font-semibold">N° Lista</th>
                        <th class="px-5 py-3 font-semibold">Estudiante</th>
                        <th class="px-5 py-3 font-semibold">RUT</th>
                        <th class="px-5 py-3 font-semibold">Curso Asignado</th>
                        <th class="px-5 py-3 font-semibold">Apoderado Responsable</th>
                        <th class="px-5 py-3 text-center font-semibold">Estado</th>
                        <th class="px-5 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($matriculas as $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-slate-400 font-medium">
                                {{ str_pad((string)$m->numero_lista, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                {{ $m->estudiante?->name }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">
                                {{ $m->estudiante?->rut }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 font-bold border border-slate-200 text-xs">
                                    {{ $m->curso?->nombre }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $m->apoderado?->name ?? 'No registrado' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($m->estado === 'regular')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Regular
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Retirado
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                @if(auth()->user()?->esSuperUsuario())
                                    <button type="button" onclick="editarMatricula({{ $m->id }}, {{ $m->numero_lista }}, '{{ $m->estado }}', {{ $m->apoderado_id ?? 'null' }})" class="text-xs font-bold text-purple-600 hover:text-purple-700 cursor-pointer" title="Solo SuperUsuario">
                                        Editar
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">Solo lectura</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-500">
                                No se encontraron matrículas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: NUEVA MATRÍCULA                                   -->
<!-- ======================================================== -->
<div id="modal-nueva-matricula" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Nueva Matrícula Escolar</h3>
                <p class="text-xs text-slate-500">Inscribe a un alumno en un curso asignando su número de lista</p>
            </div>
            <button type="button" data-cerrar-modal="modal-nueva-matricula" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('matriculas.guardar') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="matricula-estudiante" class="block text-xs font-semibold text-slate-700 mb-1">Estudiante</label>
                <select id="matricula-estudiante" name="estudiante_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Selecciona el estudiante --</option>
                    @foreach($estudiantes ?? [] as $estudiante)
                        <option value="{{ $estudiante->id }}">{{ $estudiante->name }} ({{ $estudiante->rut ?? $estudiante->email }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="matricula-curso" class="block text-xs font-semibold text-slate-700 mb-1">Curso de Destino</label>
                <select id="matricula-curso" name="curso_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Selecciona el curso --</option>
                    @foreach($cursos ?? [] as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }} ({{ $c->nivel }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="matricula-lista" class="block text-xs font-semibold text-slate-700 mb-1">Número de Lista</label>
                    <input type="number" id="matricula-lista" name="numero_lista" value="{{ ($matriculas->max('numero_lista') ?? 0) + 1 }}" min="1" max="60" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label for="matricula-anio" class="block text-xs font-semibold text-slate-700 mb-1">Año Lectivo</label>
                    <input type="number" id="matricula-anio" name="anio" value="2026" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label for="matricula-apoderado" class="block text-xs font-semibold text-slate-700 mb-1">Apoderado Responsable</label>
                <select id="matricula-apoderado" name="apoderado_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Sin apoderado registrado --</option>
                    @foreach($apoderados ?? [] as $apoderado)
                        <option value="{{ $apoderado->id }}">{{ $apoderado->name }} ({{ $apoderado->rut ?? $apoderado->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-nueva-matricula" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-600 shadow-xs">
                    Completar Matrícula
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: EDITAR MATRÍCULA (SOLO SUPERUSUARIO)               -->
<!-- ======================================================== -->
<div id="modal-editar-matricula" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-[10px] font-bold uppercase mb-1">
                    Exclusivo SuperUsuario
                </div>
                <h3 class="text-base font-bold text-slate-900">Editar Matrícula Escolar</h3>
            </div>
            <button type="button" data-cerrar-modal="modal-editar-matricula" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="form-editar-matricula" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="editar-matricula-lista" class="block text-xs font-semibold text-slate-700 mb-1">Número de Lista</label>
                    <input type="number" id="editar-matricula-lista" name="numero_lista" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label for="editar-matricula-estado" class="block text-xs font-semibold text-slate-700 mb-1">Estado de Matrícula</label>
                    <select id="editar-matricula-estado" name="estado" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                        <option value="regular">Regular</option>
                        <option value="retirado">Retirado</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="editar-matricula-apoderado" class="block text-xs font-semibold text-slate-700 mb-1">Apoderado</label>
                <select id="editar-matricula-apoderado" name="apoderado_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Sin apoderado --</option>
                    @foreach($apoderados ?? [] as $apoderado)
                        <option value="{{ $apoderado->id }}">{{ $apoderado->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-editar-matricula" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-bold rounded-xl hover:bg-purple-700 shadow-xs">
                    Actualizar Matrícula
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function editarMatricula(id, lista, estado, apoderadoId) {
        const form = document.getElementById('form-editar-matricula');
        form.action = '/matriculas/' + id;
        document.getElementById('editar-matricula-lista').value = lista;
        document.getElementById('editar-matricula-estado').value = estado;
        document.getElementById('editar-matricula-apoderado').value = apoderadoId || '';
        
        const modal = document.getElementById('modal-editar-matricula');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
</script>
@endsection
