<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Customer create</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Hrm</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('customer') }}">customers</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('customer-create') }}"
                        style="color: #3C50E0">create</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        @include('livewire.dashboard.hrm.customer.customer-form')
        <div class="mt-4 d-flex justify-content-center">
            <button wire:click.prevent='save' class="btn btn-primary">Save</button>
        </div>
    </div>
</div>



