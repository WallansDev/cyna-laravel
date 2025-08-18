<div class="row padding-1 p-1">
    <div class="col-md-12">

        <div class="form-group mb-2 mb20">
            <label for="name" class="form-label fw-bold">{{ __('Nom') }}</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user?->name) }}" id="name" placeholder="Nom">
            {!! $errors->first('name', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <br>
        {{-- <p class="fw-bold">Image actuelle</p>
        {{-- @if (str_contains(url()->current(), '/edit'))
            <p>Image actuelle</p>
            <img src="{{ asset('storage/users/' . $user->image_path) }}" alt="Image projet" width="15%">
        @endif --}}

        <div class="form-group mb-2 mb20">
            <label for="surname" class="form-label fw-bold">{{ __('Prénom') }}</label>
            <input type="text" name="surname" class="form-control @error('surname') is-invalid @enderror"
                value="{{ old('surname', $user?->surname) }}" id="surname" placeholder="Prénom">
            {!! $errors->first('surname', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <br>
        <div class="form-group mb-2 mb20">
            <label for="email" class="form-label fw-bold">{{ __('Email') }}</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user?->email) }}" id="email" placeholder="prenom.nom@example.com">
            {!! $errors->first('email', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <br>
        @if (str_contains(url()->current(), 'create'))
            <div class="form-group mb-2 mb20">
                <label for="password" class="form-label fw-bold">{{ __('Mot de passe temporaire') }}</label>
                <input type="password" name="password" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('password', $user?->password) }}" id="password" placeholder="Mot de passe">
                {!! $errors->first('password', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            </div>
            <br>
        @endif
        <div class="form-group mb-2 mb20">
            <label for="phone" class="form-label fw-bold">{{ __('Téléphone') }}</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user?->phone) }}" id="phone" placeholder="Téléphone">
            {!! $errors->first('phone', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <br>
        {{-- <div class="form-group mb-2 mb20">
            <label for="is_admin" class="form-label">{{ __('Est admin ?') }}</label>
            <input type="checkbox" name="is_admin" class="form-control @error('is_admin') is-invalid @enderror"
                value="1" {{ $user->is_admin ? 'checked' : '' }} id="is_admin" placeholder="is_admin">
            {!! $errors->first('is_admin', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div> --}}


        <div class="form-group">
                <input type="checkbox" name="is_admin" id="is_admin" value="1" {{-- 1. Si old() existe (soumission précédente), on l’utilise --}}
                {{-- 2. Sinon si on est en édition, on utilise la valeur du user --}} {{-- 3. Sinon (création), décoché par défaut --}}
                {{ old('is_admin', isset($user) ? $user->is_admin : false) ? 'checked' : '' }}>
                <label for="is_admin" class="form-label">{{ __('Admin') }}</label>
            @error('is_admin')
                <div class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </div>
            @enderror
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2 text-center">
        <button type="submit" class="btn btn-success">{{ __('Enregistrer') }}</button>
        <a href="{{ url()->previous() }}" class="ms-3 btn btn-primary">Retour en arrière</a>
    </div>
</div>
