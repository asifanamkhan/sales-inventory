<?php

namespace App\Livewire\Dashboard\Hrm\Supplier;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierEdit extends Component
{
    use WithFileUploads;
    // public $photo,$p_name,$phone,$email,$status,$p_type,$comp_id,$county_name,
    //        $party_bank_code,$party_bank_name,$party_bank_br_name,$party_bank_account_no,
    //        $p_opbal,$contact_person,$web,$address;

    public $supplier_id;
    public $editForm = true;
    public $state = [];

    public function mount($supplier_id)
    {

        $this->supplier_id = $supplier_id;
        $supplier = (array)DB::table('INV_SUPPLIER_INFO')
            ->where('p_code', $this->supplier_id)
            ->first();

        $this->state = $supplier;
        $this->state['old_photo'] = $supplier['photo'];
        $this->state['photo'] = '';

    }
    public function render()
    {
        return view('livewire.dashboard.hrm.supplier.supplier-edit');
    }
}
