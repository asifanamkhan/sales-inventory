<div>
    <div>
        <div wire:loading class="spinner-border text-primary custom-loading"></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-cart-shopping"></i> expense report
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="" style="color: #3C50E0">expense
                        report</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <form action="" wire:submit='search'>
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-md-3">
                    <div class="form-group mb-3" wire:ignore>
                        <label for="">Branch</label>
                        <select class="form-select select2" id='branch'>
                            <option value="">Select branch</option>
                            @forelse ($branchs as $branch)
                            <option wire:key='{{ $branch->branch_id }}' value="{{ $branch->branch_id }}">
                                {{ $branch->branch_name }}
                            </option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group mb-3" wire:ignore>
                        <label for="">Expenset ypes</label>
                        <select class="form-select select2" id='expense_types'>
                            <option value="">All</option>
                            @forelse ($expense_types as $expense_type)
                            <option wire:key='{{ $expense_type->expense_id }}' value="{{ $expense_type->expense_id }}">
                                {{ $expense_type->expense_type }}
                            </option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                    </div>
                    @error('expense_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-2 ">
                    <x-input required_mark='' wire:model='state.start_date' name='start_date' type='date'
                        label='Start Date' />
                </div>
                <div class="col-md-2 ">
                    <x-input required_mark='' wire:model='state.end_date' name='end_date' type='date'
                        label='End Date' />
                </div>

                <div class="col-md-1 ">
                    <button class="btn btn-primary" id='search'>Search</button>
                </div>
            </div>
        </form>
        @if (count($ledgers) > 0)
        <div>
            <div style="display: flex; justify-content: space-between" class="p-2">
                <div></div>
                <div style="float: right">
                    <form target="_blank" action="{{route('expense-report-pdf')}}" method="post">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ $state['start_date'] }}">
                        <input type="hidden" name="end_date" value="{{ $state['end_date'] }}">
                        <input type="hidden" name="expense_type" value="{{ $state['expense_type'] }}">
                        <button class="btn btn-sm btn-success">
                            <i class="fa-solid fa-file-pdf"></i> Generate PDF
                        </button>
                    </form>

                </div>
            </div>
            <div class="responsive-table" style="font-size: 0.9em !important;">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="bg-sidebar">
                            <td style="">#</td>
                            <td style="width:9%">Date</td>
                            <td >Expe no</td>
                            <td style="text-align: center">Exp type</td>
                            <td style="text-align: center">Amount</td>
                            <td style="text-align: center">Paid</td>
                            <td style="text-align: center">Due</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $t_amt = 0;
                        $t_paid = 0;
                        $t_due = 0;
                        @endphp
                        @forelse ($ledgers as $key => $ledger)
                        <tr wire:key='{{ $key }}'>
                            @php
                            $t_amt += $ledger->total_amount;
                            $t_paid += $ledger->tot_paid_amt;
                            $t_due += $ledger->tot_due_amt;
                            @endphp
                            <td>{{ $key+1 }}</td>
                            <td>{{ date('d-M-y', strtotime($ledger->expense_date)) }}</td>
                            <td>{{ $ledger->expense_no }}</td>
                            <td>
                                {{ $ledger->expense_name }}
                            </td>
                            <td style="text-align: right">{{ number_format($ledger->total_amount, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->tot_paid_amt, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->tot_due_amt, 2, '.', '') }}</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">No data found</td>
                        </tr>
                        @endforelse

                    </tbody>
                    <tfoot>
                        <th colspan="4" style="text-align: right">Total: </th>
                        <th style="text-align: right">{{ number_format($t_amt, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_paid, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_due, 2, '.', '') }}</th>
                    </tfoot>

                </table>
            </div>
        </div>
        @else
        {{-- <div class="alert alert-danger">
            No data found
        </div> --}}
        @endif

    </div>
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

    $('#expense_types').on('change', function(e){
        @this.set('state.expense_type', e.target.value, false);
    });
    $('#branch').on('change', function(e){
        @this.set('state.branch_id', e.target.value, false);
    });

</script>
@endscript

