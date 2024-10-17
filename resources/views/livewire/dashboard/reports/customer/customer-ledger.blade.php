<div>
    <div>
        <div wire:loading class="spinner-border text-primary custom-loading"></div>
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-cart-shopping"></i> Customer Ledger
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Reports</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('customer-ledger') }}"
                        style="color: #3C50E0">Customer Ledger</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">

        <form action="" wire:submit='search'>
            <div class="row g-3 mb-3 align-items-center">
                <div class="col-md-4">
                    <div class="form-group mb-3" wire:ignore>
                        <label for="">Customer<span style="color: red"> * </span></label>
                        <select class="form-select select2" id='customer'>
                            <option value="">Select customer</option>
                            @forelse ($customers as $customer)
                            <option value="{{ $customer->customer_id }}">{{
                                $customer->customer_name }}</option>
                            @empty
                            <option value=""></option>
                            @endforelse
                        </select>
                    </div>
                    @error('customer_id')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-3 ">
                    <x-input required_mark='' wire:model='state.start_date' name='start_date' type='date'
                        label='Start Date' />
                </div>
                <div class="col-md-3 ">
                    <x-input required_mark='' wire:model='state.end_date' name='end_date' type='date'
                        label='End Date' />
                </div>

                <div class="col-md-2 ">
                    <button class="btn btn-primary" id='search'>Search</button>
                </div>
            </div>
        </form>
        @if (count($ledgers) > 0)
        <div>
            <div style="display: flex; justify-content: space-between" class="p-2">
                <h4 class="" style="color: #4CAF50">{{ $ledgers[0]->customer_name }}</h4>
                <div >
                    <a target="_blank" class="btn btn-sm btn-success" href="{{ route('customer-ledger-pdf', $ledgers[0]->customer_id) }}">
                        <i class="fa-solid fa-file-pdf"></i> Generate PDF
                    </a>
                </div>
            </div>
            <div class="responsive-table" style="font-size: 0.9em !important;">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr class="bg-sidebar">
                            <td style="">#</td>
                            <td style="width:9%">Date</td>
                            <td style="width:11%">Memo no</td>
                            <td style="text-align: center">Grand amt</td>
                            <td style="text-align: center">Paid amt</td>
                            <td style="text-align: center">Return</td>
                            <td style="text-align: center">Rt received</td>
                            <td style="text-align: center">Rt due</td>
                            <td style="text-align: center">Due amt</td>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $t_tot_payable_amt = 0;
                        $t_total_paid = 0;
                        $t_return_amt = 0;
                        $t_return_paid_amt = 0;
                        $t_receivable_amt = 0;
                        $t_total_due = 0;
                        @endphp
                        @forelse ($ledgers as $key => $ledger)
                        <tr wire:key='{{ $key }}'>
                            @php
                            $t_tot_payable_amt += $ledger->tot_payable_amt;
                            $t_total_paid += $ledger->total_paid_amt;
                            $t_return_amt += $ledger->tot_return_amt;
                            $t_return_paid_amt += $ledger->sales_ret_paid;
                            $t_receivable_amt += $ledger->receiveable_amt;
                            $t_total_due += $ledger->tot_due_amt;
                            @endphp
                            <td>{{ $key+1 }}</td>
                            <td>{{ date('d-M-y', strtotime($ledger->tran_date)) }}</td>
                            <td>{{ $ledger->memo_no }}</td>
                            <td style="text-align: right">{{ number_format($ledger->tot_payable_amt, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->total_paid_amt, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->tot_return_amt, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->sales_ret_paid, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->receiveable_amt, 2, '.', '') }}</td>
                            <td style="text-align: right">{{ number_format($ledger->tot_due_amt, 2, '.', '') }}</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7">No data found</td>
                        </tr>
                        @endforelse

                    </tbody>
                    <tfoot>
                        <th colspan="3" style="text-align: right">Total: </th>
                        <th style="text-align: right">{{ number_format($t_tot_payable_amt, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_total_paid, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_return_amt, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_return_paid_amt, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_receivable_amt, 2, '.', '') }}</th>
                        <th style="text-align: right">{{ number_format($t_total_due, 2, '.', '') }}</th>
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

    $('#customer').on('change', function(e){
        @this.set('state.customer_id', e.target.value, false);
    });
</script>
@endscript

