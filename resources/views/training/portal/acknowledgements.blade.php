@extends('training.portal.layouts.main')
@section('title', 'Controller Acknowledgements - ')
@section('page-header-title', 'Controller Acknowledgements')

@section('portal-content')
    @foreach (auth()->user()->getUnreadAcknowledgements() as $unread)
        @if ($loop->first)
            <h4 class="blue-text mb-3 fw-500">Unread Acknowledgements</h4>
        @endif
        <div class="list-group-item waves-effect">
            <div class="row">
                <div class="d-flex flex-row w-100 align-items-center h-100">
                    <div class="col">{{ $unread->title }}</div>
                    <div class="col-sm-3">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#unreadModal{{ $unread->id }}"><i
                                class="fa fa-eye">
                            </i>&nbsp;View Acknowledgement</a>
                    </div>
                </div>
            </div>
            <p class="mt-2 mb-0">Published on: {{ $unread->created_at->format('d M Y') }}</p>
        </div>
        {{-- MODALS FOR UNREAD --}}
        <div class="modal fade" id="unreadModal{{ $unread->id }}" tabindex="-1" role="dialog">
            <form method="POST"
                action="{{ route('training.portal.controller-acknowledgements.read', ['announcement' => $unread->id]) }}">
                @csrf
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">{{ $unread->title }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <blockquote style="font-size: 12px !important;">
                                <p style="font-size: 12px !important;">{{ $unread->content }}</p>
                            </blockquote>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success black-text"><i class="fas fa-check"></i> Read
                                Acknowledgement</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endforeach

    @foreach (\App\Models\Training\ControllerAcknowledgement::where('user_id', auth()->id())->get() as $readAcknowledgements)
        @if ($loop->first)
            <h4 class="grey-text mb-3 mt-3 fw-500">Read Acknowledgements</h4>
        @endif
        @php
            $readAcknowledgement = $readAcknowledgements->getAcknowledgement;
        @endphp
        <div class="list-group-item waves-effect">
            <div class="row">
                <div class="d-flex flex-row w-100 align-items-center h-100">
                    <div class="col">{{ $readAcknowledgement->title }}</div>
                    <div class="col-sm-3">
                        <a href="javascript:void(0)" data-toggle="modal"
                            data-target="#readModal{{ $readAcknowledgement->id }}"><i class="fa fa-eye">
                            </i>&nbsp;View Acknowledgement</a>
                    </div>
                </div>
            </div>
            <p class="mt-2 mb-0">Read on: {{ $readAcknowledgement->created_at->format('d M Y') }}</p>
        </div>
        {{-- MODALS FOR READ --}}
        <div class="modal fade" id="readModal{{ $readAcknowledgement->id }}" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ $readAcknowledgement->title }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <blockquote style="font-size: 12px !important;">
                            <p style="font-size: 16px !important;">{{ $readAcknowledgement->content }}</p>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
