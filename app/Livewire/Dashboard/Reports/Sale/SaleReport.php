<?php

namespace App\Livewire\Dashboard\Reports\Sale;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;

class SaleReport extends Component
{
    public $products, $challans, $catagories, $sale_ledgers = [];
    public $state = [];
    public $type;
    public $searchSelect = -1;
    public $countProduct = 0;
    public $resultSales = [];
    public $salesesearch;
    public $oldSalesesearch;

    public function updatedSalesesearch()
    {   
        
        if ($this->salesesearch) {

            $resultQuery = DB::table('INV_SALES_MST as p')
                        ->where('memo_no', $this->salesesearch);

            if ($this->type == 'daily') {
                $resultQuery->where('tran_date', Carbon::now()->toDate());
            }
            if ($this->type == 'montyly') {
                $resultQuery->where('tran_date', '>=', Carbon::now()->firstOfMonth()->toDateString());
                $resultQuery->where('tran_date', '<=', Carbon::now()->lastOfMonth()->toDateString());
            }

            $result = $resultQuery->get()
                    ->toArray();

            if ($result) {
                $this->resultSales = $result;
            } else {
                $resultQuery = DB::table('INV_SALES_MST as p');

                if ($this->type == 'daily') {
                    $resultQuery->where('tran_date', Carbon::now()->toDate());
                }
                if ($this->type == 'montyly') {
                    $resultQuery->where('tran_date', '>=', Carbon::now()->firstOfMonth()->toDateString());
                    $resultQuery->where('tran_date', '<=', Carbon::now()->lastOfMonth()->toDateString());
                }

                $this->resultSales = $resultQuery
                    ->where(DB::raw('lower(p.memo_no)'), 'like', '%' . strtolower($this->salesesearch) . '%')
                    ->get()
                    ->toArray();

                // dd($this->resultSales);
            }

            $this->searchSelect = -1;
        } else {
            $this->resetSaleSearch();
        }

        $this->countProduct = count($this->resultSales);
    }

    public function searchRowSelect($pk)
    {
        $this->state['memo_no'] = $this->resultSales[$pk]->memo_no;
        $this->salesesearch = $this->state['memo_no'];
        
    }

    public function selectAccount()
    {
        $this->state['memo_no'] = $this->resultSales[$this->searchSelect]->memo_no;
        $this->salesesearch = $this->state['memo_no'];
        
    }


    //search increment decrement start
    public function decrementHighlight()
    {
        if ($this->searchSelect > 0) {
            $this->searchSelect--;
        }
    }
    public function incrementHighlight()
    {
        if ($this->searchSelect < ($this->countProduct - 1)) {
            $this->searchSelect++;
        }
    }

    public function hideDropdown()
    {
        $this->resetSaleSearch();
    }

    //search increment decrement end

    public function resetSaleSearch()
    {
        $this->searchSelect = -1;
        $this->resultSales = [];
    }


    public function search()
    {

        $query = DB::table('INV_SALES_MST');
        
        if($this->salesesearch){
            $query->where('memo_no', $this->salesesearch);
        }
        
        if ($this->type == 'daily') {
            $query->where('tran_date', Carbon::now()->toDate());
        }
        if ($this->type == 'montyly') {
            $query->where('tran_date', '>=', Carbon::now()->firstOfMonth()->toDateString());
            $query->where('tran_date', '<=', Carbon::now()->lastOfMonth()->toDateString());
        }

        if ($this->type == 'custom') {
            if (@$this->state['start_date']) {
                $query->where('tran_date', '>=', $this->state['start_date']);
            }
            if (@$this->state['end_date']) {
                $query->where('tran_date', '<=', $this->state['end_date']);
            }
        }

       
        $this->sale_ledgers = $query
                ->orderBy('memo_no','DESC')
                ->get();
        
        
    }

    public function mount($type)
    {
        $this->type = $type;
        $this->challans = [];
        $this->state['start_date'] = '';
        $this->state['end_date'] = '';
        $this->state['challan_no'] = '';
    }
    public function render()
    {
        return view('livewire.dashboard.reports.sale.sale-report');
    }
}