<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>

    @endif
    <div class="form-group mb-3">
        <label for="">Name</label>
        <input wire:model='group_name' type='text' label='Name' class="form-control @error('group_name') is-invalid @enderror">
        @error('group_name')
        <small class="form-text text-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="mt-4 d-flex justify-content-center">
        @if($editForm)
        <button wire:click='update' class="btn btn-primary">Update</button>
        @else
        <button wire:click='store' class="btn btn-primary">Create</button>
        @endif
    </div>
</div>
