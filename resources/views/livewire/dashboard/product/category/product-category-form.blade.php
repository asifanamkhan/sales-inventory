<div class="p-4">
    <div class="modal-header" style="justify-content: space-between">
            <h4 class="text-center" style="color: #3C50E0">Product category</h4>
            <b type="button" class="modal-close-icon" class="close" data-dismiss="modal"
            aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </b>
    </div>
    <div wire:loading class="spinner-border text-primary custom-loading" branch="status">
        <span class="sr-only">Loading...</span>
    </div>
    @if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
    </div>
    @endif

    <form wire:submit="@if($editForm) update @else store @endif" action="">
        <div class="row mt-2">

            <div class="col-md-10 offset-1 form-group mb-3">
                <div class="text-center mb-3 d-flex justify-content-center">
                    @if($editForm)
                    @if (@$state['photo'])
                    <img src="{{ $state['photo']->temporaryUrl() ?? '' }}" alt=""
                        style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                    @elseif(!@$state['photo'] && @$state['old_photo'])
                    <img src="{{ asset('storage/app/'.$state['old_photo']) }}" alt=""
                        style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                    @else
                    <img src="{{ asset('public/img/no-img.png') }}" alt=""
                        style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                    @endif
                    @else
                    @if (@$state['photo'])
                    <img src="{{ $state['photo']->temporaryUrl() ?? '' }}" alt=""
                        style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                    @else
                    <img src="{{ asset('public/img/no-img.png') }}" alt=""
                        style="border-radius: 50% !important; width:150px; height: 150px" class="img-fluid img-thumbnail">
                    @endif
                    @endif

                </div>
                <label for="" class="text-center">Image</label>
                <input wire:model='state.photo' type='file' class="form-control @error('photo') is-invalid @enderror">
                @error('photo')
                <small class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="col-md-10 offset-1">
                <div class="form-group mb-3">
                    <label for="">Name <span style="color: red"> * </span></label>
                    <input wire:model='state.catagories_name' type='text' label='Name'
                        class="form-control @error('catagories_name') is-invalid @enderror">
                    @error('catagories_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-10 offset-1">
                <div class="form-group mb-3">
                    <label for="">Product group</label><span style="color: red"> * </span>
                    <select wire:model='state.group_name' name="" id="" class="form-select">
                        <option value="">select</option>
                        @forelse ($groups as $group)
                        <option value="{{ $group->st_group_id }}">{{ $group->group_name }}</option>
                        @empty
                        <option value=""></option>
                        @endforelse
                    </select>

                    @error('group_name')
                    <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>
            <div class="col-md-10 offset-1">
                <div class="form-group">
                    <label for="">Description</label>
                    <textarea class="form-control" wire:model='state.description' name="" id="" cols="30"
                        rows="5"></textarea>

                </div>
            </div>
            <div class="mt-4 d-flex justify-content-center">
                <button class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>

</div>
