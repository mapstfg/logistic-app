@extends('layouts.bootstrap')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Nueva Transferencia a Farmacia</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('bodega.transfers.store') }}" method="POST" id="transfer-form">
                        @csrf

                        <div class="mb-4">
                            <label class="fw-bold mb-2">Seleccionar Items para Transferir</label>
                            <div id="items-container">
                                <div class="item-row card p-3 mb-3 border-light bg-light">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-3">
                                            <label class="small text-muted">Tipo</label>
                                            <select name="items[0][type]" class="form-select type-select" required
                                                onchange="updateItemList(this)">
                                                <option value="MEDICAMENTO">MEDICAMENTO</option>
                                                <option value="INSUMO">INSUMO</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="small text-muted">Item (disponible en bodega)</label>
                                            <select name="items[0][item_id]" class="form-select item-select" required>
                                                <option value="">Seleccione...</option>
                                                <!-- Populado por JS -->
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="small text-muted">Cantidad</label>
                                            <input type="number" name="items[0][quantity]" class="form-control" min="1"
                                                required>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-sm btn-outline-danger w-100 remove-item"
                                                style="display:none">Quitar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm" id="add-item">+ Agregar otro
                                item</button>
                        </div>

                        <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                            <a href="{{ route('bodega.transfers.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary btn-lg">Realizar Transferencia</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const medicines = {!! json_encode($medicines->map(fn($m) => ['id' => $m->id, 'name' => $m->name . " (" . $m->stock_bodega . ")"])) !!};
        const supplies = {!! json_encode($supplies->map(fn($s) => ['id' => $s->id, 'name' => $s->name . " (" . $s->stock_bodega . ")"])) !!};

        function updateItemList(typeSelect) {
            const row = typeSelect.closest('.item-row');
            const itemSelect = row.querySelector('.item-select');
            const type = typeSelect.value;
            const list = type === 'MEDICAMENTO' ? medicines : supplies;

            itemSelect.innerHTML = '<option value="">Seleccione...</option>';
            list.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                itemSelect.appendChild(option);
            });
        }

        let itemIndex = 1;
        document.getElementById('add-item').addEventListener('click', function () {
            const container = document.getElementById('items-container');
            const firstRow = container.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                input.setAttribute('name', name.replace('[0]', '[' + itemIndex + ']'));
                if (input.tagName !== 'SELECT' || !input.classList.contains('type-select')) {
                    input.value = '';
                }
            });

            const removeBtn = newRow.querySelector('.remove-item');
            removeBtn.style.display = 'block';
            removeBtn.onclick = function () { newRow.remove(); };

            container.appendChild(newRow);
            itemIndex++;
        });

        // Initial populate
        document.querySelectorAll('.type-select').forEach(s => updateItemList(s));
    </script>
@endsection