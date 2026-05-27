{{--  --}}

<div class="row" id="{{ $id }}">
    {{-- Botón "Select All" --}}
    <div class="col-12 mb-3">
        <div class="form-check">
            <input 
                type="checkbox" 
                class="form-check-input" 
                id="select-all-{{ $id }}" 
                onclick="toggleSelectAll('{{ $id }}')"
            />
            <label class="form-check-label" for="select-all-{{ $id }}">Select All</label>
        </div>
    </div>

    {{-- Protocol Options --}}
    @foreach ($objectsArray as $object)
        <div class="col-sm-6 col-md-3">
            <div class="card border-0">
                <div class="card-body">
                    {{-- Icono SVG --}}
                    <div class="text-muted text-right mb-4">
                        <svg
                            class="c-icon c-icon-2xl"
                            data-toggle="popover"
                            data-trigger="hover"
                            title="Details: "
                            data-html="true"
                            data-content="Diagram color for protocol {{ $object->messagePopUp ?? 'Unknown' }}">
                            <use
                                fill="{{ $object->iconColor ?? '#000000' }}"
                                xlink:href="assets/icons/coreui/free-symbol-defs.svg#cui-{{ $object->iconName ?? 'default-icon' }}">
                            </use>
                        </svg>
                    </div>

                    {{-- Nombre del Protocolo --}}
                    <div class="text-value-lg">{{ $object->showName ?? 'Unnamed' }}</div>
                    <span class="badge badge-secondary hide" id="packetAmtIndicator-{{ $object->nameId ?? 'unknown' }}">
                        Loading...
                    </span>

                    {{-- Checkbox --}}
                    <small class="text-muted text-uppercase font-weight-bold">
                        <div class="form-check form-check-inline mr-1">
                            <input
                                class="form-check-input protocol-selector-checkbox"
                                id="protocol-{{ $object->nameId ?? 'unknown' }}"
                                type="checkbox"
                                name="protocols[]"
                                value="{{ $object->nameId ?? '' }}"
                                {{ $object->isSelected ? 'checked' : '' }}
                            />
                            <label class="form-check-label">Display</label>
                        </div>
                    </small>
                </div>
            </div>
        </div>
    @endforeach
</div>
