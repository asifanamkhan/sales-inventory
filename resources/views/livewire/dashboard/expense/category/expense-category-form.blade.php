<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>
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

    <form wire:submit="@if($editForm) update @else store @endif" action="">
        <div class="form-group ">
            <x-input required_mark='true' wire:model='state.expense_type' name='expense_type' type='text'
                label='Expense type' />
        </div>
        <div class="form-group mb-3" wire:ignore>
            <label for="">Chart of Account<span style="color: red"> * </span></label>
            <select class="form-select " wire:model='state.account_code' id='account_id'>
                <option value="">Select account</option>
                @forelse ($accounts as $key => $account)
                <option wire:key="{{ $key }}" @if ($account->account_code == @$edit_select['account_code'])
                    selected
                    @endif
                    value="{{ $account->account_code }}">{{ $account->account_name }}</option>
                @empty
                <option value=""></option>
                @endforelse
            </select>
        </div>
        @error('account_code')
            <small class="form-text text-danger">{{ $message }}</small>
        @enderror
        <div class="form-group">
            <label for="">Purchase remarks </label>
            <textarea wire:model="state.description" class="form-control" name="" id="" cols="30" rows="5"></textarea>

        </div>

        <div class="mt-4 d-flex justify-content-center">
            <button class="btn btn-primary">Save</button>
        </div>
    </form>

</div>
@script

<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.select2').select2({
                theme: "bootstrap-5",
            });
        });
    });

    $('#account').on('change', function(e){
        @this.set('state.account_code', e.target.value, false);
    });

</script>
@endscript
