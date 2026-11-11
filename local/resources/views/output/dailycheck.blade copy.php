@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Daily check update')
@section('breadcrumb-title', 'Daily check update')

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
                        @if(Auth::user()->status == 1)
                        <button type="button" id="deleteinfo" class="btn btn-danger btn-sm">Delete</button>
                        @endif
                    </div>
                    <br>
                    @if(Auth::user()->status == 2)
                        <table valign="bottom" id="dt" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" name="checkAll" id="checkAll"></th>
                                    <th class="text-center">Setting</th>
                                    <th class="text-center">Tester</th>
                                    <th class="text-center">Due date</th>
                                    <th class="text-center">Record time</th>
                                    <th class="text-center">Operator</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Result</th>
                                    <th class="text-center">Problem</th>
                                    <th class="text-center">Root cause</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    @else
                        <table valign="bottom" id="dt" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Setting</th>
                                    <th class="text-center">Tester</th>
                                    <th class="text-center">Due date</th>
                                    <th class="text-center">Record time</th>
                                    <th class="text-center">Operator</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Result</th>
                                    <th class="text-center">Problem</th>
                                    <th class="text-center">Root cause</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>

@endsection
@section('scripts')
<script>

    $(document).ready(function() {

        @if(Auth::user()->status == 2)
            var oTable = $('#dt').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                lengthChange: true,
                ajax: {
                    url: "{{ url('dailychecktable') }}",
                    "data": function (d) {
                        d.date_start = $("#start_date").val();
                        d.date_end = $("#end_date").val();
                    }
                },
                columns: [
                    {'className': "text-center", data: 'id_dailyinfo', name: 'id_dailyinfo'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'name_tester', name: 'name_tester'},
                    {'className': "text-center", data: 'due_date', name: 'due_date'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                ],
                // "order": [[ 1, "desc" ]],
                rowCallback: function (row, data, index) {
                    var button = '';

                    var problem = [];
                    var rootcause = [];
                    var action = [];

                    $('td:eq(0)', row).html('<input type="checkbox" name="checkbox[]" value="'+data.id_dailyinfo+'">');
                    $('td:eq(4)', row).html('-');
                    $('td:eq(5)', row).html('-');
                    $('td:eq(6)', row).html('-');
                    $('td:eq(7)', row).html('-');
                    $('td:eq(8)', row).html('-');
                    $('td:eq(9)', row).html('-');
                    $('td:eq(10)', row).html('-');


                    @if(Auth::user()->status != 2)
                        button += '<a href="../dailycheckedit/'+data.id_dailyinfo+'"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    @else
                        button += '<a href="../dailycheckview/'+data.id_dailyinfo+'"><button class="btn btn-info btn-sm"><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                    @endif
                    $('td:eq(1)', row).html(button);


                    // Check status Column
                    var time_due_date = new Date(data.due_date);
                    var time_recoredtime = new Date(data.recoredtime);
                    var options = {hour12:false}

                    var check_due_date = time_due_date.toLocaleTimeString('th-TH', options);
                    var check_time_recoredtime = time_recoredtime.toLocaleTimeString('th-TH', options);

                    if(data.recoredtime == null){
                        $('td:eq(6)', row).html('<button class="btn btn-warning btn-sm">On Going</button>');
                    }else{

                        if(data.check_time_recoredtime > data.check_due_date){ // ส่งล่าช้า
                            $('td:eq(6)', row).html('<button class="btn btn-primary btn-sm">DONE<br>(Delay)</button>');
                        }else{
                            $('td:eq(6)', row).html('<button class="btn btn-success btn-sm">DONE</button>');
                        }
                    }


                    // Check Result Column
                    if(data.recoredtime != null){
                        if(data.results == "PASS"){
                            $('td:eq(7)', row).html('<button class="btn btn-success btn-sm">PASS</button>');
                        }else{

                            $('td:eq(7)', row).html('<button class="btn btn-danger btn-sm">NOT PASS</button>');
                        }
                    }

                    // Check recoredtime Column
                    if(data.recoredtime != null){
                        $('td:eq(4)', row).html(data.recoredtime);
                    }

                    // Check Operation Column
                    if(data.operation != null){
                        $('td:eq(5)', row).html(data.operation);
                    }

                    if(data.recoredtime != null){

                        // Check problem Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            problem.push(data.problem);
                        }
                        if(data.results != "PASS"){
                            problem.push(data.problem_result);
                        }

                        // Check Root cause Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            rootcause.push(data.rootcause);
                        }
                        if(data.results != "PASS"){
                            rootcause.push(data.rootcause_result);
                        }

                        // Check action Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            action.push(data.action);
                        }
                        if(data.results != "PASS"){
                            action.push(data.action_result);
                        }

                        $('td:eq(8)', row).html( problem.join(' | ') );
                        $('td:eq(9)', row).html( rootcause.join(' | ') );
                        $('td:eq(10)', row).html( action.join(' | ') );

                    }
                }
            }); 
        @else
            var oTable = $('#dt').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                lengthChange: true,
                ajax: {
                    url: "{{ url('dailychecktable') }}",
                    "data": function (d) {
                        d.date_start = $("#start_date").val();
                        d.date_end = $("#end_date").val();
                    }
                },
                columns: [
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'name_tester', name: 'name_tester'},
                    {'className': "text-center", data: 'info_due_date', name: 'due_date'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                    {'className': "text-center", data: 'id_daily', name: 'id_daily'},
                ],
                // "order": [[ 0, "desc" ]],
                rowCallback: function (row, data, index) {
                    var button = '';

                    var problem = [];
                    var rootcause = [];
                    var action = [];

                    $('td:eq(3)', row).html('-');
                    $('td:eq(4)', row).html('-');
                    $('td:eq(5)', row).html('-');
                    $('td:eq(6)', row).html('-');
                    $('td:eq(7)', row).html('-');
                    $('td:eq(8)', row).html('-');
                    $('td:eq(9)', row).html('-');

                    button += '<a href="../dailycheckedit/'+data.id_dailyinfo+'"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    //button += '<a href="../dailycheckview/'+data.id_dailyinfo+'"><button class="btn btn-info btn-sm"><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                    
                    $('td:eq(0)', row).html(button);


                    // Check status Column
                    var time_due_date = new Date(data.due_date);
                    var time_recoredtime = new Date(data.recoredtime);
                    var options = {hour12:false}

                    var check_due_date = time_due_date.toLocaleTimeString('th-TH', options);
                    var check_time_recoredtime = time_recoredtime.toLocaleTimeString('th-TH', options);

                    if(data.recoredtime == null){
                        $('td:eq(5)', row).html('<button class="btn btn-warning btn-sm">On Going</button>');
                    }else{

                        if(data.check_time_recoredtime > data.check_due_date){ // ส่งล่าช้า
                            $('td:eq(5)', row).html('<button class="btn btn-primary btn-sm">DONE<br>(Delay)</button>');
                        }else{
                            $('td:eq(5)', row).html('<button class="btn btn-success btn-sm">DONE</button>');
                        }
                    }

                    // Check Result Column
                    if(data.recoredtime != null){
                        if(data.results == "PASS"){
                            $('td:eq(6)', row).html('<button class="btn btn-success btn-sm">PASS</button>');
                        }else{

                            $('td:eq(6)', row).html('<button class="btn btn-danger btn-sm">NOT PASS</button>');
                        }
                    }

                    // Check recoredtime Column
                    if(data.recoredtime != null){
                        $('td:eq(3)', row).html(data.recoredtime);
                    }

                    // Check Operation Column
                    if(data.operation != null){
                        $('td:eq(4)', row).html(data.operation);
                    }

                    if(data.recoredtime != null){

                        // Check problem Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            problem.push(data.problem);
                        }
                        if(data.results != "PASS"){
                            problem.push(data.problem_result);
                        }

                        // Check Root cause Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            rootcause.push(data.rootcause);
                        }
                        if(data.results != "PASS"){
                            rootcause.push(data.rootcause_result);
                        }

                        // Check action Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            action.push(data.action);
                        }
                        if(data.results != "PASS"){
                            action.push(data.action_result);
                        }

                        $('td:eq(7)', row).html( problem.join(' | ') );
                        $('td:eq(8)', row).html( rootcause.join(' | ') );
                        $('td:eq(9)', row).html( action.join(' | ') );

                    }
                }
            }); 
        @endif
        $('.btnSearch').click(function(){
            oTable.ajax.reload();
        });

        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });


    
    $('#deleteinfo').click(function () {

        var idv = [];
        $('input:checked').each(function() {
           idv.push(this.value); 
        });

        try {
          
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
                    url: "{{ url('resetDailycheck') }}",
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        "id": idv,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function(data) {

                        if (data.success == 1) {
                            swal("Successed", "reset data successed", "success");
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {

                        }

                    }
                });
            });

        } catch (e) {
          // จัดการกับข้อผิดพลาด
          console.log(e.message);
        }

    });

</script>
@endsection
