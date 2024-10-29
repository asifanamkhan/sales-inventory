<div>
    @push('css')
    <style>
        .ql-editor {
            height: 70px;
            max-height: 250px;
            overflow: auto;
        }

    </style>
    @endpush

    <div wire:loading class="spinner-border text-primary custom-loading">
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
    <form action="" wire:submit='save'>
        <div class="row" x-data="{edit : false}">
            <div class="col-md-4">
                <x-input required_mark='true' wire:model='state.lc_no' name='lc_no' type='text'
                    label='LC no' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='true' wire:model='state.issue_date' name='issue_date' type='date'
                    label='LC issue date' />
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3" wire:ignore>
                    <label for="">Lc type<span style="color: red"> * </span></label>
                    <select wire:model='state.lc_type' class="form-select">
                        <option value="">Select</option>
                        @forelse ($lc_types as $lc_typ)
                        <option
                        @if ($lc_typ == @$edit_select['lc_type'])
                            selected
                            @endif
                        value="{{ $lc_typ }}">{{ $lc_typ }}</option>

                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>
                </div>
                @error('lc_type')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-4">
                <div class="form-group mb-3">
                    <label for="">Lc Status<span style="color: red"> * </span></label>
                    <select wire:model='state.lc_status' class="form-select" id='lc_status'>
                        <option value="">select</option>
                        <option value="1">Active</option>
                        <option value="2">Expired</option>
                        <option value="3">Utilized</option>
                    </select>
                    @error('lc_status')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-4">
                <x-input required_mark='true' wire:model='state.applicant' name='applicant' type='text'
                    label='Applicant' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='true' wire:model='state.lc_amount' name='lc_amount' type='number' steps='0.01'
                    label='LC amount' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.expiry_date' name='expiry_date' type='date'
                    label='LC expiry date' />
            </div>

            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.shipment_date' name='shipment_date' type='date'
                    label='LC shipment date' />
            </div>

            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.beneficiary' name='beneficiary' type='text'
                    label='Beneficiary' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.issuing_bank' name='issuing_bank' type='text'
                    label='Issuing bank' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.advising_bank' name='advising_bank' type='text'
                    label='Advising bank' />
            </div>


            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.negotiation_period' name='negotiation_period' type='text'
                    label='Negotiation period' />
            </div>


            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.incoterms' name='incoterms' type='text' label='International commercial terms' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.port_of_loading' name='port_of_loading' type='text' label='Port of loading' />
            </div>
            <div class="col-md-4">
                <x-input required_mark='' wire:model='state.port_of_discharge' name='port_of_discharge' type='text' label='Port of discharge' />
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Item description </label>
                    <livewire:quill-text-editor wire:model="state.goods_description" theme="snow" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="">LC documents </label>
                    <livewire:dropzone wire:model="documents_required" :rules="['mimes:jpg,svg,png,jpeg,pdf,docx,xlsx,csv']"
                        :key="'dropzone-two'" />
                </div>
            </div>
        </div>
        <div class="mt-2 d-flex justify-content-center">
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

</script>
@endscript

