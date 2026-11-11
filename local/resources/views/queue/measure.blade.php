
@extends('layouts.main')
@section('title', 'Begin Measurement and Summary')
@section('breadcrumb-title-href', 'queue')
@section('breadcrumb-title', 'Queuing')
@section('breadcrumb-sub', 'Begin Measurement and Summary')


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-12 col-xl-2"></div>
                        <div class="col-sm-12 col-xl-4">
                            <label>Job detail</label>
                            <input type="text" name="description" id="description" class="form-control">
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
                                <label>Summary date</label>
                                <input type="date" name="accept_summarytime" id="accept_summarytime" class="form-control">
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

                                                <!-- Requester -->
                                                @if(Auth::user()->status == 5)
                                                    <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">#</th>
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

  </div>

    <!-- Modal Measurement -->
    <div class="modal fade" id="exampleModalSemple" tabindex="-1" role="dialog" aria-labelledby="exampleModalSempleTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalSempleTitle">Record Measurement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-confirm" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id" name="edit_id" />
                    <div class="mb-3">
                        <label for="measurement_date" class="col-form-label">Date:</label>
                        <input type="datetime-local" class="form-control" id="measurement_date" name="measurement_date">
                    </div> 
                    <div class="mb-3">
                        <label for="measure_link" class="col-form-label">Link:</label>
                        <input type="text" class="form-control" id="measure_link" name="measure_link">
                    </div>
                    <div class="mb-3">
                        <label for="measurement_operator" class="col-form-label">Operator:</label>
                        <input type="text" class="form-control" id="measurement_operator" name="measurement_operator">
                    </div>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="confirmmeasure()">Save</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Notification For Admin and operation --> 
    @if(Auth::user()->status == 1 || Auth::user()->status == 5)
        @if(!empty($notificationmeasure))
        <div class="modal fade" id="ModalNotification" tabindex="-1" role="dialog" aria-labelledby="ModalNotificationTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalNotificationTitle" style=" color: #FCB454; ">Notify Measurement and Summary</h5>
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
                                    <th class="text-center" scope="col">Work Measurement Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notificationmeasure as $item)
                                <tr style="background: #FCB454;">
                                    <td scope="row">{{ $item['tester'] }}</td>
                                    <td>{{ $item['jobno'] }}</td>
                                    <td>{{ $item['title'] }}</td>
                                    <td>{{ $item['fifo'] }}</td>
                                    <td>{{ $item['measurement'] }}</td>
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

    <!-- Modal measure complete -->
    <div class="modal fade" id="ModalSuccess" tabindex="-1" role="dialog" aria-labelledby="ModalSuccess" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalSuccess">Updated: Measurement Completed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-complete" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id3" name="edit_id3" />
                    <div class="mb-3">
                        <label for="measure_complete" class="col-form-label">Date:</label>
                        <input type="datetime-local" class="form-control" id="measure_complete" name="measure_complete">
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveComplete()">Save</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Show data -->
    <div class="modal fade" id="Viewdata" tabindex="-1" role="dialog" aria-labelledby="Viewdata" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleViewdata">View:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_01" class="col-sm-4 col-form-label"><b> Job no : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_01" />
                    </div>
                </div>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_02" class="col-sm-4 col-form-label"><b> Job detail : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_02" />
                    </div>
                </div>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_03" class="col-sm-4 col-form-label"><b> Machine : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_03" />
                    </div>
                </div>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_04" class="col-sm-4 col-form-label"><b> Requester : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_04" />
                    </div>
                </div>
                <hr>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_05" class="col-sm-4 col-form-label"><b> Urgent Job Status : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_05" />
                    </div>
                </div>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_06" class="col-sm-4 col-form-label"><b> Work Measurement Time : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_06" />
                    </div>
                </div>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_07" class="col-sm-4 col-form-label"><b> Summary Time : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_07" />
                    </div>
                </div>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_08" class="col-sm-4 col-form-label"><b> Operator : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_08" />
                    </div>
                </div>
                <hr>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_09" class="col-sm-4 col-form-label"><b> Receiving Sample Date : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_09" />
                    </div>
                </div>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_10" class="col-sm-4 col-form-label"><b> Operator : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_10" />
                    </div>
                </div>
                <hr>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_11" class="col-sm-4 col-form-label"><b> Recored Measurement Date : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_11" />
                    </div>
                </div>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_12" class="col-sm-4 col-form-label"><b> Operator : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_12" />
                    </div>
                </div>
                <hr>
                <div class="form-group row" style=" margin-bottom: 0px;">
                    <label for="view_13" class="col-sm-4 col-form-label"><b> Updated Measurement Completed Date : </b></label>
                    <div class="col-sm-8">
                        <input type="text" readonly class="form-control-plaintext" id="view_13" />
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

                    if(data.measure_status == 1){
                        if(chkmeasure == 0){

                            // เริ่มกระบวนการวัด และ Summary
                            button += '<button type="button" class="btn btn-warning btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#exampleModalSemple" title="Save Measure and Summary" style="font-size:14px;"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Record Measurement</button></a>&nbsp;';
                        }
                    }else if(data.measure_status == 2){
                        
                        // อัปเดตสถานะงานเป็น "เสร็จสิ้น"
                            button += '<button type="button" class="btn btn-success btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#ModalSuccess" title="Update Success" style="font-size:14px;"><i class="fa fa-flag" aria-hidden="true"></i> Compate Measurement</button></a>&nbsp;';
                    }

                }else if(data.status == 3){
                    notice += '<span class="label label-success">Complete</span>';

                }else{
                    notice += '<span class="label label-danger">Cancel</span>';
                }

                button += '<a href="queue-view/'+data.id+'"><button type="button" class="btn btn-info btn-sm" title="View data" style="font-size:14px;"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>&nbsp;';

                $('td:eq(0)', row).html(button);
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
                var chkmeasure = data['chkmeasure'];

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

                    if(data.measure_status == 1){
                        if(chkmeasure == 0){

                            // เริ่มกระบวนการวัด และ Summary
                            button += '<button type="button" class="btn btn-warning btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#exampleModalSemple" title="Save Measure and Summary" style="font-size:14px;"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Record Measurement</button></a>&nbsp;';
                        }

                    }else if(data.measure_status == 2){
                        
                        // อัปเดตสถานะงานเป็น "เสร็จสิ้น"
                            button += '<button type="button" class="btn btn-success btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#ModalSuccess" title="Update Success" style="font-size:14px;"><i class="fa fa-flag" aria-hidden="true"></i> Compate Measurement</button></a>&nbsp;';
                    }

                }else if(data.status == 3){
                    notice += '<span class="label label-success">Complete</span>';

                }else{

                    notice += '<span class="label label-danger">Cancel</span>';

                }

                button += '<a href="queue-view/'+data.id+'"><button type="button" class="btn btn-info btn-sm" title="View data" style="font-size:14px;"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>&nbsp;';


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
        
        $('#edit_id').val("");
        $('#edit_id3').val("");

        $('#edit_id').val(id);
        $('#edit_id3').val(id);
    }

    function getView(id)
    {

                $("#Viewdata").trigger("reset");

                $.ajax({
                    type: 'POST',
                    url: `{{ url('queue-view') }}`,
                    dataType: 'json',
                    data : { 
                        id:id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function (result) {  

                        $('#view_01').val(result.jobno); 
                        $('#view_02').val(result.title); 
                        $('#view_03').val(result.tester); 
                        $('#view_04').val(result.requester); 

                        var tag = '';
                        if(result.tag == 1){
                            tag = "Yes";
                        }else{
                            tag = "No";
                        }

                        $('#view_05').val(tag); 
                        $('#view_06').val(result.accept_measurementtime); 
                        $('#view_07').val(result.accept_summarytime); 
                        $('#view_08').val(result.accept_operator); 

                        $('#view_09').val(result.sample_date); 
                        $('#view_10').val(result.sample_operator); 

                        $('#view_11').val(result.measure_date); 
                        $('#view_12').val(result.measure_operator); 

                        $('#view_13').val(result.measure_complete); 


                    }
                });        

    }

    
    function confirmmeasure()
    {
        $('#exampleModalSemple').modal('hide');

        var id = $('#edit_id').val();
        var date = $('#measurement_date').val();
        var operator = $('#measurement_operator').val();
        
        if(id != "" && date != "" && operator != ""){

            var forms = $('#fm-confirm')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('queue-save-measure') }}",
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

    function saveComplete()
    {
        $('#ModalSuccess').modal('hide');

        var id = $('#edit_id3').val();
        var complete_date = $('#measure_complete').val();
        
        if(id != "" && complete_date != ""){

            var forms = $('#fm-complete')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('queue-save-complete') }}",
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
