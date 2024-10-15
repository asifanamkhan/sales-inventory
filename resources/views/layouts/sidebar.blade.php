<nav id="sidebar">
    <div class="sidebar-header">
        <div>INVENTORY</div>
    </div>
    <ul class="list-unstyled components">
        <li>
            <a href="#"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li>
            <a href="#adminSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-screwdriver-wrench"></i> Administrator
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('role') ? 'show' : ' ' }}
        {{ request()->routeIs('role-create') ? 'show' : ' ' }}
        {{ request()->routeIs('role-details') ? 'show' : ' ' }}
        {{ request()->routeIs('module') ? 'show' : ' ' }}
        {{ request()->routeIs('company-info') ? 'show' : ' ' }}
        {{ request()->routeIs('user') ? 'show' : ' ' }}
        {{ request()->routeIs('user-create') ? 'show' : ' ' }}

        " id="adminSubmenu">
                @permission(1,'visible_flag')
                <li class="{{ request()->routeIs('company-info') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('company-info') }}"> - Company</a>
                </li>
                @endpermission
                @permission(2,'visible_flag')
                <li class="{{ request()->routeIs('module') ? 'active' : ' ' }} ">
                    <a class="list" wire:navigate href="{{ route('module') }}"> - Module</a>
                </li>
                @endpermission
                @permission(3,'visible_flag')
                <li class="
                {{ request()->routeIs('user') ? 'active' : ' ' }}
                {{ request()->routeIs('user-create') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('user') }}"> - Users</a>
                </li>
                @endpermission
                <li class="
                    {{ request()->routeIs('role') ? 'active' : ' ' }}
                    {{ request()->routeIs('role-create') ? 'active' : ' ' }}
                    {{ request()->routeIs('role-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('role') }}"> - Role</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - User role access</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#HRMSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-user-gear"></i> HRM settings
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('branch') ? 'show' : ' ' }}
        {{ request()->routeIs('department') ? 'show' : ' ' }}
        {{ request()->routeIs('designation') ? 'show' : ' ' }}
        {{ request()->routeIs('employee') ? 'show' : ' ' }}
        {{ request()->routeIs('supplier') ? 'show' : ' ' }}
        {{ request()->routeIs('supplier-create') ? 'show' : ' ' }}
        {{ request()->routeIs('supplier-edit') ? 'show' : ' ' }}
        {{ request()->routeIs('customer') ? 'show' : ' ' }}
        {{ request()->routeIs('customer-create') ? 'show' : ' ' }}
        {{ request()->routeIs('customer-edit') ? 'show' : ' ' }}

        " id="HRMSubmenu">
                <li class="{{ request()->routeIs('branch') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('branch') }}"> - Branch</a>
                </li>
                <li class="{{ request()->routeIs('department') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('department') }}"> - Department</a>
                </li>

                <li class="{{ request()->routeIs('designation') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('designation') }}"> - Designation</a>
                </li>
                <li class="{{ request()->routeIs('employee') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('employee') }}"> - Employee</a>
                </li>
                <li class="
                {{ request()->routeIs('supplier') ? 'active' : ' ' }}
                {{ request()->routeIs('supplier-create') ? 'active' : ' ' }}
                {{ request()->routeIs('supplier-edit') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('supplier') }}"> - Supplier</a>
                </li>
                <li class="
                {{ request()->routeIs('customer') ? 'active' : ' ' }}
                {{ request()->routeIs('customer-create') ? 'active' : ' ' }}
                {{ request()->routeIs('customer-edit') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('customer') }}"> - Customer</a>
                </li>

            </ul>
        </li>

        <li>
            <a href="#accountSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-money-check-dollar"></i> Accounts settings
            </a>
            <ul class="collapse list-unstyled
        {{-- {{ request()->routeIs('counter') ? 'show' : ' ' }} --}}

        " id="accountSubmenu">

                <li class="">
                    <a class="list" wire:navigate href="">Chart of accounts</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Expense setup</a>
                </li>

            </ul>
        </li>

        <li>
            <a href="#productSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-gear"></i> Product settings
            </a>
            <ul class="collapse list-unstyled
            {{ request()->routeIs('product-group') ? 'show' : ' ' }}
            {{ request()->routeIs('product-brand') ? 'show' : ' ' }}
            {{ request()->routeIs('product-unit') ? 'show' : ' ' }}
            {{ request()->routeIs('product-color') ? 'show' : ' ' }}
            {{ request()->routeIs('product-category') ? 'show' : ' ' }}
            {{ request()->routeIs('product') ? 'show' : ' ' }}
            {{ request()->routeIs('product-create') ? 'show' : ' ' }}
            {{ request()->routeIs('product-edit') ? 'show' : ' ' }}
            {{ request()->routeIs('product-pricing-list') ? 'show' : ' ' }}

        " id="productSubmenu">
                <li class="{{ request()->routeIs('product-group') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-group') }}"> - Product group</a>
                </li>
                <li class="{{ request()->routeIs('product-brand') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-brand') }}"> - Product brand</a>
                </li>

                <li class="{{ request()->routeIs('product-category') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-category') }}"> - Product category</a>
                </li>
                <li class="{{ request()->routeIs('product-unit') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-unit') }}"> - Product unit</a>
                </li>
                <li class="{{ request()->routeIs('product-color') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-color') }}"> - Product colors</a>
                </li>
                <li class="
                {{ request()->routeIs('product') ? 'active' : ' ' }}
                {{ request()->routeIs('product-create') ? 'active' : ' ' }}
                {{ request()->routeIs('product-edit') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('product') }}"> - Product information</a>
                </li>
                <li class="
                {{ request()->routeIs('product-pricing-list') ? 'active' : ' ' }}
                ">
                    <a class="list" wire:navigate href="{{ route('product-pricing-list') }}"> - Pricing list</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> -
                        Discount list</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#purchaseSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-cart-shopping"></i> Purchase settings
            </a>
            <ul class="collapse list-unstyled
            {{ request()->routeIs('purchase') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-create') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-edit') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-details') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return-create') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return-edit') ? 'show' : ' ' }}
            {{ request()->routeIs('purchase-return-details') ? 'show' : ' ' }}

        " id="purchaseSubmenu">
                <li class="
                {{ request()->routeIs('purchase') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-edit') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('purchase') }}"> - Purchase list</a>
                </li>
                <li class="
                {{ request()->routeIs('purchase-create') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('purchase-create') }}"> - Purchase entry</a>
                </li>
                <li class="
                {{ request()->routeIs('purchase-return') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-return-create') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-return-edit') ? 'active' : ' ' }}
                {{ request()->routeIs('purchase-return-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('purchase-return') }}"> - Purchase return</a>
                </li>
            </ul>
        </li>

        <li>
            <a style="" href="#salesSubmenu" data-toggle="collapse" aria-expanded="true"
                class="dropdown-toggle main-list">
                <i class="fa-solid fa-scale-balanced"></i> Sales settings
            </a>
            <ul class="collapse list-unstyled
        {{ request()->routeIs('sale') ? 'show' : ' ' }}
        {{ request()->routeIs('sale-create') ? 'show' : ' ' }}
        {{ request()->routeIs('sale-edit') ? 'show' : ' ' }}
        {{ request()->routeIs('sale-details') ? 'show' : ' ' }}
        {{ request()->routeIs('sale-return') ? 'show' : ' ' }}
        {{ request()->routeIs('sale-return-create') ? 'show' : ' ' }}
        {{ request()->routeIs('sale-return-edit') ? 'show' : ' ' }}
        {{ request()->routeIs('sale-return-details') ? 'show' : ' ' }}
        " id="salesSubmenu">
                <li class="
                {{ request()->routeIs('sale') ? 'active' : ' ' }}
                {{ request()->routeIs('sale-edit') ? 'active' : ' ' }}
                {{ request()->routeIs('sale-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('sale') }}"> - Sales list</a>
                </li>
                <li class="{{ request()->routeIs('sale-create') ? 'active' : ' ' }}">
                    <a class="list" href="{{ route('sale-create') }}"> - Sales entry</a>
                </li>
                <li class="
                {{ request()->routeIs('sale-return') ? 'active' : ' ' }}
                {{ request()->routeIs('sale-return-create') ? 'active' : ' ' }}
                {{ request()->routeIs('sale-return-edit') ? 'active' : ' ' }}
                {{ request()->routeIs('sale-return-details') ? 'active' : ' ' }}
                ">
                    <a class="list" wire:navigate href="{{ route('sale-return') }}"> - Sales return</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#productDamageSubmenu" data-toggle="collapse" aria-expanded="true"
                class="dropdown-toggle main-list">
                <i class="fa fa-chain-broken" aria-hidden="true"></i> Product damage
            </a>
            <ul class="collapse list-unstyled
            {{ request()->routeIs('product-damage') ? 'show' : ' ' }}
            {{ request()->routeIs('product-damage-create') ? 'show' : ' ' }}
            {{ request()->routeIs('product-damage-edit') ? 'show' : ' ' }}

        " id="productDamageSubmenu">
                <li class="{{ request()->routeIs('product-damage') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-damage') }}"> - Damage list</a>
                </li>
                <li class="{{ request()->routeIs('product-damage-create') ? 'active' : ' ' }}">
                    <a class="list" href="{{ route('product-damage-create') }}"> - Damage entry</a>
                </li>

            </ul>
        </li>

        <li>
            <a href="#misReportSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-chart-line"></i> MIS reports
            </a>
            <ul class="collapse list-unstyled
        {{-- {{ request()->routeIs('counter') ? 'show' : ' ' }} --}}

        " id="misReportSubmenu">
                <li class="">
                    <a class="list" target="_blank" href="{{ route('supplier-info-reports') }}"> - Supplier Info</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Supplier Ledger</a>
                </li>
                <li class="">
                    <a class="list" target="_blank" href="{{ route('customer-info-reports') }}"> - Customer Info</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Customer Ledger</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Product Lists</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Product Purchase Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Purchase Return Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Product Stock Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Stock Out Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Product Damage Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Product Expiry Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Daily Sells Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Monthly Sells Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Yearly Sells Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Sells Return Report</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href=""> - Payment Report</a>
                </li>
            </ul>
        </li>

        <li class="">
            <a wire:navigate href=""><i class="fas fa-hdd"></i></i> Data backup</a>
        </li>
        <li class="">
            <a wire:navigate href=""><i class="fa fa-question-circle" aria-hidden="true"></i></i> Help</a>
        </li>
    </ul>
</nav>
