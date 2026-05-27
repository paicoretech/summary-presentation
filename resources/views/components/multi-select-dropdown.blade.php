<div class="dropdown multi-select-dropdown">
    {{-- Dropdown button --}}
    <button 
        class="btn btn-outline-secondary btn-protocol dropdown-toggle w-100 text-left  d-flex align-items-center justify-content-between" 
        type="button" 
        id="dropdownMenuButton" 
        data-toggle="dropdown" 
        aria-haspopup="true" 
        aria-expanded="false">
        Select Protocols
        <img src="{{ asset('assets/icons/ionic-ios-arrow-down.svg') }}" alt="Dropdown Arrow" class="dropdown-arrow-icon" />
    </button>

    {{-- Dropdown menu --}}
    <div class="dropdown-menu dropdown-protocol"  aria-labelledby="dropdownMenuButton">
        {{-- Select All toggle --}}
        <div class="custom-control custom-switch d-flex align-items-center justify-content-between">
            
            <input 
                type="checkbox" 
                class="custom-control-input" 
                id="select-all" 
                {{-- onclick="toggleSelectAll(this)" --}}
                data-target=".protocol-checkbox"
            />
            <label class="custom-control-label" for="select-all">Select All</label>
        </div>

        {{-- Dynamic protocol options --}}
        @foreach ($objectsArray as $object)
            <div class="d-flex align-items-center justify-content-between  protocol-option">
                {{-- Icon and protocol name on the left --}}
                <div class="d-flex align-items-center protocol-name">
                    {{-- Circular icon with dynamic color --}}
                    <div class="icon-circle d-flex align-items-center justify-content-center me-2" style="background-color: {{ $object->iconColor }};">
                        
                    </div>
                    <span class="protocol-name m1">{{ $object->showName }}</span>
                </div>
                
                {{-- Checkbox aligned to the right --}}
                <input 
                    type="checkbox" 
                    class="form-check-inline custom-checkbox protocol-checkbox" 
                    id="protocol-{{ $object->nameId }}" 
                    value="{{ $object->nameId }}" 
                    name="{{ $name }}[]" 
                    {{ in_array($object->nameId, $selected) ? 'checked' : '' }}
                />
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    
</script>
@endpush
