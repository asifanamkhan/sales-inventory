<div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>
    {{-- @if($brearcums) --}}
    <div style="display: flex; justify-content: space-between; align-items:center">
        <h3 style="padding: 0px 5px 10px 5px;">Role details</h3>
        <nav aria-label="breadcrumb" style="padding-right: 5px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">administrator</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('role') }}" style="">roles</a></li>
                <li class="breadcrumb-item active"><a wire:navigate href="{{ route('role-details', $role_id) }}"
                        style="color: #3C50E0">role details</a></li>
            </ol>
        </nav>
    </div>
    {{-- @endif --}}
    <div class="card p-4">

        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 25%">Role name</th>
                    <td>
                        {{ $roleDetail->role_name }}
                    </td>
                </tr>
                <tr>
                    <th>Role description</th>
                    <td>
                        {{ $roleDetail->role_desc }}
                    </td>
                </tr>
            </table>
            <table class="table table-bordered">
                <thead>
                    <th>Module name</th>
                    <th>Sub module name</th>
                    <th class="text-center">Visible</th>
                    <th class="text-center">Write</th>
                    <th class="text-center">Edit</th>
                    <th class="text-center">Read</th>
                </thead>
                <tbody>

                    @foreach ($role_permissions as $permission)

                    <tr>
                        <td>

                            {{ $permission->module_name }}
                        </td>
                        <td>

                            {{ $permission->module_dtl_name }}
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <input disabled @if ($permission->visible_flag == 1) checked @endif
                                type="checkbox" class="form-check-input">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <input @if ($permission->write_flag == 1) checked @endif
                                disabled type="checkbox" class="form-check-input">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <input @if ($permission->edit_flag == 1) checked @endif
                                disabled type="checkbox" class="form-check-input">
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <input @if ($permission->read_flag == 1) checked @endif
                                disabled type="checkbox" class="form-check-input">
                            </div>
                        </td>

                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>


    </div>

</div>
