<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Livewire\Dashboard\AccountReports\PaymentReport;
use App\Livewire\Dashboard\AccountReports\TransactionReport;
use App\Livewire\Dashboard\AccountReports\TrialBalance;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

use App\Livewire\Dashboard\Admin\Company\CompanyInfo;
use App\Livewire\Dashboard\Admin\Module\Module;
use App\Livewire\Dashboard\Admin\Role\{Role, RoleCreate, RoleDetails};
use App\Livewire\Dashboard\Admin\User\{User, UserCreate};
use App\Livewire\Dashboard\Expense\Category\ExpenseCategory;
use App\Livewire\Dashboard\Expense\Expense;
use App\Livewire\Dashboard\Expense\ExpenseCreate;
use App\Livewire\Dashboard\Expense\ExpenseDetails;
use App\Livewire\Dashboard\Expense\ExpenseEdit;
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
use App\Livewire\Dashboard\Reports\Customer\CustomerLedger;
use App\Livewire\Dashboard\Reports\Product\{
    ProductDamageReport,
    ProductSaleReturn,
    ProductSaleReport,
    ProductPurchaseReturnReport,
    ProductPurchaseReport,
    ProductExpiryReport,
    ProductStockOutReport,
    ProductStockReport
};

use App\Livewire\Dashboard\Reports\Sale\SaleReport;
use App\Livewire\Dashboard\Reports\Supplier\SupplierLedger;
use App\Livewire\Dashboard\Sales\Sales\{SaleDetails, Sales, SalesCreate, SalesEdit};
use App\Livewire\Dashboard\Sales\SalesReturn\{SalesReturn, SalesReturnCreate, SalesReturnDetails, SalesReturnEdit};
use Illuminate\Support\Facades\DB;
use App\Livewire\Dashboard\Dashboard;
use App\Livewire\Dashboard\Hrm\Employee\EmployeeCreate;
use App\Livewire\Dashboard\Purchase\Lc\{Lc, LcCreate, LcDetails, LcEdit};
use App\Livewire\Dashboard\Reports\Expense\ExpenseReport;
use App\Livewire\Dashboard\Requisition\RequisitionCreate;
use App\Livewire\Dashboard\Requisition\RequisitionDetails;
use App\Livewire\Dashboard\Requisition\RequisitionEdit;
use App\Livewire\Dashboard\Requisition\RequisitionList;
use Illuminate\Support\Facades\Auth;

Livewire::setUpdateRoute(function ($handle) {
    $path = env('LIVEWIRE_UPDATE_PATH') . '/livewire/update';
    return Route::post($path, $handle);
});

require __DIR__ . '/auth.php';


Route::middleware(['auth', 'verified', 'throttle:60,1'])->group(function () {

    Route::get('/', Dashboard::class)->name('dashboard');
    // ------------- admin start ----------------
    Route::get('role', Role::class)->name('role')->middleware('permission:4,visible_flag');
    Route::get('role/create', RoleCreate::class)->name('role-create')->middleware('permission:4,visible_flag');
    Route::get('role/details/{role_id}', RoleDetails::class)->name('role-details')->middleware('permission:4,visible_flag');

    Route::get('module', Module::class)->name('module')->middleware('permission:2,visible_flag');

    Route::get('company-info', CompanyInfo::class)->name('company-info')->middleware('permission:1,visible_flag');

    Route::get('user', User::class)->name('user')->middleware('permission:3,visible_flag');
    Route::get('user/create', UserCreate::class)->name('user-create')->middleware('permission:3,visible_flag');

    // ------------- admin end ----------------

    // ------------- hrm start ----------------

    Route::get('hrm/branch', Branch::class)->name('branch')->middleware('permission:19,visible_flag');
    Route::get('hrm/department', Department::class)->name('department')->middleware('permission:20,visible_flag');
    Route::get('hrm/designation', Designation::class)->name('designation')->middleware('permission:21,visible_flag');
    Route::get('hrm/employee', Employee::class)->name('employee')->middleware('permission:22,visible_flag');
    Route::get('hrm/employee/create', EmployeeCreate::class)->name('employee-create')->middleware('permission:22,visible_flag');

    Route::middleware(['permission:23,visible_flag'])->group(function () {
        Route::get('supplier', Supplier::class)->name('supplier');
        Route::get('supplier/create', SupplierCreate::class)->name('supplier-create');
        Route::get('supplier/{supplier_id}/edit', SupplierEdit::class)->name('supplier-edit');
    });

    Route::middleware(['permission:24,visible_flag'])->group(function () {
        Route::get('customer', Customer::class)->name('customer');
        Route::get('customer/create', CustomerCreate::class)->name('customer-create');
        Route::get('customer/{customer_id}/edit', CustomerEdit::class)->name('customer-edit');
    });
    // ------------- hrm end ----------------


    // ------------- product start ----------------

    Route::get('product/group', ProductGroup::class)->name('product-group')->middleware('permission:5,visible_flag');
    Route::get('product/brand', ProductBrand::class)->name('product-brand')->middleware('permission:7,visible_flag');
    Route::get('product/unit', ProductUnit::class)->name('product-unit')->middleware('permission:9,visible_flag');
    Route::get('product/color', ProductColor::class)->name('product-color')->middleware('permission:10,visible_flag');
    Route::get('product/category', ProductCategory::class)->name('product-category')->middleware('permission:8,visible_flag');

    Route::middleware(['permission:12,visible_flag'])->group(function () {
        Route::get('product', Product::class)->name('product');
        Route::get('product/create', ProductCreate::class)->name('product-create');
        Route::get('product/{product_id}/edit', ProductEdit::class)->name('product-edit');
    });
    Route::get('product/pricing/list', PricingList::class)->name('product-pricing-list')->middleware('permission:13,visible_flag');

    // ------------- product end ----------------


    // ------------- purchase start ----------------

    Route::middleware(['permission:25,visible_flag'])->group(function () {
        Route::get('lc', Lc::class)->name('lc');
        Route::get('lc/create', LcCreate::class)->name('lc-create');
        Route::get('lc/{lc_id}/edit', LcEdit::class)->name('lc-edit');
        Route::get('lc/{lc_id}/details', LcDetails::class)->name('lc-details');
        // Route::get('lc-invoice/{lc_id}', [PurchaseController::class, 'invoice'])->name('lc-invoice');


        Route::get('purchase', Purchase::class)->name('purchase');
        Route::get('purchase/create', PurchaseCreate::class)->name('purchase-create');
        Route::get('purchase/{purchase_id}/edit', PurchaseEdit::class)->name('purchase-edit');
        Route::get('purchase/{purchase_id}/details', PurchaseDetails::class)->name('purchase-details');
        Route::get('purchase-invoice/{purchase_id}', [PurchaseController::class, 'invoice'])->name('purchase-invoice');
    });

    Route::middleware(['permission:26,visible_flag'])->group(function () {
        Route::get('purchase-return', PurchaseReturn::class)->name('purchase-return');
        Route::get('purchase-return/create', PurchaseReturnCreate::class)->name('purchase-return-create');
        Route::get('purchase-return/{purchase_return_id}/edit', PurchaseReturnEdit::class)->name('purchase-return-edit');
        Route::get('purchase-return/{purchase_return_id}/details', PurchaseReturnDetails::class)->name('purchase-return-details');
        Route::get('purchase-return-invoice/{purchase_id}', [PurchaseController::class, 'returnInvoice'])->name('purchase-return-invoice');
    });

    // ------------- purchase end ----------------


    // ------------- sale start ----------------

    Route::middleware(['permission:27,visible_flag'])->group(function () {
        Route::get('sale', Sales::class)->name('sale');
        Route::get('sale/create', SalesCreate::class)->name('sale-create');
        Route::get('sale/{sale_id}/edit', SalesEdit::class)->name('sale-edit');
        Route::get('sale/{sale_id}/details', SaleDetails::class)->name('sale-details');
        Route::get('sale-invoice/{sale_id}', [SaleController::class, 'invoice'])->name('sale-invoice');
    });

    Route::middleware(['permission:28,visible_flag'])->group(function () {
        Route::get('sale-return', SalesReturn::class)->name('sale-return');
        Route::get('sale-return/create', SalesReturnCreate::class)->name('sale-return-create');
        Route::get('sale-return/{sale_return_id}/edit', SalesReturnEdit::class)->name('sale-return-edit');
        Route::get('sale-return/{sale_return_id}/details', SalesReturnDetails::class)->name('sale-return-details');
        Route::get('sale-return-invoice/{sale_id}', [SaleController::class, 'returnInvoice'])->name('sale-return-invoice');
    });
    // ------------- sale end ----------------

    // ------------- sale start ----------------

    Route::middleware(['permission:30,visible_flag'])->group(function () {
        Route::get('product-damage', ProductDamage::class)->name('product-damage');
        Route::get('product-damage/create', ProductDamageCreate::class)->name('product-damage-create');
        Route::get('product-damage/{product_damage_id}/edit', ProductDamageEdit::class)->name('product-damage-edit');
    });

    // Route::get('sale-pdf', [SaleController::class, 'tcpdPDF'])->name('sale-pdf');

    // ------------- sale end ----------------

    // ------------- reports start ----------------

    Route::middleware(['permission:31,visible_flag'])->group(function () {
        Route::get('reports-supplier-info', [SupplierController::class, 'supplierInfo'])->name('supplier-info-reports');
        Route::get('supplier-ledger', SupplierLedger::class)->name('supplier-ledger');
        Route::get('supplier-ledger-pdf/{code}', [SupplierController::class, 'supplierLedgerPdf'])->name('supplier-ledger-pdf');
    });
    Route::middleware(['permission:33,visible_flag'])->group(function () {
        Route::get('reports-customer-info', [CustomerController::class, 'customerInfo'])->name('customer-info-reports');
        Route::get('customer-ledger', CustomerLedger::class)->name('customer-ledger');
        Route::get('customer-ledger-pdf/{code}', [CustomerController::class, 'customerLedgerPdf'])->name('customer-ledger-pdf');
    });

    Route::get('reports-product-list', [ProductController::class, 'productList'])->name('product-list-reports')->middleware('permission:35,visible_flag');

    Route::get('reports-product-purchase', ProductPurchaseReport::class)->name('reports-product-purchase')->middleware('permission:36,visible_flag');
    Route::post('product-purchase-report-pdf', [ProductController::class, 'purchaseReport'])->name('product-purchase-report-pdf')->middleware('permission:36,visible_flag');

    Route::get('reports-product-purchase-return', ProductPurchaseReturnReport::class)->name('reports-product-purchase-return')->middleware('permission:37,visible_flag');
    Route::post('product-purchase-return-report-pdf', [ProductController::class, 'purchaseReturnReport'])->name('product-purchase-return-report-pdf')->middleware('permission:37,visible_flag');

    Route::get('reports-product-stock', ProductStockReport::class)->name('reports-product-stock')->middleware('permission:38,visible_flag');
    Route::post('product-stock-report-pdf', [ProductController::class, 'purchaseStockReport'])->name('product-stock-report-pdf')->middleware('permission:38,visible_flag');

    Route::get('reports-product-stock-out', ProductStockOutReport::class)->name('reports-product-stock-out')->middleware('permission:39,visible_flag');

    Route::get('reports-product-damage', ProductDamageReport::class)->name('reports-product-damage')->middleware('permission:40,visible_flag');
    Route::post('product-damage-report-pdf', [ProductController::class, 'purchaseDamageReport'])->name('product-damage-report-pdf')->middleware('permission:40,visible_flag');

    Route::get('reports-product-expire', ProductExpiryReport::class)->name('reports-product-expire')->middleware('permission:41,visible_flag');
    Route::post('product-expire-report-pdf', [ProductController::class, 'purchaseExpireReport'])->name('product-expire-report-pdf')->middleware('permission:41,visible_flag');

    Route::get('reports-product-sale', ProductSaleReport::class)->name('reports-product-sale')->middleware('permission:42,visible_flag');
    Route::post('product-sale-report-pdf', [ProductController::class, 'productSaleReport'])->name('product-sale-report-pdf')->middleware('permission:42,visible_flag');
    Route::get('reports-product-sale/{type}', SaleReport::class)->name('reports-sale')->middleware('permission:42,visible_flag');
    Route::get('reports-product-sale-return', ProductSaleReturn::class)->name('reports-product-sale-return')->middleware('permission:43,visible_flag');
    Route::post('product-sale-return-report-pdf', [ProductController::class, 'productSaleReturnReport'])->name('product-sale-return-report-pdf')->middleware('permission:43,visible_flag');

    Route::get('reports-expense', ExpenseReport::class)->name('reports-expense');
    Route::post('expense-report-pdf', [ExpenseController::class, 'expenseReport'])->name('expense-report-pdf');



    // ------------- reports end ----------------

    // -------------Account reports start ----------------

    Route::middleware(['permission:45,visible_flag'])->group(function () {
        Route::get('account-transaction', TransactionReport::class)->name('account-transaction');
        Route::post('account-transaction-pdf', [AccountController::class, 'transactionReport'])->name('account-transaction-pdf');

        Route::get('account-payments', PaymentReport::class)->name('account-payments');
        Route::post('account-payments-pdf', [AccountController::class, 'paymentReport'])->name('account-payments-pdf');

        Route::get('trial-balance', TrialBalance::class)->name('trial-balance');
        Route::post('trial-balance-pdf', [AccountController::class, 'trialBalance'])->name('trial-balance-pdf');
    });

    // -------------Account reports end ----------------

    // -------------Expense start ----------------
    Route::middleware(['permission:18,visible_flag'])->group(function () {
        Route::get('expense-category', ExpenseCategory::class)->name('expense-category');
        Route::get('expense', Expense::class)->name('expense');
        Route::get('expense/create', ExpenseCreate::class)->name('expense-create');
        Route::get('expense/{expense_id}/edit', ExpenseEdit::class)->name('expense-edit');
        Route::get('expense/{expense_id}/details', ExpenseDetails::class)->name('expense-details');
        Route::get('expense-invoice/{expense_id}', [ExpenseController::class, 'invoice'])->name('expense-invoice');
    });

    // -------------Expense end ----------------


     // -------------requisitioR start ----------------
     Route::middleware(['permission:18,visible_flag'])->group(function () {
        Route::get('requisition', RequisitionList::class)->name('requisition');
        Route::get('requisition/create', RequisitionCreate::class)->name('requisition-create');
        Route::get('requisition/{requisition_id}/edit', RequisitionEdit::class)->name('requisition-edit');
        Route::get('requisition/{requisition_id}/details', RequisitionDetails::class)->name('requisition-details');
        Route::get('requisition-invoice/{requisition_id}', [RequisitionController::class, 'invoice'])->name('requisition-invoice');
    });

    // -------------Requisition end ----------------

});




Route::get('test', function () {

    // $data = DB::select("
    // SELECT account_code, parent_code, LEVEL as depth
    // FROM ACC_CHART_OF_ACCOUNTS
    // START WITH parent_code IS NULL
    // CONNECT BY PRIOR account_code = parent_code
    // ORDER SIBLINGS BY parent_code
    // ");

    // dd($data);
});