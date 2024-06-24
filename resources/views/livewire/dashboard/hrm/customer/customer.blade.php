<div>
    <div wire:loading class="spinner-border text-primary custom-loading" >
        <span class="sr-only">Loading...</span>
    </div>
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Customers</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Hrm</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('customer') }}" style="color: #3C50E0">Customers</a></li>
            </ol>
        </nav>
    </div>
    <div class="card p-4">
        <div class="row g-3 mb-3 align-items-center">
            <div class="col-auto">
                <input type="text" wire:model.live.debounce.300ms='search' class="form-control" placeholder="search here">
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

            <div class="col-auto">
                <a wire:navigate href='{{ route('customer-create') }}' class="btn btn-primary">
                    Create customer
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td class="bg-sidebar" style="width: 5%">#</td>
                        <td class="bg-sidebar" style="">Name</td>
                        <td class="bg-sidebar" style="">Phone</td>
                        <td class="bg-sidebar" style="">Email</td>
                        <td class="bg-sidebar" style="width:12%">Status</td>
                        <td class="bg-sidebar text-center" style="width: 15%">Action</td>
                    </tr>
                </thead>
                <tbody>
                    @if (count($this->resultCustomer) > 0)
                    @foreach ($this->resultCustomer as $key => $customer)
                    <tr wire:key='{{ $key }}'>
                        <td>{{ $this->resultCustomer->firstItem() + $key }}</td>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->phone_no }}</td>
                        <td>{{ $customer->email }}</td>
                        <td>
                            <select style="
                            @if ($customer->status == 1)
                                border: 1px solid #139C49
                            @else
                                border: 1px solid #D1485F
                            @endif
                            "
                            class='form-select' name="" id="">
                                <option
                                @if ($customer->status == 1)
                                    selected
                                @endif value="1">Active</option>
                                <option
                                @if ($customer->status == 0)
                                    selected
                                @endif value="2">Inactive</option>
                            </select>
                        </td>
                        <td style="display: flex; justify-content:center">
                            <div class="">
                                <a wire:navigate href='{{ route('customer-edit', $customer->customer_id) }}'

                                    class="btn btn-sm btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20px" height="20px" viewBox="0 0 50 50">
                                        <path fill="white" d="M 43.050781 1.9746094 C 41.800781 1.9746094 40.549609 2.4503906 39.599609 3.4003906 L 38.800781 4.1992188 L 45.699219 11.099609 L 46.5 10.300781 C 48.4 8.4007812 48.4 5.3003906 46.5 3.4003906 C 45.55 2.4503906 44.300781 1.9746094 43.050781 1.9746094 z M 37.482422 6.0898438 A 1.0001 1.0001 0 0 0 36.794922 6.3925781 L 4.2949219 38.791016 A 1.0001 1.0001 0 0 0 4.0332031 39.242188 L 2.0332031 46.742188 A 1.0001 1.0001 0 0 0 3.2578125 47.966797 L 10.757812 45.966797 A 1.0001 1.0001 0 0 0 11.208984 45.705078 L 43.607422 13.205078 A 1.0001 1.0001 0 1 0 42.191406 11.794922 L 9.9921875 44.09375 L 5.90625 40.007812 L 38.205078 7.8085938 A 1.0001 1.0001 0 0 0 37.482422 6.0898438 z"></path>
                                        </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <span>{{ $this->resultCustomer->links() }}</span>
    </div>
</div>

<script>

</script>




