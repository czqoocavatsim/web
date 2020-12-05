@extends('admin.training.layouts.main')
@section('training-content')
<a href="{{route('training.admin.roster')}}" class="blue-text" style="font-size: 1.2em;"> <i class="fas fa-arrow-left"></i> Roster</a>
<h2 class="blue-text font-weight-bold mt-2 pb-2">Home Page New Controllers</h2>
<table class="table dt table-hover table-bordered">
    <thead>
        <th>CID</th>
        <th>Date</th>
        <th>Remove</th>
    </thead>
    <tbody>
        @foreach ($entries as $e)
        <tr data-row-id="{{$e->id}}">
            <th>{{$e->controller_id}}</th>
            <td data-sort="{{$e->timestamp}}">{{$e->timestamp->toDayDateTimeString()}}</td>
            <td>
                <a class="remove-entry" href=""><i class="red-text fas fa-times"></i>&nbsp;Remove</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<div class="md-form input-group mt-3">
    <input type="text" id="addEntryCID" class="form-control" placeholder="Add a controller (CID)">
    <div class="input-group-append">
        <button onclick="addEntry()" class="btn btn-md btn-light m-0 px-5" type="button">Add</button>
    </div>
</div>
<script>
    $(document).ready(function () {
        $(".remove-entry").on('click', function () {
            let row = $(this).parents().eq(1)
            //Make ajax request
            $.ajax({
                type: 'POST',
                url: window.location.href + '/remove',
                data: {
                    entry_id: $(row).data('row-id'),
                },
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    //Show saved toast
                    Toastify({
                        text: `Entry removed!`,
                        duration: 5000,
                        close: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: 'right', // `left`, `center` or `right`
                        backgroundColor: '#00C851',
                        offset: {
                            x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                            y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                        },
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                    }).showToast();

                    location.reload();
                },
                error: function(data) {
                    //Error
                    Toastify({
                        text: `Error (${data.responseJSON.message})`,
                        duration: 5000,
                        close: true,
                        gravity: "bottom", // `top` or `bottom`
                        position: 'right', // `left`, `center` or `right`
                        backgroundColor: '#ff4444',
                        offset: {
                            x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                            y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                        },
                        stopOnFocus: true, // Prevents dismissing of toast on hover
                    }).showToast();
                }
            })
        })
    })

    function addEntry()
    {
        $.ajax({
            type: 'POST',
            url: window.location.href + '/add',
            data: {
                cid: $('#addEntryCID').val()
            },
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data) {
                //Show saved toast
                Toastify({
                    text: `Entry added!`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#00C851',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();

                location.reload();
            },
            error: function(data) {
                //Error
                Toastify({
                    text: `Error (${data.responseJSON.message})`,
                    duration: 5000,
                    close: true,
                    gravity: "bottom", // `top` or `bottom`
                    position: 'right', // `left`, `center` or `right`
                    backgroundColor: '#ff4444',
                    offset: {
                        x: 100, // horizontal axis - can be a number or a string indicating unity. eg: '2em'
                        y: 50 // vertical axis - can be a number or a string indicating unity. eg: '2em'
                    },
                    stopOnFocus: true, // Prevents dismissing of toast on hover
                }).showToast();
            }
        })
    }

    $(document).ready(function () {
        $('.table.dt').DataTable();
    })
</script>
@endsection
