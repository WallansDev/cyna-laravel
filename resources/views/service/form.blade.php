<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label fw-bold">{{ __('Nom') }}</label>
            <input type="text" name="name" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('name', $service?->name) }}" id="name" placeholder="Nom">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <br>
        @if (str_contains(url()->current(), '/edit'))
            <p class="fw-bold">Image actuelle</p>
            <img src="{{ asset('storage/services/' . $service->image_path) }}" alt="Image projet" width="15%">
            <br><br>
        @endif

        <div class="form-group mb-2 mb20">
            <label for="image_path" class="form-label fw-bold">{{ __('Image') }}</label>
            <input type="file" name="image_path" class="form-control @error('image_path') is-invalid @enderror"
                value="{{ old('image_path', $service?->image_path) }}" id="image_path" placeholder="Image Path">
            {!! $errors->first('image_path', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <br>
        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label fw-bold">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                value="{{ old('description', $service?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <br>
        <div class="form-group">
            <input type="hidden" name="availbility" value="0">
            <input type="checkbox" name="availbility" id="availbility" value="1"
                {{ old('availbility', $service->availbility ?? true) ? 'checked' : '' }}>
            <label for="availbility">Disponible</label>
        </div>

        <div class="form-group">
            <label for="is_top_product">
                <input type="checkbox" name="is_top_product" id="is_top_product" value="1"
                    {{ old('is_top_product', ($service->top_position ?? 0) > 0) ? 'checked' : '' }}>
                En vedette
            </label>
        </div>
        <br>
        {{-- <div id="top_position_block"
            style="{{ old('is_top_product', ($service->top_position ?? 0) > 0) ? '' : 'display:none;' }}">
            <p class="mb-2">Organisez la position dans les produits en vedette :</p>
            <ul id="sortable-top-products" class="list-group" style="border: 1px solid black">
                @php
                    $alreadyInList = false;
                @endphp
                @foreach ($topServices as $top)
                    <li class="list-group-item sortable-item d-flex justify-content-between align-items-center"
                        data-id="{{ $top->id }}">
                        @if (isset($service) && $service->id === $top->id)
                            <b>{{ $top->name }}</b>
                            @php $alreadyInList = true; @endphp
                        @else
                            {{ $top->name }}
                        @endif
                    </li>
                @endforeach

                Si ce service devient top mais n'est pas encore dans la liste
                @if (!$alreadyInList && old('is_top_product'))
                    <li class="list-group-item sortable-item d-flex justify-content-between align-items-center"
                        data-id="{{ $service->id ?? 'new' }}">
                        {{ old('name', $service->name ?? 'Ce nouveau service') }}
                        <span class="badge bg-primary">Ce service</span>
                    </li>
                @endif
            </ul>

            <input type="hidden" name="top_order_json" id="top_order_json">
        </div> --}}


        <div class="form-group">
            <label for="categories" class="fw-bold">Catégories associés</label>
            <select name="categories[]" id="categories" class="form-control" multiple>
                @foreach ($allCategories as $category)
                    <option value="{{ $category->id }}" @if (isset($service) && $service->categories->contains($category->id)) selected @endif>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <br>
        <!-- Formulaire principal pour mettre à jour le service -->
        <form action="{{ route('services.update', $service->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Champs du service ... -->

            <!-- Upload de nouvelles images -->
            <input type="file" name="gallery[]" multiple class="form-control">
        </form>

        <hr>

        <!-- Galerie existante avec suppression individuelle -->
        <div class="row">
            @foreach ($service->gallery as $img)
                <div class="col-md-3 text-center mb-2">
                    <img src="{{ asset('storage/services/gallery/' . $img->image_path) }}"
                        class="img-fluid rounded shadow mb-1" alt="">

                    <!-- Formulaire indépendant pour supprimer l'image -->
                    <form action="{{ route('service-images.destroy', $img->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                    </form>
                </div>
            @endforeach
        </div>


        <br>

    </div>
    <div class="col-md-12 mt20 mt-2 text-center">
        <button type="submit" class="btn btn-success">{{ __('Enregistrer') }}</button>
        <a href="{{ url()->previous() }}" class="ms-3 btn btn-primary">Retour en arrière</a>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(function() {
        function refreshOrder() {
            let order = [];
            $('.sortable-item').each(function(index) {
                order.push({
                    id: $(this).data('id'),
                    position: index + 1
                });
            });
            $('#top_order_json').val(JSON.stringify(order));
        }

        $('#is_top_product').on('change', function() {
            if (this.checked) {
                $('#top_position_block').show();

                // Ajoute dynamiquement le service dans la liste si absent
                const serviceId = '{{ $service->id ?? 'new' }}';
                const name = $('#name').val() || 'Ce nouveau service';

                const alreadyExists = $('#sortable-top-products li').filter(function() {
                    return $(this).data('id') == serviceId;
                }).length > 0;

                if (!alreadyExists) {
                    $('#sortable-top-products').append(`
                        <li class="list-group-item sortable-item d-flex justify-content-between align-items-center"
                            data-id="${serviceId}">
                            ${name}
                            <span class="badge bg-primary">Ce service</span>
                        </li>
                    `);
                    $("#sortable-top-products").sortable("refresh");
                    refreshOrder();
                }
            } else {
                $('#top_position_block').hide();
            }
        });

        $("#sortable-top-products").sortable({
            update: function() {
                refreshOrder();
            }
        });

        refreshOrder(); // Initial order save
    });
</script>
