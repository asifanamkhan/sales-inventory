<?php

namespace App\Livewire\Dashboard\Hrm\Supplier;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class SupplierCreate extends Component
{
    use WithFileUploads;

    public $state = [];
    public $editForm = false;


    public function save()
    {

        Validator::make($this->state, [
            'p_name' => 'required',
            'phone' => 'required',
            'email' => 'email|nullable',
            'status' => 'required|numeric',
            'p_type' => 'required',
        ])->validate();

        if ($this->state['photo']) {
            $this->state['photo'] = $this->state['photo']->store('upload');
        }

        DB::table('INV_SUPPLIER_INFO')->insert($this->state);

        session()->flash('status', 'New Supplier create successfully. You can find it at supplier list');

        $this->reset();
        $this->state['photo'] = '';
    }
    public function render()
    {
        return view('livewire.dashboard.hrm.supplier.supplier-create');
    }
}
