<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;

class Dashboard extends Component
{
    public $total_amount, $date, $start_date, $end_date;

    public function mount()
    {
        $this->date = 1;
    }

    #[Computed]
    public function result(){
        $query = DB::table('ACC_VOUCHER_INFO');

        if ($this->date == 1) {
           $query->where('voucher_date', Carbon::now()->toDateString());
        }
        if ($this->date == 2) {
            $query->where('voucher_date', '>=', Carbon::now()->startOfWeek(Carbon::SATURDAY)->toDateString());
            $query->where('voucher_date', '<', Carbon::now()->endOfWeek(Carbon::SATURDAY)->toDateString());
        }
        if ($this->date == 3) {
            $query->where('voucher_date', '>=', Carbon::now()->firstOfMonth()->toDateString());
            $query->where('voucher_date', '<=', Carbon::now()->lastOfMonth()->toDateString());
        }
        if ($this->date == 4) {

            $query->where('voucher_date', '>=', Carbon::now()->startOfYear()->toDateString());
            $query->where('voucher_date', '<=', Carbon::now()->endOfYear()->toDateString());
        }

        if ($this->date == 5) {
            // dd($this->date);
            if($this->start_date){
                $query->where('voucher_date', '>=', $this->start_date);
            }
            if($this->end_date){
                $query->where('voucher_date', '<=', $this->end_date);
            }
        }
        $query->select(
            DB::raw("SUM(CASE WHEN tran_type = 'PR' AND voucher_type = 'DR' THEN amount ELSE 0 END) as pr_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'PR' AND voucher_type = 'CR' THEN amount ELSE 0 END) as pr_paid_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'PRT' AND voucher_type = 'CR' THEN amount ELSE 0 END) as prt_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'SL' AND voucher_type = 'CR' THEN amount ELSE 0 END) as sl_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'SL' AND voucher_type = 'DR' THEN amount ELSE 0 END) as sl_paid_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'SRT' AND voucher_type = 'DR' THEN amount ELSE 0 END) as srt_total"),
            DB::raw("SUM(CASE WHEN tran_type = 'EXP' AND voucher_type = 'DR' THEN amount ELSE 0 END) as exp_total"),
        );

        return $query->first();
    }

    public function render()
    {

        return view('livewire.dashboard.dashboard');
    }
}