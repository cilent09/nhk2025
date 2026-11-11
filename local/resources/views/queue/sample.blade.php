
@extends('layouts.main')
@section('title', 'Queuing')
@section('breadcrumb-title', 'Queuing') 


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    @if(Auth::user()->status == 1 || Auth::user()->status == 4)
                    <a href="{{ url('queue/add') }}"><button class="btn btn-primary"><i class="fa fa-plus"></i> Create Queuing</button></a> 
                    @endif
                </div>

                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-12 col-xl-2"></div>
                        <div class="col-sm-12 col-xl-4">
                            <label>Job detail</label>
                            <input type="text" name="title" id="title" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <label>Requester</label>
                            <input type="text" name="requester" id="requester" class="form-control">
                        </div>
                    </div>
                    <br>
                        <div class="row">
                            <div class="col-sm-12 col-xl-2"></div>
                            <div class="col-sm-12 col-xl-4">
                                <label>Due date</label>
                                <input type="date" name="duedate" id="duedate" class="form-control">
                            </div>
                            <div class="col-sm-12 col-xl-4">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">--select status--</option>
                                    <option value="1">Not start</option>
                                    <option value="2">Running</option>
                                    <option value="3">Complete</option>
                                    <option value="4">Cancle</option>
                                </select>
                            </div>
                            <div class="col-sm-12 col-xl-2">
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
                <div class="card-block p-0"> 
                    <!-- Tab panes -->
                    <div class="tab-content card-block" > 
                        <div class="tab-pane active" id="home3" role="tabpanel"> 
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-12">
                                            <form class="custom-validation" method="post" id="my_form" >
                                            {{ csrf_field() }}

                                                @if(Auth::user()->status == 5)
                                                    <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Machine</th>
                                                                <th class="text-center">Job no.</th>
                                                                <th class="text-center">Job Detail</th> 
                                                                <th class="text-center">Finish date</th>
                                                                <th class="text-center">Requester</th>
                                                                <th class="text-center">Measurement Status</th>
                                                                <th class="text-center">Summary Status</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                @elseif(Auth::user()->status == 1)
                                                    <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
                                                                <th class="text-center">Machine</th>
                                                                <th class="text-center">Job no.</th>
                                                                <th class="text-center">Job Detail</th> 
                                                                <th class="text-center">Finish date</th>
                                                                <th class="text-center">Requester</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Measurement Status</th>
                                                                <th class="text-center">Summary Status</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                @endif

                                            </form>
                                        </div>
                                    </div>
                                </div> 
                        </div> 
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <input type="hidden" id="userstatus" value="{{Auth::user()->status}}">
 
    <!-- Modal Semple -->
    <div class="modal fade" id="exampleModalSemple" tabindex="-1" role="dialog" aria-labelledby="exampleModalSempleTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalSempleTitle">Confirm Receiving the Sample</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-semple" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id2" name="edit_id2" />
                    <div class="mb-3">
                        <label for="sample_date" class="col-form-label">Date:</label>
                        <input type="datetime-local" class="form-control" id="sample_date" name="sample_date">
                    </div> 
                    <div class="mb-3">
                        <label for="sample_operator" class="col-form-label">Operator:</label>
                        <input type="text" class="form-control" id="sample_operator" name="sample_operator">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="confirmSemple()">Save</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Notification For Admin and operation --> 
    @if(Auth::user()->status == 1 || Auth::user()->status == 5)
        @if(count($notificationsample) > 0)
        <div class="modal fade" id="ModalNotification" tabindex="-1" role="dialog" aria-labelledby="ModalNotificationTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalNotificationTitle" style=" color: red; ">Job not submitted on time</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-body">
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th class="text-center" scope="col">Machine</th>
                                    <th class="text-center" scope="col">Job no</th>
                                    <th class="text-center" scope="col">Job Detail</th>
                                    <th class="text-center" scope="col">Fifo</th>
                                    <th class="text-center" scope="col">Specify Sample Delivery Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notificationsample as $item)
                                <tr style="background: #F16767;">
                                    <td scope="row">{{ $item['tester'] }}</td>
                                    <td>{{ $item['jobno'] }}</td>
                                    <td>{{ $item['title'] }}</td>
                                    <td>{{ $item['fifo'] }}</td>
                                    <td>{{ $item['limit'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

    <!-- Modal Edit FIFO -->
    <div class="modal fade" id="editfifo" tabindex="-1" role="dialog" aria-labelledby="editfifoTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editfifoTitle">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-edit-semple" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id3" name="edit_id3" />
                    <div class="mb-3">
                        <label for="edit_deadline" class="col-form-label">Job Deadline Postponed:</label>
                        <input type="date" class="form-control" id="edit_deadline" name="edit_deadline">
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveeditsample()">Save</button>
            </div>
            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>

    $( document ).ready(function() {
        $('#ModalNotification').modal('show');
    });

    var userstatus = $('#userstatus').val();

    if(userstatus == 5){
        var oTable = $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            responsive: true,
            lengthChange: false,
            ajax: {
                "url": "{{ url('datatablesqueuesample') }}",
                "type": "POST",
                "data": function (d) {
                    d.title = $("#title").val();
                    d.requester = $("#requester").val();
                    d.duedate = $("#duedate").val();
                    d.status = $("#status").val();
                }
            },
            columns: [
                {
                    'className': "text-center",
                    data: 'machine',
                    name: 'machine'
                },
                {
                    'className': "text-center",
                    data: 'jobno',
                    name: 'jobno'
                },
                {
                    'className': "text-center",
                    data: 'title',
                    name: 'title'
                },
                {
                    'className': "text-center",
                    data: 'duedate',
                    name: 'duedate'
                },
                {
                    'className': "text-center",
                    data: 'requester',
                    name: 'requester'
                },
                {
                    'className': "text-center",
                    data: 'measure_status',
                    name: 'MeasurementStatus'
                },
                {
                    'className': "text-center",
                    data: 'summary_status',
                    name: 'SummaryStatus'
                },
                
            ],
            rowCallback: function (row, data, index) {

                if(data.status == 1){
                    notice += '<span class="label label-primary">Not start</span>';

                }else if(data.status == 2){ 
                    notice += '<span class="label label-warning">Running</span>';

                     if(data.measure_status == 0){

                        // พนักงานหน้างานยืนยันรับ Sample => Admin
                        button += '<button type="button" class="btn btn-success btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#exampleModalSemple" title="Receiving Sample" style="font-size:14px;"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Receiving Sample</button></a>&nbsp;';

                        if(btnedit == 1){
                            // Edit fifo
                            button += '<button type="button" class="btn btn-warning btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#editfifo" title="Edit" style="font-size:14px;"><i class="fa fa-pencil" aria-hidden="true"></i> FIFO</button></a>&nbsp;';
                        }

                    }

                }else if(data.status == 3){
                    notice += '<span class="label label-success">Complete</span>';

                }else{
                    notice += '<span class="label label-danger">Cancel</span>';
                }
                $('td:eq(5)', row).html(notice);

            }
        });
    }else if(userstatus == 1){
        var oTable = $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            responsive: true,
            lengthChange: false,
            ajax: {
                "url": "{{ url('datatablesqueuesample') }}",
                "type": "POST",
                "data": function (d) {
                    d.title = $("#title").val();
                    d.requester = $("#requester").val();
                    d.duedate = $("#duedate").val();
                    d.status = $("#status").val();
                }
            },
            columns: [
                {
                    'className': "text-center",
                    data: 'id',
                    name: 'id'
                },
                {
                    'className': "text-center",
                    data: 'machine',
                    name: 'machine'
                },
                {
                    'className': "text-center",
                    data: 'jobno',
                    name: 'jobno'
                },
                {
                    'className': "text-center",
                    data: 'title',
                    name: 'title'
                },
                {
                    'className': "text-center",
                    data: 'duedate',
                    name: 'duedate'
                },
                {
                    'className': "text-center",
                    data: 'requester',
                    name: 'requester'
                },
                {
                    'className': "text-center",
                    data: 'status',
                    name: 'status'
                },
                {
                    'className': "text-center",
                    data: 'measure_status',
                    name: 'MeasurementStatus'
                },
                {
                    'className': "text-center",
                    data: 'summary_status',
                    name: 'SummaryStatus'
                },
                
            ],
            rowCallback: function (row, data, index) {

                var button = '';
                var summarystatus = '';
                var measurestatus = '';
                var notice = '';
                               
                var btnedit = data['btnedit'];
                var chksample = data['chksample'];

                if(data.measure_status == 1){
                    measurestatus += '<span class="label label-primary">Not start</span>';

                }else if(data.measure_status == 2){
                    measurestatus += '<span class="label label-warning">Running</span>';

                }else if(data.measure_status == 3){
                    measurestatus += '<span class="label label-success">Complete</span>';

                }else if(data.measure_status == 4){
                    measurestatus += '<span class="label label-danger">Cancel</span>';

                }else{
                    measurestatus += '<span class="label label-primary">Not start</span>';
                }

                if(data.summary_status == 1){
                    summarystatus += '<span class="label label-primary">Not start</span>';

                }else if(data.summary_status == 2){
                    summarystatus += '<span class="label label-warning">Running</span>';

                }else if(data.summary_status == 3){
                    summarystatus += '<span class="label label-success">Complete</span>';

                }else if(data.summary_status == 4){
                    summarystatus += '<span class="label label-danger">Cancel</span>';

                }else{
                    summarystatus += '<span class="label label-primary">Not start</span>';
                }

                if(data.status == 1){
                    notice += '<span class="label label-primary">Not start</span>';

                }else if(data.status == 2){
                    notice += '<span class="label label-warning">Running</span>';

                    if(data.measure_status == 0){

                        // พนักงานหน้างานยืนยันรับ Sample => Admin
                        button += '<button type="button" class="btn btn-success btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#exampleModalSemple" title="Receiving Sample" style="font-size:14px;"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Receiving Sample</button></a>&nbsp;';

                        if(btnedit == 1){
                            // Edit fifo
                            button += '<button type="button" class="btn btn-warning btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#editfifo" title="Edit" style="font-size:14px;"><i class="fa fa-pencil" aria-hidden="true"></i> FIFO</button></a>&nbsp;';
                        }

                    }


                }else if(data.status == 3){
                    notice += '<span class="label label-success">Complete</span>';

                }else{

                    notice += '<span class="label label-danger">Cancel</span>';

                }

                $('td:eq(0)', row).html(button);
                $('td:eq(6)', row).html(notice);
                $('td:eq(7)', row).html(measurestatus);
                $('td:eq(8)', row).html(summarystatus);
            }
        });
    }

    $('#chk').on('click', function(e) {
        oTable.draw();
    });
 


    function getSample(id)
    {
        $('#edit_id2').val(id);
        $('#edit_id3').val(id);
    }


    function confirmSemple()
    {
        $('#exampleModalSemple').modal('hide');

        var id = $('#edit_id2').val();
        var sample_date = $('#sample_date').val();
        var sample_operator = $('#sample_operator').val();
        
        if(id != "" && sample_date != "" && sample_operator != ""){

            var forms = $('#fm-semple')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('sempleQueue') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        swal("warning", data.error , "error");
                    }
                }
            });

        }else{
            swal("warning", "Please complete all required fields.", "warning");
        }

    }

    
    function saveeditsample()
    {
        $('#editfifo').modal('hide');

        var id = $('#edit_id3').val();
        var edit_deadline = $('#edit_deadline').val();
        
        if(id != "" && edit_deadline != ""){

            var forms = $('#fm-edit-semple')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('queue_edit_deadline') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        swal("warning", data.error , "error");
                    }
                }
            });

        }else{
            swal("warning", "Please complete all required fields.", "warning");
        }

    }

    </script>
@endsection
