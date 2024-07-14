<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Create sales return </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Sales</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale-return') }}">sales return</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale-return-create') }}"
                        style="color: #3C50E0">create</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4" wire:ignore.self>
        <livewire:dashboard.sales.sales-return.sales-return-form :sales_return_id=false />
    </div>
</div>



