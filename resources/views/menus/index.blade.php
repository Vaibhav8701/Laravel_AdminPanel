@extends('layout.app')

@section('title', 'Menu Editor')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/menu.css') }}">

@endsection  

@section('content')
    @if (!has_permission('menus.index'))
        <script>window.location.href = "{{ route('access.denied') }}";</script>
    @else
    <div class="menu-editor-container">
        <!-- Header -->
        <div class="menu-editor-header">
            <h1>
                <i class="fas fa-bars"></i> Menu Editor
            </h1>
            <a href="{{ route('menu.index') }}" class="menu-editor-header-btn">
                <i class="fas fa-list"></i> Menu List
            </a>
        </div>

        <!-- Menu Editor Grid -->
        <div class="menu-editor-grid">
            <!-- Menu List -->
            <div class="menu-card">
                <div class="menu-card-header">
                    <i class="fas fa-layer-group"></i> Menu Items
                </div>
                <div class="menu-card-body">
                    <ul id="myEditor" class="sortablelist"></ul>
                </div>
            </div>

            <!-- Edit Item -->
            <div class="menu-card">
                <div class="menu-card-header">
                    <i class="fas fa-edit"></i> Edit Item
                </div>

                <div class="menu-card-body">
                    <form id="frmEdit">
                        <div class="menu-form-group">
                            <label for="text">Text *</label>
                            <input type="text" class="menu-form-input item-menu"
                                   name="text" id="text" required>
                        </div>

                        <div class="menu-form-group">
                            <label for="myEditor_icon">Icon</label>
                            <input type="text" class="menu-form-input"
                                   id="myEditor_icon" readonly placeholder="Click to select icon">
                            <input type="hidden" name="icon"
                                   class="item-menu" id="icon">
                        </div>

                        <div class="menu-form-group">
                            <label for="href">URL</label>
                            <input type="text" id="href"
                                   class="menu-form-input item-menu" name="href" placeholder="e.g., dashboard">
                        </div>

                        <div class="menu-form-group">
                            <label for="permission">Permission</label>
                            <select name="permission" class="menu-form-select item-menu" id="permission">
                                <option value="">-- Select Permission --</option>
                                @foreach ($permissions ?? [] as $perm)
                                    <option value="{{ $perm['permission_name'] }}">
                                        {{ $perm['permission_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="moduleid" class="item-menu" id="moduleid">
                        <input type="hidden" name="deletestatus" class="item-menu" id="deletestatus" value="1">
                        <input type="hidden" name="is_active" class="item-menu" id="is_active" value="1">
                    </form>
                </div>

                <div class="menu-card-footer">
                    @if (has_permission('menus.save'))
                    <button type="button" id="btnOutput" class="menu-btn menu-btn-save">
                        <i class="fas fa-save"></i> Save
                    </button>
                    <button type="button" id="btnUpdate" class="menu-btn menu-btn-update">
                        <i class="fas fa-sync-alt"></i> Update
                    </button>
                    @endif
                    <button type="button" id="btnAdd" class="menu-btn menu-btn-add">
                        <i class="fas fa-plus"></i> Add
                    </button>
                </div>
            </div>
        </div>

        <!-- JSON Output (Hidden) -->
        <div class="json-output-card" style="display: none;">
            <div class="menu-card-header">
                <i class="fas fa-code"></i> JSON Output
            </div>
            <div class="menu-card-body">
                <textarea id="out" class="json-output-textarea" rows="10" readonly></textarea>
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap (required for iconpicker popover) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.min.js"></script>

<!-- jQuery UI for Sortable -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<!-- Icon Picker (requires Bootstrap) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/css/bootstrap-iconpicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-iconpicker/1.10.0/js/bootstrap-iconpicker.bundle.min.js"></script>

<!-- Menu Editor Library -->
<script src="https://cdn.jsdelivr.net/gh/davicotico/jQuery-Menu-Editor@master/jquery-menu-editor.min.js"></script>

<script>
    $(function () {
        console.log('Menu Editor initializing...');
        
        // Initialize Menu Editor
        const editor = new MenuEditor('myEditor', {
            maxLevel: 2,
            formOptions: {
                text: '#text',
                href: '#href',
                icon: '#icon',
                permission: '#permission',
                moduleid: '#moduleid',
                deletestatus: '#deletestatus',
                is_active: '#is_active'
            }
        });

        editor.setForm($('#frmEdit'));
        editor.setUpdateButton($('#btnUpdate'));
        console.log('MenuEditor instance created');

        // Initialize Icon Picker
        try {
            $('#myEditor_icon').iconpicker({
                placement: 'bottomLeft',
                hideOnSelect: true
            }).on('iconpickerSelected', function (event) {
                $('#icon').val(event.iconpickerValue);
            });
            console.log('Icon picker initialized');
        } catch (e) {
            console.warn('Icon picker initialization:', e);
        }

        // Load menu data
        console.log('Loading menu data from: {{ route('menu.load') }}');
        $.ajax({
            url: "{{ route('menu.load') }}",
            type: 'GET',
            dataType: 'json',
            timeout: 5000,
            success: function (data) {
                console.log('Menu data received:', data);
                if (data && Array.isArray(data)) {
                    if (data.length > 0) {
                        console.log('Setting menu data with', data.length, 'items');
                        editor.setData(JSON.stringify(data));
                    } else {
                        console.log('Menu data is empty array - no items to load');
                    }
                } else {
                    console.error('Invalid menu data format:', typeof data);
                }
            },
            error: function (xhr, status, error) {
                console.error('Failed to load menu data');
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response:', xhr.responseText);
                alert('Failed to load menu items. Check browser console for details.');
            }
        });

        // Add new menu item
        $('#btnAdd').click(function(e) {
            e.preventDefault();
            console.log('Add button clicked');
            editor.add();
        });

        // Update menu item
        $('#btnUpdate').click(function(e) {
            e.preventDefault();
            console.log('Update button clicked');
            editor.update();
        });

        // Save menu to JSON
        $('#btnOutput').click(function (e) {
            e.preventDefault();
            console.log('Save button clicked');
            
            const str = editor.getString();
            console.log('Menu string:', str);
            $("#out").text(str);

            if (!str || str === '[]' || str === '') {
                alert('No menu items to save!');
                return;
            }

            $.ajax({
                url: "{{ route('menu.save') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    json: str
                },
                success: function (response) {
                    console.log('Menu saved. Response:', response);
                    if (response && response.success) {
                        alert('Menu saved successfully!');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        alert('Error: ' + (response?.message || 'Unknown error'));
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Failed to save menu');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    alert('Failed to save menu. Check console for details.');
                }
            });
        });

        console.log('Menu Editor ready');
    });
</script>
@endsection