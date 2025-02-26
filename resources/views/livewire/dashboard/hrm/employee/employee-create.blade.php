<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fas fa-plus"></i> Create new employee </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Hrm</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('employee') }}">employees</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('employee-create') }}"
                        style="color: #3C50E0">create</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4" wire:ignore.self>
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
        </div>
        @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
        </div>
        @elseif (session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('warning') }}
        </div>
        @endif

        @livewire('dashboard.hrm.employee.employee-form', ['action' => 'saveEmployee'])
    </div>
</div>
