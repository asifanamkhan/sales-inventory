<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa-solid fa-cart-shopping"></i> Expense
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Expense</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('expense') }}"
                        style="color: #3C50E0">expense list</a></li>
            </ol>
        </nav>
    </div>
    <div class="row" style="padding: 0px 8px 2px">
        <p class="col-auto">
            Total expense:
            <span class="badge bg-primary">
                {{ number_format($expenseGrantAmt, 2, '.', ',') }}
            </span>
        </p>

        <p class="col-auto">
            Total paid:
            <span class='badge bg-success'>{{ number_format($expensePaidAmt, 2, '.', ',') }}</span>
        </p>
        <p class="col-auto">
            Total due:
            <span class='badge bg-danger'>{{ number_format($expenseDueAmt, 2, '.', ',') }}</span>
        </p>
    </div>

    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>
    @elseif (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif
    <div class="card p-4">

        <div class="row g-3 mb-3 align-items-center">
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control"
                    placeholder="search here">
            </div>
            <div class="col-auto">
                <select class="form-select" wire:model.live='pagination' name="" id="">
                    <option value="5">5</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
            <div class="col-auto d-flex align-items-center gap-1">
                <input type="text" wire:model='searchDate' class="form-control date-range" id="date-filter">
                <button wire:click='dateFilter' class="btn btn-success">
                    <i class="fa-solid fa-search"></i>
                </button>
            </div>

            {{-- <div class="col-auto ">
                <a class="btn btn-warning">
                    <i class="fa-solid fa-file-excel"></i>
                </a>
            </div>
            <div class="col-auto ">
                <a class="btn btn-info">
                    <i class="fa-solid fa-print"></i>
                </a>
            </div> --}}
            <div class="col-md-2">

            </div>

            <div class="col-auto">
                <a wire:navigate href='{{route('expense-create') }}' type="button" class="btn btn-primary">Create new
                    expense</a>
            </div>


            {{-- modal --}}
            <x-large-modal class='payment'>
                <livewire:dashboard.expense.pay-partial.payment>
            </x-large-modal>

        </div>
        <div class="responsive-table" style="font-size: 0.9em !important;">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr class="bg-sidebar">
                        <td>
                            <input wire:model.live.debounce.500ms='selectPageRows' type="checkbox"
                                class="form-check-input">
                        </td>
                        <td style="">#</td>
                        <td style="width:9%">Date</td>
                        {{-- <td style="width:11%">Memo no</td> --}}
                        <td style="">Type</td>
                        <td style="text-align: center">Amount</td>
                        <td style="text-align: center">Paid</td>
                        <td style="text-align: center">Due</td>
                        <td style="text-align: center">Payment</td>
                        <td class="text-center" style=" width: 9%"">Action</td>

                    </tr>

                </thead>
                <tbody>
                    @if (count($this->resultExpense) > 0)
                    @foreach ($this->resultExpense as $key => $expense)
                    <tr wire:key='{{ $key }}'>
                        <td>
                            <input wire:model='selectRows' id='{{ $expense->expense_mst_id }}'
                                value=" {{ $expense->expense_mst_id }}" type="checkbox" class="form-check-input">
                        </td>
                        <td>{{ $this->resultExpense->firstItem() + $key }}</td>
                        <td>
                            {{ date('d-M-y', strtotime($expense->expense_date)) }}
                        </td>
                        {{-- <td>{{ $expense->memo_no }}</td> --}}
                        <td>{{ $expense->p_name }}</td>

                        <td style="text-align: right">
                            @php
                            $grand_total += (float)$expense->total_amount;
                            @endphp
                            {{ number_format($expense->total_amount, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            @php
                            $paid_total += (float)$expense->tot_paid_amt;
                            @endphp
                            {{ number_format($expense->tot_paid_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            @php
                            $due_total += (float)$expense->tot_due_amt;
                            @endphp
                            {{ number_format($expense->tot_due_amt, 2, '.', '') }}
                        </td>
                        <td style="text-align: right">
                            <div class="d-flex justify-content-center align-items-center">
                                <span style="
                                font-size: 0.9em;
                                @if($expense->payment_status == 'PAID')
                                background: #D4EDDA;
                                color: #155724;
                                @else
                                background: #F8D7DA;
                                color: #721c24;
                                @endif
                                " class="badge badge-pill">
                                    {{ $expense->payment_status }}
                                </span>
                            </div>
                        </td>
                        <td style="">
                            <div class="dropdown show">
                                <a class="btn btn-sm btn-primary dropdown-toggle" href="#" role="button"
                                    id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    Action &nbsp;&nbsp;&nbsp;&nbsp;
                                </a>

                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                    <a class="dropdown-item" wire:navigate
                                        href="{{ route('expense-edit', $expense->expense_mst_id) }}">
                                        <i class="fa fa-edit"></i> <span>Edit</span>
                                    </a>
                                    <a class="dropdown-item d-flex gap-1" wire:navigate
                                        href="{{ route('expense-details', $expense->expense_mst_id) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-binoculars" viewBox="0 0 16 16">
                                            <path
                                                d="M3 2.5A1.5 1.5 0 0 1 4.5 1h1A1.5 1.5 0 0 1 7 2.5V5h2V2.5A1.5 1.5 0 0 1 10.5 1h1A1.5 1.5 0 0 1 13 2.5v2.382a.5.5 0 0 0 .276.447l.895.447A1.5 1.5 0 0 1 15 7.118V14.5a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 14.5v-3a.5.5 0 0 1 .146-.354l.854-.853V9.5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v.793l.854.853A.5.5 0 0 1 7 11.5v3A1.5 1.5 0 0 1 5.5 16h-3A1.5 1.5 0 0 1 1 14.5V7.118a1.5 1.5 0 0 1 .83-1.342l.894-.447A.5.5 0 0 0 3 4.882zM4.5 2a.5.5 0 0 0-.5.5V3h2v-.5a.5.5 0 0 0-.5-.5zM6 4H4v.882a1.5 1.5 0 0 1-.83 1.342l-.894.447A.5.5 0 0 0 2 7.118V13h4v-1.293l-.854-.853A.5.5 0 0 1 5 10.5v-1A1.5 1.5 0 0 1 6.5 8h3A1.5 1.5 0 0 1 11 9.5v1a.5.5 0 0 1-.146.354l-.854.853V13h4V7.118a.5.5 0 0 0-.276-.447l-.895-.447A1.5 1.5 0 0 1 12 4.882V4h-2v1.5a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5zm4-1h2v-.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5zm4 11h-4v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5zm-8 0H2v.5a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5z" />
                                        </svg>
                                        <span>Details</span>
                                    </a>
                                    <a @click="$dispatch('expense-payment', {id: {{ $expense->expense_mst_id }}})"
                                        data-toggle="modal" data-target=".payment" class="dropdown-item" href="#">
                                        <i class="fa fa-credit-card"></i> Make payment
                                    </a>
                                    <a target="_blank" class="dropdown-item"
                                        href="{{ route('expense-invoice', $expense->expense_mst_id) }}">
                                        <i class="fas fa-print"></i> Print
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fa-regular fa-copy"></i> Duplicate
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    </tbody>
                <tfoot>
                    <tr style="text-align: right; font-weight:600">
                        <td colspan="4">Total</td>
                        <td>
                            {{ number_format($grand_total, 2, '.', ',') }}
                        </td>
                        <td>
                            {{ number_format($paid_total, 2, '.', ',') }}
                        </td>
                        <td>
                            {{ number_format($due_total, 2, '.', ',') }}
                        </td>
                        <td colspan="3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <span>{{ $this->resultExpense->links() }}</span>
    </div>
</div>
@script
<script data-navigate-once>
    document.addEventListener('livewire:navigated', () => {
        $(document).ready(function() {
            $('.date-range').daterangepicker();
        });
    });
    $('#date-filter').on('change', function(){
        @this.set('searchDate', $('#date-filter').val(), false);
    })
</script>
@endscript
