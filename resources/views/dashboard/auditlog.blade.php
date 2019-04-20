@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    <div class="container" style="margin-top: 20px;">
        <h2>Audit Log</h2>
        <p>Abuse of this log will result in removal of permissions and referrals to ZQO1/VATCAN. The difference between a private and
            a non-private entry is that non-private entries will not be available to people under a GDPR request.</p>
        </p>
        @if (count($entries) < 1)
            <p>No logs</p>
        @else
            <table id="dataTable" class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Time</th>
                        <th scope="col">User</th>
                        <th scope="col">Affected User</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($entries as $entry)
                    <tr>
                        <th scope="row">{{$entry->time}}</th>
                        <td>
                            {{App\User::find($entry->user_id)->fname}} {{App\User::find($entry->user_id)->lname}} {{App\User::find($entry->user_id)->id}}
                        </td>
                        <td>
                            {{App\User::find($entry->affected_id)->fname}} {{App\User::find($entry->affected_id)->lname}} {{App\User::find($entry->affected_id)->id}}
                        <td>
                            {{$entry->action}}
                            &nbsp;
                            @if ($entry->private == 1)
                            (private)
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
        <br/>
        <h5>Insert Entry</h5>
        <div class="input-group mb-3">
            <form method="POST" class="input-group" action="{{route('auditlog.insert')}}">
                {{ csrf_field() }}
                <input type="text" name="message" placeholder="Enter a message here.." class="form-control">
                <input type="text" name="affected_id" maxlength="7" placeholder="Affected ID" class="form-control">
                <select name="private" class="form-control">
                    <option selected value="no">Not private</option>
                    <option value="yes">Private</option>
                </select>
                <div class="input-group-append">
                    <input value="Submit" type="submit" class="btn btn-success"></div>
                </div>
            </form>
        </div>
        <script>
                $(document).ready(function() {
                    $('#dataTable').DataTable( {
                        "order": [[ 0, "desc" ]]
                    } );
                } );
            </script>
    </div>
@stop