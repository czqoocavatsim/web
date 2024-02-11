@extends('admin.training.layouts.main')
@section('training-content')
    <h1 class="blue-text pb-2">{{ $announcement->title }}</h1>
    <table class="table dt table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col">Read Acknowledgement</th>
                <th scope="col">Unread Acknowledgement</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <ul>
                        @foreach (\App\Models\Training\ControllerAcknowledgement::where('announcement_id', $announcement->id)->get() as $readAcknowledgement)
                            <li>{{ $readAcknowledgement->user_id }}</li>
                        @endforeach
                    </ul>
                </td>
                <td>
                    <ul>
                        @foreach ($announcement->getReadMembers() as $readMembers)
                            <li>{{ $readMembers->user_id }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
