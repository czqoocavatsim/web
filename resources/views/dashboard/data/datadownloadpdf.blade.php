<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<p>Gander Oceanic FIR https://ganderoceanic.com</p>
<h2>{{Auth::user()->fullName('FLC')}}</h2>
<h5>Data as of {{\Carbon\Carbon::now()}}.</h5>
<div style="border: 1px solid; padding: 10px;">
    This data has been gathered at the request of {{Auth::user()->fullName('FLC')}} in accordance with the Gander Oceanic FIR Privacy Policy and the European Union GDDPR. For more information, please visit https://ganderoceanic.com/privacy.
</div>
<h5>Basic Data</h5>
<table>
    <thead><td>Attribute</td><td>Data</td></thead>
    <tbody>
    <tr><td>First Name</td><td>{{Auth::user()->fname}}</td></tr>
    <tr><td>Last Name</td><td>{{Auth::user()->lname}}</td></tr>
    <tr><td>CID</td><td>{{Auth::user()->id}}</td></tr>
    <tr><td>Displayed First Name</td><td>{{Auth::user()->display_fname}}</td></tr>
    <tr><td>Display Last Name</td><td>@if (Auth::user()->display_last_name)True @else False @endif</td></tr>
    <tr><td>Display CID Only</td><td>@if (Auth::user()->display_cid_only)True @else False @endif</td></tr>
    <tr><td>Email</td><td>{{Auth::user()->email}}</td></tr>
    <tr><td>Rating</td><td>{{Auth::user()->rating_GRP}} ({{Auth::user()->rating_id}}, {{Auth::user()->rating_short}}, {{Auth::user()->rating_long}})</td></tr>
    <tr><td>VATSIM Registration Date</td><td>{{Auth::user()->reg_date}}</td></tr>
    <tr><td>Region</td><td>{{Auth::user()->region_name}} ({{Auth::user()->region_code}})</td></tr>
    <tr><td>Division</td><td>{{Auth::user()->division_name}} ({{Auth::user()->division_code}})</td></tr>
    <tr><td>Subdivision</td><td>{{Auth::user()->subdivision_name}} ({{Auth::user()->subdivision_code}})</td></tr>
    <tr><td>Permissions</td><td>{{Auth::user()->permissions}}</td></tr>
    <tr><td>Accepted privacy policy</td><td>{{Auth::user()->init}}</td></tr>
    <tr><td>Subscribed to emails</td><td>{{Auth::user()->gdpr_subscribed_emails}}</td></tr>
    <tr><td>Avatar</td><td>{{Auth::user()->avatar}}</td></tr>
    <tr><td>Biography</td><td>{{Auth::user()->biography}}</td></tr>
    </tbody>
</table>
</body>
</html>
