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
            <div class="col-md-10">
                <h4>Summary</h4>
            </div>
            <div class="col-md-2">
                <select name="" id="" class="form-select">
                    <option value="">Today</option>
                    <option value="">Month</option>
                    <option value="">Year</option>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <div class="row">
                <div class="col-md-3">
                    <div style="

                        border-radius: 5px;
                        background: #007AFF;
                        color: white;
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner col-md-6">
                                <h3>150</h3>
                                <p>Purchase</p>
                            </div>
                            <div class="icon col-md-6">
                                <i class="fa-solid fa-cart-shopping" style="color: #0069D9; font-size:80px"></i>
                            </div>
                        </div>
                        <div style="background: #006FE5;padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div style="
                        border-radius: 5px;
                        background: #17A2B7;
                        color: white;
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner col-md-7">
                                <h3>150</h3>
                                <p>Purchase Return</p>
                            </div>
                            <div class="icon col-md-5">
                                <i class="fa-solid fa-cart-shopping" style="color: #138B9C; font-size:80px"></i>
                            </div>
                        </div>
                        <div style="background: #1491A5;padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div style="

                        border-radius: 5px;
                        background: #6366F1;
                        color: white;
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner col-md-7">
                                <h3>150</h3>
                                <p>Sale</p>
                            </div>
                            <div class="icon col-md-5">
                                <i class="fa-solid fa-cart-shopping" style="color: #5557CE; font-size:80px"></i>
                            </div>
                        </div>
                        <div style="background: #585CD9;padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div style="

                        border-radius: 5px;
                        background: #6C757E;
                        color: white;
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner col-md-7">
                                <h3>150</h3>
                                <p>Sale</p>
                            </div>
                            <div class="icon col-md-5">
                                <i class="fa-solid fa-cart-shopping" style="color: #5C6369; font-size:80px"></i>
                            </div>
                        </div>
                        <div style="background: #606970;padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner col-md-7">
                                <h3>150</h3>
                                <p>Sale</p>
                            </div>
                            <div class="icon col-md-5">
                                <i class="fa-solid fa-cart-shopping" style="color: #338260; font-size:80px"></i>
                            </div>
                        </div>
                        <div style="background: #378965;padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div style="

                        border-radius: 5px;
                        background: #660FF1;
                        color: white;
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner col-md-7">
                                <h3>150</h3>
                                <p>Sale</p>
                            </div>
                            <div class="icon col-md-5">
                                <i class="fa-solid fa-cart-shopping" style="color: #570ECF; font-size:80px"></i>
                            </div>
                        </div>
                        <div style="background: #5B0ED8;padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div style="

                        border-radius: 5px;
                        background: #DC3546;
                        color: white;
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner col-md-7">
                                <h3>150</h3>
                                <p>Sale</p>
                            </div>
                            <div class="icon col-md-5">
                                <i class="fa-solid fa-cart-shopping" style="color: #C12D3B; font-size:80px"></i>
                            </div>
                        </div>
                        <div style="background: #C62F3E;padding: 2px; text-align:center">
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>

                </div>
                <div class="col-md-3">
                    <div style="

                        border-radius: 5px;
                        background: #001f3ee8;
                        color: white;
                    ">
                        <div class="row" style="padding: 10px;">
                            <div class="inner col-md-7">
                                <h3>150</h3>
                                <p>Sale</p>
                            </div>
                            <div class="icon col-md-5">
                                <i class="fa-solid fa-cart-shopping" style="color: #001F3E; font-size:80px"></i>
                            </div>
                        </div>
                        <div style="background: #001F3E;padding: 2px; text-align:center">
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
