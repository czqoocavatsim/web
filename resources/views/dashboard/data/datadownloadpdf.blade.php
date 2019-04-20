<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        body {
            margin: 1em;
        }

        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<h2>Gander Oceanic FIR // Data for {{$basicData->fullName('FLC')}}</h2>
<h4>Requested at {{\Carbon\Carbon::now()}} Zulu time via the Gander Oceanic Core website.</h4>
<hr/>
<h4>Basic User Data</h4>
<table>
    <thead>
    <th>Item</th>
    <th>Value</th>
    </thead>
    <tbody>
        <tr>
            <td>VATSIM CID (User ID)</td>
            <td>{{$basicData->id}}</td>
        </tr>
        <tr>
            <td>Full Name</td>
            <td>{{$basicData->fullName('FL')}}</td>
        </tr>
        <tr>
            <td>Email Address</td>
            <td>{{$basicData->email}}</td>
        </tr>
        <tr>
            <td>VATSIM GRP Rating</td>
            <td>{{$basicData->rating}}</td>
        </tr>
        <tr>
            <td>VATSIM Division</td>
            <td>{{$basicData->division}}</td>
        </tr>
        <tr>
            <td>Permission level</td>
            <td>{{$basicData->permissions}}</td>
        </tr>
        <tr>
            <td>User Created At</td>
            <td>{{$basicData->created_at}}</td>
        </tr>
        <tr>
            <td>User Last Updated At</td>
            <td>{{$basicData->updated_at}}</td>
        </tr>
        <tr>
            <td>GDPR Subscription Status (0 = no, 1 = yes)</td>
            <td>{{$basicData->gdpr_subscribed_emails}}</td>
        </tr>
        <tr>
            <td>Avatar</td>
            <td>{{$basicData->avatar}}</td>
        </tr>
    </tbody>
</table>
<br>
<h4>User Notes</h4>
<table>
    <thead>
    <th>Date/Time</th>
    <th>Content</th>
    </thead>
    <tbody>
    @foreach($userNotes as $note)
    <tr>
        <td>{{$note->content}}</td>
        <td>{{$note->timestamp}}</td>
    </tr>
    @endforeach
    </tbody>
</table>
<br>
<h4>Controller Applications</h4>
<small>Status help: 0 = pending; 1 = denied; 2 = accepted; 3 = withdrawn</small>
<table>
    <thead>
    <th>Application ID</th>
    <th>Status</th>
    <th>Submitted at</th>
    <th>Processed at</th>
    <th>Processed by</th>
    <th>Applicant statement</th>
    <th>Staff comment</th>
    </thead>
    <tbody>
    @foreach($applications as $application)
        <tr>
            <td>{{$application->application_id}}</td>
            <td>{{$application->status}}</td>
            <td>{{$application->submitted_at}}</td>
            <td>{{$application->processed_at}}</td>
            <td>{{$application->processed_by}}</td>
            <td>{{$application->applicant_statement}}</td>
            <td>{{$application->staff_comment}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>