@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Daily check set up')
@section('breadcrumb-title', 'Daily check set up')

@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div style="text-align: right">
                        <a href="{{ url('dailychecksetcreate') }}"><button type="button" class="btn btn-success btn-sm">&nbsp;&nbsp;Add&nbsp;&nbsp;</button></a>
                        <!-- <button type="button" name="today_mail" id="today_mail" class="btn btn-info btn-sm">&nbsp;&nbsp;E-mail - today&nbsp;&nbsp;</button> -->
                    </div>
                    <br>
                    <table valign="bottom" id="dt" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Tester</th>
                                <th class="text-center">Due date</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">

@endsection
@section('scripts')
<script>

    $(document).ready(function() {
        var oTable = $('#dt').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: true,
            ajax: {
                url: "{{ url('dailychecksettable') }}",
            },
            columns: [
                {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                {'className': "text-center", data: 'name_tester', name: 'name_tester'},
                {'className': "text-center", data: 'due_date', name: 'due_date'},
            ],
            "order": [[ 0, "desc" ]],
            rowCallback: function (row, data, index) {
                var button = '';
                @if(Auth::user()->status != 2)
                    button += '<a href="../dailychecksetedit/'+data.id_daily+'"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    button += '<button class="btn btn-danger btn-sm" onclick="deleteid(' + data.id_daily + ')" title="Delete"><i class="fa fa-trash-o" style="font-size:18px;"></i></button>&nbsp;';
                @else
                    button += '<a href="../dailychecksetedit/'+data.id_daily+'"><button class="btn btn-info btn-sm"><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                @endif


                $('td:eq(0)', row).html(button);

 

            }
        }); 

    });

    function deleteid(id)
    {
        swal({
            title: "Are you sure?",
            text: "Confirm delete ?",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            $.ajax({
                url: "{{ url('deleteDailycheckset') }}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "id": id,
                    "_token": '{{ csrf_token() }}',
                },
                success: function(data) {

                   if (data.success == 1) {
                        swal("Success", "delete data success", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        
                    }

                }
            });
        });

    }

    
    $('#today_mail').click(function () {

        var dt = new Date();
        $.ajax({
            url: "{{ url('Dailycheckmail') }}",
            type: 'GET',
            success: function(data) {

                if (data == "true") {
                    swal("Success", "Successfully sent email", "success");
                }
                   
            }
        });

    });

</script>
@endsection
