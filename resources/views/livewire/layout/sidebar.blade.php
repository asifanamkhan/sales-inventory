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
                <i class="fa fa-user"></i> Administrator
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
                    <a class="list" wire:navigate href="{{ route('company-info') }}">Company information</a>
                </li>
                @endpermission
                @permission(2,'visible_flag')
                <li class="{{ request()->routeIs('module') ? 'active' : ' ' }} ">
                    <a class="list" wire:navigate href="{{ route('module') }}">Module information</a>
                </li>
                @endpermission
                @permission(3,'visible_flag')
                <li class="
                {{ request()->routeIs('user') ? 'active' : ' ' }}
                {{ request()->routeIs('user-create') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('user') }}">Users</a>
                </li>
                @endpermission
                <li class="
                    {{ request()->routeIs('role') ? 'active' : ' ' }}
                    {{ request()->routeIs('role-create') ? 'active' : ' ' }}
                    {{ request()->routeIs('role-details') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('role') }}">Role</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">User role access</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#HRMSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa fa-users"></i> HRM settings
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
                    <a class="list" wire:navigate href="{{ route('branch') }}">Branch information</a>
                </li>
                <li class="{{ request()->routeIs('department') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('department') }}">Department information</a>
                </li>

                <li class="{{ request()->routeIs('designation') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('designation') }}">Designation information</a>
                </li>
                <li class="{{ request()->routeIs('employee') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('employee') }}">Employee information</a>
                </li>
                <li class="
                {{ request()->routeIs('supplier') ? 'active' : ' ' }}
                {{ request()->routeIs('supplier-create') ? 'active' : ' ' }}
                {{ request()->routeIs('supplier-edit') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('supplier') }}">Supplier information</a>
                </li>
                <li class="
                {{ request()->routeIs('customer') ? 'active' : ' ' }}
                {{ request()->routeIs('customer-create') ? 'active' : ' ' }}
                {{ request()->routeIs('customer-edit') ? 'active' : ' ' }}
                 ">
                    <a class="list" wire:navigate href="{{ route('customer') }}">Customer information</a>
                </li>

            </ul>
        </li>

        <li>
            <a href="#accountSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-dollar-sign"></i> Accounts settings
            </a>
            <ul class="collapse list-unstyled
        {{-- {{ request()->routeIs('counter') ? 'show' : ' ' }} --}}

        " id="accountSubmenu">
                <li class="">
                    <a class="list" wire:navigate href="">Financial year setup</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Bank year setup</a>
                </li>

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
                <i class="fa fa-list"></i> Product settings
            </a>
            <ul class="collapse list-unstyled
            {{ request()->routeIs('product-group') ? 'show' : ' ' }}
            {{ request()->routeIs('product-brand') ? 'show' : ' ' }}

        " id="productSubmenu">
                <li class="{{ request()->routeIs('product-group') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-group') }}">Product group</a>
                </li>
                <li class="{{ request()->routeIs('product-brand') ? 'active' : ' ' }}">
                    <a class="list" wire:navigate href="{{ route('product-brand') }}">Product brand</a>
                </li>

                <li class="">
                    <a class="list" wire:navigate href="">Product category</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Product unit</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Product colors</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Product information</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Product pricing list</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Product discount list</a>
                </li>

            </ul>
        </li>

        <li>
            <a href="#purchaseSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa-solid fa-cart-shopping"></i> Purchase settings
            </a>
            <ul class="collapse list-unstyled
        {{-- {{ request()->routeIs('counter') ? 'show' : ' ' }} --}}

        " id="purchaseSubmenu">
                <li class="">
                    <a class="list" wire:navigate href="">Product purchase entry</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Product purchase return</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#salesSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-receipt"
                    viewBox="0 0 16 16">
                    <path
                        d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z" />
                    <path
                        d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5" />
                </svg> Sales settings
            </a>
            <ul class="collapse list-unstyled
        {{-- {{ request()->routeIs('counter') ? 'show' : ' ' }} --}}

        " id="salesSubmenu">
                <li class="">
                    <a class="list" wire:navigate href="">Sales entry</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Sales purchase</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Product damage</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#misReportSubmenu" data-toggle="collapse" aria-expanded="true" class="dropdown-toggle main-list">
                <i class="fa fa-list"></i> Mis reports
            </a>
            <ul class="collapse list-unstyled
        {{-- {{ request()->routeIs('counter') ? 'show' : ' ' }} --}}

        " id="salesSubmenu">
                <li class="">
                    <a class="list" wire:navigate href="">Report 1</a>
                </li>
                <li class="">
                    <a class="list" wire:navigate href="">Report 2</a>
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