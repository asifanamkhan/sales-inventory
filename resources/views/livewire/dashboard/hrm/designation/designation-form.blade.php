<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>

    @endif
    <x-input wire:model='desig_name' name='desig_name' type='text' label='Name' />
    <div class="mt-4 d-flex justify-content-center">
        @if($editForm)
        <button wire:click='update' class="btn btn-primary">Update</button>
        @else
        <button wire:click='store' class="btn btn-primary">Create</button>
        @endif
    </div>
</div>
