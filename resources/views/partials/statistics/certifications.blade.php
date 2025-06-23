<div class="col-md-4 mb-4">
    <h4 class="font-weight-bold blue-text mb-4">Newest Controllers</h4>
    <ul class="list-unstyled">
        @foreach ($certifications as $cert)
            <li class="mb-1">
                <div class="d-flex flex-row">
                    <p class="mb-0 ml-1">
                        <span style="font-size: 1.4em;">
                            <img src="{{ $cert->controller->avatar() }}" style="height: 35px !important; width: 35px !important; margin-right: 10px; margin-bottom: 3px; border-radius: 50%;">
                            <div class="d-flex flex-column ml-2">
                                <h5 class="fw-400">{{ $cert->controller->fullName('FL') }}</h5>
                                <p title="{{ $cert->timestamp->toDayDateTimeString() }}">{{ $cert->timestamp->diffForHumans() }}</p>
                            </div>
                        </span>
                    </p>
                </div>
            </li>
        @endforeach
    </ul>
</div>