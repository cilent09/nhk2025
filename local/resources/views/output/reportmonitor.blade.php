@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Monitoring report')
@section('breadcrumb-title', 'Monitoring report')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-block">
              
                    <div class="row">
                        <div class="col-sm-12 col-xl-4">
                            <label>Start date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <label>End date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <label>&nbsp;</label><br>
                            <button id="chk" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
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
                    <a onclick="exportall()"><button type="button" class="btn btn-primary btn-sm">&nbsp;&nbsp;Export All&nbsp;&nbsp;</button></a>
                    </div>
                    <br>
                    <table valign="bottom" id="dt" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                            
                            <tr>
                                <th class="text-center">Tester</th>
                                <th class="text-center">First date</th>
                                <th class="text-center">Laster</th>
                                <th class="text-center">Result</th>
                                <th class="text-center">Export</th>
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

    $(document).ready(function () {
        var oTable = $('#dt').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            ajax: {
                "url": "{{url('monitortables')}}",
                "data": function (d) {
                    d.date_start = $("#start_date").val();
                    d.date_end = $("#end_date").val();
                }
            },
            columns: [{
                    className: "text-center",
                    data: 'name_tester',
                    name: 'name_tester'
                },
                {
                    className: "text-center",
                    data: 'id_mnu',
                    name: 'id_mnu'
                },
                {
                    className: "text-center",
                    data: 'id_mnu',
                    name: 'id_mnu'
                },
                {
                    className: "text-center",
                    data: 'id_mnu',
                    name: 'id_mnu'
                },
                {
                    className: "text-center",
                    data: 'id_mnu',
                    name: 'id_mnu'
                }
            ],
            rowCallback: function (row, data, index) {

                var result = '<span id="result'+data.id_mnu+'"><p style="text-align:center"><i class="fa fa-minus fa-3x" style="color:dimgray;"></i> </p></span>';

                if(data.result == "NOT_PASS"){
                    result = '<span id="result'+data.id_mnu+'"><button type="button" class="btn btn-danger btn-sm">&nbsp;&nbsp;NOT PASS&nbsp;&nbsp;</button></span>';
                }
                if(data.result == "PASS"){
                    result = '<span id="result'+data.id_mnu+'"><button type="button" class="btn btn-success btn-sm">&nbsp;&nbsp;PASS&nbsp;&nbsp;</button></span>';
                }

                var firstdate = '<input style="width: 80%" class="form-control" type="date" id="datestart'+data.id_mnu+'" name="datestart'+data.id_mnu+'" onchange="datestartfs('+data.id_mnu+')">';
                var lastdate = '<input style="width: 80%" class="form-control" type="date" id="dateend'+data.id_mnu+'" name="dateend'+data.id_mnu+'" onchange="datestartfs('+data.id_mnu+')">';
                var btexports = '<button type="button" onclick="exports('+data.id_mnu+')" class="btn btn-primary btn-sm">&nbsp;&nbsp;Export&nbsp;&nbsp;</button>';

                if(data.date_start != null){

                    firstdate = '<input style="width: 80%" class="form-control" type="date" id="datestart'+data.id_mnu+'" name="datestart'+data.id_mnu+'" onchange="datestartfs('+data.id_mnu+')" value="'+data.date_start+'">'; 
                }

                if(data.date_end != null){

                    lastdate = '<input style="width: 80%" class="form-control" type="date" id="dateend'+data.id_mnu+'" name="dateend'+data.id_mnu+'" onchange="datestartfs('+data.id_mnu+')" value="'+data.date_end+'">'; 
                }
    
                $('td:eq(1)', row).html(firstdate);
                $('td:eq(2)', row).html(lastdate);
                $('td:eq(3)', row).html(result);
                $('td:eq(4)', row).html(btexports);


            }
        });
        $('.btn-success').click(function(){

            oTable.ajax.reload();
        });

    });
    
    function exportall() {
        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });

        var datestart = $('#start_date').val();
        var dateend = $('#end_date').val();

        $.ajax({
            type: 'post',
            url: "{{ url('excel_monitoringall') }}",
            data: 
            {
                'datestart' : datestart,
                'dateend' : dateend,
            },
            success: function (data) {
                if (data.success == 1) {
                    swal.close();
                    window.location.href = "../local/storage/NHK-File/report/Monitoring/"+data.filename;  
                } 
            }
        });
    }

    function exports(id) {
        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });

        var datestart = $('#start_date'+id).val();
        var dateend = $('#end_date'+id).val();
        var tester = id;

        $.ajax({
            type: 'post',
            url: "{{ url('excel_monitoring') }}",
            data: 
            {
                'datestart' : datestart,
                'dateend' : dateend,
                'tester' : tester,
            },
            success: function (data) {
                if (data.success == 1) {
                    swal.close();
                    window.location.href = "../local/storage/NHK-File/report/Monitoring/"+data.filename;  
                } 
            }
        });
    }

    function datestartfs(id){
    
        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });

        var datestart = $('#datestart'+id).val();
        var dateend = $('#dateend'+id).val();
        var tester = id;

        $.ajax({
            type: 'post',
            url: "{{ url('table_monitor') }}",
            data: 
            {
                'datestart' : datestart,
                'dateend' : dateend,
                'tester' : tester,
            },
            success: function (data) {

                swal.close();
                console.log(parseInt(data));

                if(parseInt(data) == 1){
                    $('#result'+id).html('<button type="button" class="btn btn-danger btn-sm">&nbsp;&nbsp;NOT PASS&nbsp;&nbsp;</button>');
                }
                if(parseInt(data) == 0){
                    $('#result'+id).html('<button type="button" class="btn btn-success btn-sm">&nbsp;&nbsp;PASS&nbsp;&nbsp;</button>');
                }
                if(parseInt(data) == -1){
                    $('#result'+id).html('<p style="text-align:center"><i class="fa fa-minus fa-3x" style="color:dimgray;"></i> </p>');
                }


            }
        }); 

    }
    


    
            
</script>
@endsection
