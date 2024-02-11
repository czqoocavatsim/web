@extends('admin.training.layouts.main')
@section('training-content')
<h1 class="blue-text pb-2">Controller Acknowledgements</h1>
<p>Select an announcement to view a list of controllers who have acknowledged it and those who haven't.</p>
    <table class="table dt table-hover table-bordered">
        <thead>
            <th>Announcement</th>
            <th>Action</th>
        </thead>
        <tbody>
            @foreach (\App\Models\News\Announcement::where('controller_acknowledgement', true)->get() as $announcement)
                <tr>
                    <td>
                        {{ $announcement->title }}
                    </td>
                    <td>
                        <a href="{{route('training.admin.acknowledgement.find', ['announcement'=>$announcement->id])}}"><i class="fas fa-eye"></i>&nbsp;View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('.table.dt').DataTable();
        })
    </script>
@endsection
