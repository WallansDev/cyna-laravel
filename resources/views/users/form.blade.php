<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user?->name) }}" id="name" placeholder="Name">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- @if (str_contains(url()->current(), '/edit'))
            <p>Image actuelle</p>
            <img src="{{ asset('storage/users/' . $user->image_path) }}" alt="Image projet" width="15%">
        @endif --}}

        <div class="form-group mb-2 mb20">
            <label for="surname" class="form-label">{{ __('Surname') }}</label>
            <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror"
                value="{{ old('surname', $user?->surname) }}" id="surname" placeholder="Surname">
            {!! $errors->first('surname', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user?->email) }}" id="email" placeholder="efef">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        @if (str_contains(url()->current(), 'create'))
            <div class="form-group mb-2 mb20">
                <label for="password" class="form-label">{{ __('Mot de passe temporaire') }}</label>
                <input type="password" name="password" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('password', $user?->password) }}" id="password" placeholder="password">
                {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
        @endif
        <div class="form-group mb-2 mb20">
            <label for="phone" class="form-label">{{ __('Phone') }}</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user?->phone) }}" id="phone" placeholder="phone">
            {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

        {{-- <div class="form-group mb-2 mb20">
            <label for="is_admin" class="form-label">{{ __('Est admin ?') }}</label>
            <input type="checkbox" name="is_admin" class="form-control @error('is_admin') is-invalid @enderror"
                value="1" {{ $user->is_admin ? 'checked' : '' }} id="is_admin" placeholder="is_admin">
            {!! $errors->first('is_admin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}


        <div class="form-group mb-2 mb20">
            <label for="is_admin" class="form-label">{{ __('Est admin ?') }}</label>

            <input type="checkbox" name="is_admin" id="is_admin" value="1" {{-- 1. Si old() existe (soumission précédente), on l’utilise --}}
                {{-- 2. Sinon si on est en édition, on utilise la valeur du user --}} {{-- 3. Sinon (création), décoché par défaut --}}
                {{ old('is_admin', isset($user) ? $user->is_admin : false) ? 'checked' : '' }}>

            @error('is_admin')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>
