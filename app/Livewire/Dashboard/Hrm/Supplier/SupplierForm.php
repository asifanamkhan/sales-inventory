<?php

namespace App\Livewire\Dashboard\Hrm\Supplier;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierForm extends Component
{
    use WithFileUploads;
    public $photo,$p_name,$phone,$email,$status,$p_type,$comp_id,$county_name,
           $party_bank_code,$party_bank_name,$party_bank_br_name,$party_bank_account_no,
           $p_opbal,$contact_person,$web,$address;


    public function save(){
       $this->validate([
            'p_name' => 'required',
            'phone' => 'required',
            'email' => 'email|nullable',
            'status' => 'required|numeric',
            'p_type' => 'required',
        ]);

        if($this->photo){
            $path = $this->photo->store('myfiles');
        }else{
            $path = '';
        }

        DB::table('INV_SUPPLIER_INFO')->insert([
            'p_name' => $this->p_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'status' => $this->status,
            'p_type' => $this->p_type,
            'comp_id' => $this->comp_id,
            'county_name' => $this->county_name,
            'party_bank_code' => $this->party_bank_code,
            'party_bank_name' => $this->party_bank_name,
            'party_bank_br_name' => $this->party_bank_br_name,
            'party_bank_account_no' => $this->party_bank_account_no,
            'p_opbal' => $this->p_opbal,
            'contact_person' => $this->contact_person,
            'web' => $this->web,
            'address' => $this->address,
            'photo' => $path,
        ]);

        session()->flash('status', 'New Supplier create successfully. You can find it at supplier list');
        $this->reset();
    }
    public function render()
    {
        return view('livewire.dashboard.hrm.supplier.supplier-form');
    }
}
