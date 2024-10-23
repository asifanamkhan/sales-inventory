<div>
    <div wire:loading class="spinner-border text-primary custom-loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">
            <i class="fa fa-home"></i> Dashboard
        </h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
            </ol>
        </nav>
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

        <div class="row">
            <div class="@if ($date == 5) col-md-4 @else col-md-10 @endif">
                <h4>Summary</h4>
            </div>
            <div class="col-md-2">
                <select wire:model.live.debounce.300ms="date" class="form-select">
                    <option value="1">Today</option>
                    <option value="2">Weekly</option>
                    <option value="3">Monthly</option>
                    <option value="4">Yearly</option>
                    <option value="5">Custom</option>
                </select>
            </div>
            @if ($date == 5)
            <div class="col-md-3" style="display: flex; align-items:center">
                <span style="width: 50%; text-align: right">Start: &nbsp;</span> <input type="date" wire:model.live.debounce.300ms='start_date' class="form-control">
            </div>
            <div class="col-md-3" style="display: flex;align-items:center">
                <span style="width: 50%; text-align: right">End: &nbsp;</span> <input type="date" wire:model.live.debounce.300ms='end_date' class="form-control">
            </div>
            @endif
        </div>

        <div class="mt-4">
            <div class="row">
                <div class="col-md-3">
                    <div style="
                        border-radius: 5px;
                        background: #007AFF;
                        color: white;
                        position: relative; /* Make the parent relative for absolute positioning */
                        overflow: hidden; /* To hide any overflow from the icon */
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner" style="position: relative; z-index: 2;">
                                <h3>{{number_format($this->result->pr_total, 1, '.', ',') }}</h3>
                                <p>Purchase</p>
                            </div>
                            <div class="icon" style="
                                position: absolute;
                                top: 40%;
                                right: -160px; /* Move the icon to the right side */
                                transform: translateY(-50%);
                                z-index: 1;
                                opacity: 0.9; /* This will make the icon slightly transparent */
                            ">
                                <i class="fa-solid fa-cart-shopping" style="color: #0069D9; font-size: 80px;"></i>
                            </div>
                        </div>
                        <div style="background: #006FE5; padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="
                        border-radius: 5px;
                        background: #17A2B7;
                        color: white;
                        position: relative; /* Make the parent relative for absolute positioning */
                        overflow: hidden; /* To hide any overflow from the icon */
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner" style="position: relative; z-index: 2;">
                                <h3>{{number_format($this->result->prt_total, 1, '.', ',') }}</h3>
                                <p>Purchase Return</p>
                            </div>
                            <div class="icon" style="
                                position: absolute;
                                top: 40%;
                                right: -160px; /* Move the icon to the right side */
                                transform: translateY(-50%);
                                z-index: 1;
                                opacity: 0.9; /* This will make the icon slightly transparent */
                            ">
                                <i class="fa-solid fa-forward" style="color: #138B9C; font-size: 80px;"></i>
                            </div>
                        </div>
                        <div style="background: #1491A5; padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="
                        border-radius: 5px;
                        background: #6366F1;
                        color: white;
                        position: relative; /* Make the parent relative for absolute positioning */
                        overflow: hidden; /* To hide any overflow from the icon */
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner" style="position: relative; z-index: 2;">
                                @php
                                $pr_due = $this->result->pr_total - ($this->result->prt_total +
                                $this->result->pr_paid_total);
                                @endphp
                                <h3>{{number_format($pr_due, 1, '.', ',') }}</h3>
                                <p>Purchase Due</p>
                            </div>
                            <div class="icon" style="
                                position: absolute;
                                top: 40%;
                                right: -160px; /* Move the icon to the right side */
                                transform: translateY(-50%);
                                z-index: 1;
                                opacity: 0.9; /* This will make the icon slightly transparent */
                            ">
                                <i class="fa-solid fa-forward" style="color: #5557CE; font-size: 80px;"></i>
                            </div>
                        </div>
                        <div style="background: #585CD9; padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div style="
                        border-radius: 5px;
                        background: #6C757E;
                        color: white;
                        position: relative; /* Make the parent relative for absolute positioning */
                        overflow: hidden; /* To hide any overflow from the icon */
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner" style="position: relative; z-index: 2;">
                                <h3>{{number_format($this->result->sl_total, 1, '.', ',') }}</h3>
                                <p>Sale</p>
                            </div>
                            <div class="icon" style="
                                position: absolute;
                                top: 40%;
                                right: -160px; /* Move the icon to the right side */
                                transform: translateY(-50%);
                                z-index: 1;
                                opacity: 0.9; /* This will make the icon slightly transparent */
                            ">
                                <i class="fa-solid fa-scale-balanced" style="color: #5C6369; font-size: 80px;"></i>
                            </div>
                        </div>
                        <div style="background: #606970; padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <div class="row mt-4">
            <div class="col-md-3">
                <div style="
                    border-radius: 5px;
                    background: #3D9970;
                    color: white;
                    position: relative; /* Make the parent relative for absolute positioning */
                    overflow: hidden; /* To hide any overflow from the icon */
                ">
                    <div class="row" style="padding: 10px;">
                        <div class="inner" style="position: relative; z-index: 2;">
                            <h3>{{number_format($this->result->srt_total, 1, '.', ',') }}</h3>
                            <p>Sale Return</p>
                        </div>
                        <div class="icon" style="
                            position: absolute;
                            top: 40%;
                            right: -160px; /* Move the icon to the right side */
                            transform: translateY(-50%);
                            z-index: 1;
                            opacity: 0.9; /* This will make the icon slightly transparent */
                        ">
                            <i class="fa-solid fa-forward" style="color: #338260; font-size: 80px;"></i>
                        </div>
                    </div>
                    <div style="background: #378965; padding: 2px; text-align:center">
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div style="
                    border-radius: 5px;
                    background: #660FF1;
                    color: white;
                    position: relative; /* Make the parent relative for absolute positioning */
                    overflow: hidden; /* To hide any overflow from the icon */
                ">
                    <div class="row" style="padding: 10px;">
                        <div class="inner" style="position: relative; z-index: 2;">
                            <h3>{{number_format($this->result->sl_paid_total, 1, '.', ',') }}</h3>
                            <p>Sale Received</p>
                        </div>
                        <div class="icon" style="
                            position: absolute;
                            top: 40%;
                            right: -160px; /* Move the icon to the right side */
                            transform: translateY(-50%);
                            z-index: 1;
                            opacity: 0.9; /* This will make the icon slightly transparent */
                        ">
                            <i class="fa-solid fa-forward" style="color: #570ECF; font-size: 80px;"></i>
                        </div>
                    </div>
                    <div style="background: #5B0ED8; padding: 2px; text-align:center">
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div style="
                    border-radius: 5px;
                    background: #DC3546;
                    color: white;
                    position: relative; /* Make the parent relative for absolute positioning */
                    overflow: hidden; /* To hide any overflow from the icon */
                ">
                    <div class="row" style="padding: 10px;">
                        <div class="inner" style="position: relative; z-index: 2;">
                            @php
                            $sl_due = $this->result->sl_total - ($this->result->srt_total +
                            $this->result->sl_paid_total);
                            @endphp
                            <h3>{{number_format($sl_due, 1, '.', ',') }}</h3>
                            <p>Sale Due</p>
                        </div>
                        <div class="icon" style="
                            position: absolute;
                            top: 40%;
                            right: -160px; /* Move the icon to the right side */
                            transform: translateY(-50%);
                            z-index: 1;
                            opacity: 0.9; /* This will make the icon slightly transparent */
                        ">
                            <i class="fa-solid fa-forward" style="color: #C12D3B; font-size: 80px;"></i>
                        </div>
                    </div>
                    <div style="background: #C62F3E; padding: 2px; text-align:center">
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div style="
                    border-radius: 5px;
                    background: #001f3ee8;
                    color: white;
                    position: relative; /* Make the parent relative for absolute positioning */
                    overflow: hidden; /* To hide any overflow from the icon */
                ">
                    <div class="row" style="padding: 10px;">
                        <div class="inner" style="position: relative; z-index: 2;">
                            <h3>{{number_format($this->result->exp_total, 1, '.', ',') }}</h3>
                            <p>Expense</p>
                        </div>
                        <div class="icon" style="
                            position: absolute;
                            top: 40%;
                            right: -160px; /* Move the icon to the right side */
                            transform: translateY(-50%);
                            z-index: 1;
                            opacity: 0.9; /* This will make the icon slightly transparent */
                        ">
                            <i class="fa-solid fa-forward" style="color: #001F3E; font-size: 80px;"></i>
                        </div>
                    </div>
                    <div style="background: #001F3E; padding: 2px; text-align:center">
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>
</div>
@script
<script data-navigate-once>

</script>
@endscript
