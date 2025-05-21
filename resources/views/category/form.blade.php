<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('name', $category?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        @if (str_contains(url()->current(), '/edit'))
            <p>Image actuelle</p>
            <img src="{{ asset('storage/categories/' . $category->image_path) }}" alt="Image projet" width="15%">
        @endif

        <div class="form-group mb-2 mb20">
            <label for="image_path" class="form-label">{{ __('Image Path') }}</label>
            <input type="file" name="image_path" class="form-control @error('image_path') is-invalid @enderror"
                value="{{ old('image_path', $category?->image_path) }}" id="image_path" placeholder="Image Path">
            {!! $errors->first('image_path', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <input type="text" name="description" class="form-control @error('description') is-invalid @enderror"
                value="{{ old('description', $category?->description) }}" id="description" placeholder="Description">
            {!! $errors->first('description', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group">
            <label for="services">Services</label>
            <select name="services[]" id="services" class="form-control" multiple>
                @foreach ($allServices as $service)
                    <option value="{{ $service->id }}" @if (isset($category) && $category->services->contains($service->id)) selected @endif>
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
