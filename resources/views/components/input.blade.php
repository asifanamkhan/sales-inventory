@props(['label','type', 'name'])

<div class="form-group mb-3">
    <label for="">{{ $label }}</label>
    <input type="{{ $type }}" class="form-control @error($name) is-invalid @enderror"
        {{ $attributes }}>
    @error($name)
    <small class="form-text text-danger">{{ $message }}</small>
    @enderror
</div>
