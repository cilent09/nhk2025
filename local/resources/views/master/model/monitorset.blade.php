@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Monitoring set up')
@section('breadcrumb-title', 'Monitoring set up')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-block"> 

                <div class="row">
                    <div class="col-sm-12 col-xl-3">
                        <label>Start date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control">
                    </div>
                    <div class="col-sm-12 col-xl-3">
                        <label>End date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control">
                    </div>
                    <div class="col-sm-12 col-xl-3">
                        <label>Repeats</label>
                        <select id="repeats" name="repeats" class="form-control">
                            <option value="">Select</option>
                            <option value="no_repeats">no repeats</option>
                            <option value="daily">daily</option>
                            <option value="weeks">weeks</option>
                            <option value="months">months</option>
                            <option value="years">years</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-xl-3">
                        <label>&nbsp;</label><br>
                        <button id="chk" class="btn btn-success btnSearch"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div> 

            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div style="text-align: right">
                        <a href="{{ url('monitorsetcreate') }}"><button type="button" class="btn btn-success btn-sm">&nbsp;&nbsp;Add&nbsp;&nbsp;</button></a>
                        <!-- <button type="button" name="today_mail" id="today_mail" class="btn btn-info btn-sm">&nbsp;&nbsp;E-mail - today&nbsp;&nbsp;</button> -->
                    </div>
                    <br>
                    <table valign="bottom" id="dt" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Tester</th>
                                <th class="text-center">Topic</th>
                                <th class="text-center">Start date</th>
                                <th class="text-center">Repeats</th>
                                <!-- <th class="text-center">Repeats on the day</th> -->
                                <th class="text-center">End date</th>
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
                url: "{{ url('monitorsettable') }}",
                "data": function (d) {
                    d.date_start = $("#start_date").val();
                    d.date_end = $("#end_date").val();
                    d.repeats = $("#repeats").val();
                }
            },
            columns: [
                {'className': "text-center", data: 'id_mns', name: 'id_mns'},
                {'className': "text-center", data: 'name_tester', name: 'name_tester'},
                {'className': "text-center", data: 'topic', name: 'topic'},
                {'className': "text-center", data: 'start_date', name: 'start_date'},
                {'className': "text-center", data: 'newrepeats', name: 'newrepeats'},
                // {'className': "text-center", data: 'day', name: 'day'},
                {'className': "text-center", data: 'end_date', name: 'end_date'},
            ],
            "order": [[ 0, "desc" ]],
            rowCallback: function (row, data, index) {
                var button = '';


                @if(Auth::user()->status != 2)
                    
                    button += '<a href="../monitorsetedit/'+data.id_mns+'"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    button += '<button class="btn btn-danger btn-sm" onclick="deleteid(' + data.id_mns + ')" title="Delete"><i class="fa fa-trash-o" style="font-size:18px;"></i></button>&nbsp;';

                @else

                    button += '<a href="../monitorsetedit/'+data.id_mns+'"><button class="btn btn-info btn-sm"><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';

                @endif


                $('td:eq(0)', row).html(button); 

                console.log(data.day)

            }
        }); 

        $('.btnSearch').click(function(){
            oTable.ajax.reload();
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
                url: "{{ url('deleteMonitorset') }}",
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
            url: "{{ url('Monitoringmail') }}",
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
