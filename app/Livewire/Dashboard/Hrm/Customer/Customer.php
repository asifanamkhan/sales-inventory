<?php

namespace App\Livewire\Dashboard\Hrm\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Customer extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pagination = 10;

    #[Computed]
    public function resultCustomer()
    {
        $customers = DB::table('INV_CUSTOMER_INFO');

        if ($this->search) {
            $customers
                ->where(DB::raw('lower(customer_name)'), 'like', '%' . strtolower($this->search) . '%')
                ;
        }

        return $customers->orderBy('customer_id', 'DESC')
            ->paginate($this->pagination);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        return view('livewire.dashboard.hrm.customer.customer')->title('Customer');
    }
}
