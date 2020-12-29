<div>
    <input wire:model="search" type="text" class="form-control" placeholder="Search (enter more than 2 characters)..."/>

    <div wire:loading.remove class="list-group z-depth-1 mt-3">
        @foreach($results as $r)
            <a href="#" class="list-group-item list-group-item-action waves-effect">
                <h5 class="blue-text fw-700">{{$r->user->fullName('FLC')}}</h5>
            </a>
        @endforeach
    </div>
    <div wire:loading>
        <div class="fa-2x blue-text mt-3">
            <i class="fas fa-circle-notch fa-spin"></i>
        </div>
    </div>
</div>
