<?php

use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

use App\Livewire\Dashboard\Admin\Company\CompanyInfo;
use App\Livewire\Dashboard\Admin\Module\Module;
use App\Livewire\Dashboard\Admin\Role\{Role, RoleCreate, RoleDetails};
use App\Livewire\Dashboard\Admin\User\{User, UserCreate};
use App\Livewire\Dashboard\Hrm\Branch\Branch;
use App\Livewire\Dashboard\Hrm\Customer\{Customer, CustomerCreate, CustomerEdit};
use App\Livewire\Dashboard\Hrm\Department\Department;
use App\Livewire\Dashboard\Hrm\Designation\Designation;
use App\Livewire\Dashboard\Hrm\Employee\Employee;
use App\Livewire\Dashboard\Hrm\Supplier\{Supplier, SupplierCreate, SupplierEdit};
use App\Livewire\Dashboard\Product\Brand\ProductBrand;
use App\Livewire\Dashboard\Product\Category\ProductCategory;
use App\Livewire\Dashboard\Product\Color\ProductColor;
use App\Livewire\Dashboard\Product\Group\ProductGroup;
use App\Livewire\Dashboard\Product\PricingList\PricingList;
use App\Livewire\Dashboard\Product\Product\{Product, ProductCreate, ProductEdit};
use App\Livewire\Dashboard\Product\Unit\ProductUnit;
use App\Livewire\Dashboard\ProductDamage\ProductDamage;
use App\Livewire\Dashboard\ProductDamage\ProductDamageCreate;
use App\Livewire\Dashboard\ProductDamage\ProductDamageEdit;
use App\Livewire\Dashboard\Purchase\Purchase\{Purchase, PurchaseCreate, PurchaseDetails, PurchaseEdit};
use App\Livewire\Dashboard\Purchase\Return\{PurchaseReturn, PurchaseReturnCreate, PurchaseReturnDetails, PurchaseReturnEdit};
use App\Livewire\Dashboard\Sales\Sales\{SaleDetails, Sales, SalesCreate, SalesEdit};
use App\Livewire\Dashboard\Sales\SalesReturn\{SalesReturn, SalesReturnCreate, SalesReturnDetails, SalesReturnEdit};
use Illuminate\Support\Facades\DB;

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/sales-inventory/livewire/update', $handle);
});

require __DIR__ . '/auth.php';


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    // ------------- admin start ----------------
    Route::get('role', Role::class)->name('role');
    Route::get('role/create', RoleCreate::class)->name('role-create');
    Route::get('role/details/{role_id}', RoleDetails::class)->name('role-details');

    Route::get('module', Module::class)->name('module');

    Route::get('company-info', CompanyInfo::class)->name('company-info');

    Route::get('user', User::class)->name('user');
    Route::get('user/create', UserCreate::class)->name('user-create');

    // ------------- admin end ----------------

    // ------------- hrm start ----------------

    Route::get('hrm/branch', Branch::class)->name('branch');
    Route::get('hrm/department', Department::class)->name('department');
    Route::get('hrm/designation', Designation::class)->name('designation');
    Route::get('hrm/employee', Employee::class)->name('employee');

    Route::get('supplier', Supplier::class)->name('supplier');
    Route::get('supplier/create', SupplierCreate::class)->name('supplier-create');
    Route::get('supplier/{supplier_id}/edit', SupplierEdit::class)->name('supplier-edit');

    Route::get('customer', Customer::class)->name('customer');
    Route::get('customer/create', CustomerCreate::class)->name('customer-create');
    Route::get('customer/{customer_id}/edit', CustomerEdit::class)->name('customer-edit');

    // ------------- hrm end ----------------


    // ------------- product start ----------------

    Route::get('product/group', ProductGroup::class)->name('product-group');
    Route::get('product/brand', ProductBrand::class)->name('product-brand');
    Route::get('product/unit', ProductUnit::class)->name('product-unit');
    Route::get('product/color', ProductColor::class)->name('product-color');
    Route::get('product/category', ProductCategory::class)->name('product-category');

    Route::get('product', Product::class)->name('product');
    Route::get('product/create', ProductCreate::class)->name('product-create');
    Route::get('product/{product_id}/edit', ProductEdit::class)->name('product-edit');

    Route::get('product/pricing/list', PricingList::class)->name('product-pricing-list');

    // ------------- product end ----------------


    // ------------- purchase start ----------------

    Route::get('purchase', Purchase::class)->name('purchase');
    Route::get('purchase/create', PurchaseCreate::class)->name('purchase-create');
    Route::get('purchase/{purchase_id}/edit', PurchaseEdit::class)->name('purchase-edit');
    Route::get('purchase/{purchase_id}/details', PurchaseDetails::class)->name('purchase-details');
    Route::get('purchase-invoice/{purchase_id}', [PurchaseController::class, 'invoice'])->name('purchase-invoice');

    Route::get('purchase-return', PurchaseReturn::class)->name('purchase-return');
    Route::get('purchase-return/create', PurchaseReturnCreate::class)->name('purchase-return-create');
    Route::get('purchase-return/{purchase_return_id}/edit', PurchaseReturnEdit::class)->name('purchase-return-edit');
    Route::get('purchase-return/{purchase_return_id}/details', PurchaseReturnDetails::class)->name('purchase-return-details');
    Route::get('purchase-return-invoice/{purchase_id}', [PurchaseController::class, 'returnInvoice'])->name('purchase-return-invoice');


    // ------------- purchase end ----------------


    // ------------- sale start ----------------
    Route::get('sale', Sales::class)->name('sale');
    Route::get('sale/create', SalesCreate::class)->name('sale-create');
    Route::get('sale/{sale_id}/edit', SalesEdit::class)->name('sale-edit');
    Route::get('sale/{sale_id}/details', SaleDetails::class)->name('sale-details');
    Route::get('sale-invoice/{sale_id}', [SaleController::class, 'invoice'])->name('sale-invoice');


    Route::get('sale-return', SalesReturn::class)->name('sale-return');
    Route::get('sale-return/create', SalesReturnCreate::class)->name('sale-return-create');
    Route::get('sale-return/{sale_return_id}/edit', SalesReturnEdit::class)->name('sale-return-edit');
    Route::get('sale-return/{sale_return_id}/details', SalesReturnDetails::class)->name('sale-return-details');
    Route::get('sale-return-invoice/{sale_id}', [SaleController::class, 'returnInvoice'])->name('sale-return-invoice');

    // ------------- sale end ----------------

    // ------------- sale start ----------------

    Route::get('product-damage', ProductDamage::class)->name('product-damage');
    Route::get('product-damage/create', ProductDamageCreate::class)->name('product-damage-create');
    Route::get('product-damage/{product_damage_id}/edit', ProductDamageEdit::class)->name('product-damage-edit');

    // ------------- sale end ----------------

});