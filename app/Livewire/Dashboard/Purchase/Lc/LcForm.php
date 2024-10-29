<?php

namespace App\Livewire\Dashboard\Purchase\Lc;

use App\Service\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LcForm extends Component
{
    public $state = [];
    public $edit_select = [];
    public $lc_id;
    public $documents_required = [];
    public $lc_types;
    public $action;


    public function lcTypesAll()
    {
        return $this->lc_types = [
            'Sight',
            'Usance',
            'Transferable'
        ];
    }



    public function mount($lc_id = null)
    {
        if ($lc_id) {
            $this->lc_id = $lc_id;
            $this->state = (array)DB::table('INV_LC_DETAILS')
                ->where('tran_mst_id', $lc_id)
                ->first();
            // dd($this->state);
            $this->edit_select['lc_type'] = $this->state['lc_type'];
            $this->state['issue_date'] = Carbon::parse($this->state['issue_date'])->toDateString();
            $this->state['expiry_date'] = $this->state['expiry_date'] ? Carbon::parse($this->state['expiry_date'])->toDateString() : '';
            $this->state['shipment_date'] = $this->state['shipment_date'] ?  Carbon::parse($this->state['shipment_date'])->toDateString() : '';
        } else {
            $this->state['lc_status'] = 1;
        }


        $this->lcTypesAll();
    }

    public function save()
    {

        Validator::make($this->state, [
            'issue_date' => 'required|date',
            'lc_amount' => 'required|numeric',
            'applicant' => 'required',
            'lc_no' => 'required',
            'lc_status' => 'required',
            'lc_type' => 'required',

        ])->validate();


        // dd(
        //     $this->state,
        //     $this->paymentState,
        //     $this->purchaseCart,
        // );

        $this->state['user_id'] = Auth::user()->id;
        $this->state['entry_dt'] = Carbon::now()->toDateString();
        $this->state['branch_id'] = 1;

        $this->dispatch($this->action, [
            'state' => $this->state,
        ]);
    }
    public function render()
    {
        return view('livewire.dashboard.purchase.lc.lc-form');
    }
}