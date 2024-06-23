<?php

use App\Livewire\Dashboard\Admin\Company\CompanyInfo;
use App\Livewire\Dashboard\Admin\Module\Module;
use App\Livewire\Dashboard\Admin\Role\Role;
use App\Livewire\Dashboard\Admin\Role\RoleCreate;
use App\Livewire\Dashboard\Admin\Role\RoleDetails;
use App\Livewire\Dashboard\Admin\User\User;
use App\Livewire\Dashboard\Admin\User\UserCreate;
use App\Livewire\Dashboard\Hrm\Branch\Branch;
use App\Livewire\Dashboard\Hrm\Customer\Customer;
use App\Livewire\Dashboard\Hrm\Department\Department;
use App\Livewire\Dashboard\Hrm\Designation\Designation;
use App\Livewire\Dashboard\Hrm\Employee\Employee;
use App\Livewire\Dashboard\Hrm\Supplier\Supplier;
use App\Livewire\Dashboard\Hrm\Supplier\SupplierCreate;
use App\Livewire\Dashboard\Hrm\Supplier\SupplierEdit;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/sales-inventory/livewire/update', $handle);
});

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('role', Role::class)->name('role');
    Route::get('role-create', RoleCreate::class)->name('role-create');
    Route::get('role-details/{role_id}', RoleDetails::class)->name('role-details');

    Route::get('module', Module::class)->name('module');

    Route::get('company-info', CompanyInfo::class)->name('company-info');

    Route::get('user', User::class)->name('user');
    Route::get('user-create', UserCreate::class)->name('user-create');

    //hrm
    Route::get('branch', Branch::class)->name('branch');
    Route::get('department', Department::class)->name('department');
    Route::get('designation', Designation::class)->name('designation');
    Route::get('employee', Employee::class)->name('employee');

    Route::get('supplier', Supplier::class)->name('supplier');
    Route::get('supplier-create', SupplierCreate::class)->name('supplier-create');
    Route::get('supplier/{supplier_id}/edit', SupplierEdit::class)->name('supplier-edit');

    Route::get('customer', Customer::class)->name('customer');


});
