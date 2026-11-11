
@extends('layouts.main')
@section('title', 'Summary phase')
@section('breadcrumb-title-href', 'queue')
@section('breadcrumb-title', 'Queuing')
@section('breadcrumb-sub', 'Summary phase')


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

        <!-- Modal Notification For Admin and operation --> 
    @if(!empty($notification))
        <div class="modal fade" id="ModalNotification" tabindex="-1" role="dialog" aria-labelledby="ModalNotificationTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalNotificationTitle" style=" color: #FCB454; ">Notify Summary</h5>
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
                                    <th class="text-center" scope="col">Summary Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($notification as $item)
                                <tr style="background: #FCB454;">
                                    <td scope="row">{{ $item['tester'] }}</td>
                                    <td>{{ $item['jobno'] }}</td>
                                    <td>{{ $item['title'] }}</td>
                                    <td>{{ $item['fifo'] }}</td>
                                    <td>{{ $item['summarytime'] }}</td>
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
  
    <!-- Modal save complete -->
    <div class="modal fade" id="ModalSuccess" tabindex="-1" role="dialog" aria-labelledby="ModalSuccess" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalSuccess">Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-complete" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id" name="edit_id" />
                    <div class="mb-3">
                        <label for="summary_date" class="col-form-label">Summary Date:</label>
                        <input type="datetime-local" class="form-control" id="summary_date" name="summary_date">
                    </div> 

                    <div class="mb-3">
                        <label for="accept_date" class="col-form-label">Requester Time:</label>
                        <input type="datetime-local" class="form-control" id="date" name="date" />
                    </div> 

                    <div class="mb-3">
                        <label for="accept_date" class="col-form-label">Accept Time:</label>
                        <input type="datetime-local" class="form-control" id="accept_date" name="accept_date" />
                    </div> 

                    <div class="mb-3">
                        <label for="measure_date" class="col-form-label">Measurement Time:</label>
                        <input type="datetime-local" class="form-control" id="measure_date" name="measure_date" />
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

@endsection

@section('scripts')
    <script>

    $( document ).ready(function() {
        $('#ModalNotification').modal('show');
    });

    var userstatus = $('#userstatus').val();


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

                    if(data.summary_status == 2){
                        button += '<button type="button" class="btn btn-success btn-sm" onclick="getView('+data.id+')" data-toggle="modal" data-target="#ModalSuccess" title="Update Success" style="font-size:14px;"><i class="fa fa-flag" aria-hidden="true"></i> Record Summary</button></a>&nbsp;';
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

    $('#chk').on('click', function(e) {
        oTable.draw();
    });

    function getView(id)
    {

        $('#edit_id').val(id);

        $.ajax({
            type: 'POST',
            url: `{{ url('queue-view') }}`,
            dataType: 'json',
            data : { 
                id:id,
                "_token": "{{ csrf_token() }}",
            },
            success: function (result) {  

                $('#date').val(result.date); 
                $('#accept_date').val(result.accept_date); 
                $('#measure_date').val(result.measure_date); 

            }
        });        

    }

    function saveComplete()
    {
        $('#ModalSuccess').modal('hide');

        var id = $('#edit_id').val();
        var summary_date = $('#summary_date').val();
        
        if(id != "" && summary_date != ""){

            var forms = $('#fm-complete')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('queue-save-summary') }}",
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
