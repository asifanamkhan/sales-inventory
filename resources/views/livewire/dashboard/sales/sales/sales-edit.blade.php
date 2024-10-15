<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa fa-plus"></i>Sale edit</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">

                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale') }}">Purchase</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('sale-edit', $sale_id) }}"
                        style="color: #3C50E0">edit</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4" wire:ignore.self>
        <livewire:dashboard.sales.sales.sales-form sale_id="{{ $sale_id }}" />
    </div>
</div>

