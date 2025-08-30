<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label fw-bold">{{ __('Nom') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
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
            <textarea name="description" class="form-control" rows="4" @error('description') is-invalid @enderror"
                id="description" placeholder="Description">{{ old('description', $service?->description) }}</textarea>
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <br>
        <div class="form-group mb-2 mb20">
            <label for="technical_specifications"
                class="form-label fw-bold">{{ __('Caractéristiques technique') }}</label>
            <textarea type="text" name="technical_specifications"
                class="form-control" rows="3" @error('technical_specifications') is-invalid @enderror"
                id="technical_specifications" placeholder="24h/24 7j/7 - Protection multi-terminaux ...">{{ old('technical_specifications', $service?->technical_specifications) }}</textarea>
            {!! $errors->first(
                'technical_specifications',
                '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>',
            ) !!}
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
        <div class="form-group">
            <label class="form-label fw-bold">Images de la galerie</label>
            <input type="file" name="gallery[]" multiple class="form-control">
        </div>

        <hr>

        <!-- Galerie existante avec suppression individuelle (sans formulaires imbriqués) -->
        <div class="row">
            @foreach ($service->gallery as $img)
                <div class="col-md-3 text-center mb-2">
                    <img src="{{ asset('storage/services/gallery/' . $img->image_path) }}"
                        class="img-fluid rounded shadow mb-1" alt="">

                    <!-- Bouton de suppression via AJAX pour éviter les formulaires imbriqués -->
                    <button type="button" class="btn btn-sm btn-danger delete-image" data-id="{{ $img->id }}"
                        data-url="{{ route('service-images.destroy', $img->id) }}">
                        Supprimer
                    </button>
                </div>
            @endforeach
        </div>


        <br>
        <div class="mb-3">
            <label for="price_monthly" class="form-label">Prix mensuel (€)</label>
            <input type="number" step="0.01" min="0" name="price_monthly" id="price_monthly"
                class="form-control" value="{{ old('price_monthly', $service->price_monthly) }}">
        </div>
        <div class="mb-3">
            <label for="price_yearly" class="form-label">Prix annuel (€)</label>
            <input type="number" step="0.01" min="0" name="price_yearly" id="price_yearly"
                class="form-control" value="{{ old('price_yearly', $service->price_yearly) }}">
        </div>
        <div class="mb-3">
            <label for="price_type" class="form-label">Type de prix</label>
            <select name="price_type" id="price_type" class="form-control">
                <option value="monthly" @if (old('price_type', $service->price_type) == 'monthly') selected @endif>
                    Mensuel
                </option>
                <option value="yearly" @if (old('price_type', $service->price_type) == 'yearly') selected @endif>
                    Annuel
                </option>
            </select>
        </div>

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
        // Suppression d'image de galerie via AJAX pour éviter les formulaires imbriqués
        $(document).on('click', '.delete-image', function(e) {
            e.preventDefault();
            const button = $(this);
            const url = button.data('url');

            if (!confirm('Supprimer cette image ?')) {
                return;
            }

            $.ajax({
                url: url,
                method: 'POST',
                data: {
                    _method: 'DELETE',
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    // Retire la tuile qui contient l'image et le bouton
                    button.closest('.col-md-3').remove();
                },
                error: function(xhr) {
                    alert('Échec de suppression (' + xhr.status + ').');
                }
            });
        });

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
