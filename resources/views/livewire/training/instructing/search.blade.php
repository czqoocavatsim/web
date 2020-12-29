<div>
    <input wire:model="search" type="text" class="form-control" placeholder="Search (enter more than 2 characters)..."/>
    <div wire:loading.remove>
        @if (count($resultsStudents) > 0)
            <p class="text-muted mb-1 mt-3" style="font-size: 1.1em;">Students</p>
        @endif
        <div class="list-group z-depth-1 mt-3">
            @foreach($resultsStudents as $r)
                <a href="#" class="list-group-item list-group-item-action waves-effect">
                    <div class="d-flex flex-column">
                        <h5 class="blue-text fw-700 mb-2">{{$r->user->fullName('FLC')}}</h5>
                        <div class="d-flex flex-row">
                            @foreach($r->labels as $label)
                            <div class="mr-1 pb-0">
                                <span class="badge shadow-none {{$label->label()->colour}}" style="height: 8px; width: 25px;">&nbsp;</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        @if (count($resultsInstructors) > 0)
        <p class="text-muted mb-1 mt-3" style="font-size: 1.1em;">Instructors</p>
        @endif
        <div class="list-group z-depth-1 mt-3">
            @foreach($resultsInstructors as $r)
                <a href="#" class="list-group-item list-group-item-action waves-effect">
                    <div class="d-flex flex-column">
                        <h5 class="blue-text fw-700 mb-0">{{$r->user->fullName('FLC')}}</h5>
                    </div>
                </a>
            @endforeach
        </div>
        @if (count($resultsTrainingSessions) > 0)
        <p class="text-muted mb-1 mt-3" style="font-size: 1.1em;">Training Sessions</p>
        @endif
        <div class="list-group z-depth-1 mt-3">
            @foreach($resultsTrainingSessions as $r)
                <a href="#" class="list-group-item list-group-item-action waves-effect">
                    <div class="d-flex flex-column">
                        <div class="d-flex flex-row align-items-center">
                            <img src="{{$r->instructor->user->avatar()}}" class="z-depth-1" style="height: 30px; width:30px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                            <img src="{{$r->student->user->avatar()}}" class="z-depth-1" style="height: 30px; z-index: 50; margin-left: -30px; width:30px;margin-right: 15px; margin-bottom: 3px; border-radius: 50%;">
                            <div>
                                <h5 class="blue-text fw-700 font-weight-bold">{{$r->student->user->fullName('FLC')}}</h5>
                                <p class="fw-400 mb-0">
                                    Scheduled for {{$r->scheduled_time->toDayDateTimeString()}}
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div wire:loading>
        <div class="fa-2x blue-text mt-3">
            <i class="fas fa-circle-notch fa-spin"></i>
        </div>
    </div>
</div>
