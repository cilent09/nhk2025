
@extends('layouts.main')
@section('title', 'Queuing')
@section('breadcrumb-title', 'Queuing') 
<style>
    .modal-xl {
        max-width: 95% !important;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-block">
                    <div class="row">
                        <div class="col-sm-12 col-xl-2"></div>
                        <div class="col-sm-12 col-xl-4">
                            <label>Job name</label>
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
                                <label>Tester</label>
                                <select name="tester" id="tester" class="form-control">
                                    <option value="">--select status--</option>
                                    <option value="SEM/EDX Tester">SEM/EDX Tester</option>
                                    <option value="LPC tester">LPC Tester</option>
                                    <option value="Smoke Tester">Smoke Tester</option> 
                                    <option value="FT-IR Tester">FT-IR Tester</option>
                                    <option value="GC-MS Tester">GC-MS Tester</option>
                                    <option value="IC Tester">IC Tester</option>
                                    <option value="DSC Tester">DSC Tester</option>
                                    <option value="Weight Tester">Weight Tester</option>
                                    <option value="Fallen Dust Tester">Fallen Dust Tester</option>
                                </select>
                                <span class="text-danger">กรุณาระบุ Tester ก่อนทำการค้นหาทุกครั้ง</span>
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
                                                            <th class="text-center">Active button</th>
                                                            <th class="text-center">Tester</th>
                                                            <th class="text-center">Job no.</th>
                                                            <th class="text-center">Job name</th> 
                                                            <th class="text-center">Due date</th>
                                                            <th class="text-center">Requester</th>
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

    <!-- Modal Acceptance -->
    <div class="modal fade" id="AcceptanceModal" tabindex="-1" role="dialog" aria-labelledby="AcceptanceModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Estimate date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form  method="post" id="fm-edit" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id" name="edit_id" />

                    <div class="mb-3">
                        <label for="edit_tag" class="col-form-label">Urgent Job Status:</label>
                        <select class="form-control" id="edit_tag" name="edit_tag">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <!-- datetime-local -->
                        <label for="measurementtime" class="col-form-label">Measurement:</label>
                        <input type="date" class="form-control" id="measurementtime" name="measurementtime">
                    </div>
                    <div class="mb-3">
                        <label for="summarytime" class="col-form-label">Submit:</label>
                        <input type="date" class="form-control" id="summarytime" name="summarytime">
                    </div>
                    <div class="mb-3">
                        <label for="operator" class="col-form-label">Operator:</label>
                        <input type="text" class="form-control" id="operator" name="operator">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger" onclick="cancelList()">Cancle</button>
                <button type="button" class="btn btn-primary" onclick="confirmList()">Confirm</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Semple -->
    <div class="modal fade" id="exampleModalSemple" tabindex="-1" role="dialog" aria-labelledby="exampleModalSempleTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalSempleTitle">Actual receive sample date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-semple" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id2" name="edit_id2" />
                    <div class="mb-3">
                        <label for="sample_date" class="col-form-label">Receive:</label>
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
                    <input type="hidden" id="edit_id4" name="edit_id4" />
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

    <!-- Modal Measurement -->
    <div class="modal fade" id="exampleMeasurement" tabindex="-1" role="dialog" aria-labelledby="exampleMeasurementTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleMeasurementTitle">Actual measurement date</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-confirm" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id5" name="edit_id5" />
                    <div class="mb-3">
                        <label for="measurement_date" class="col-form-label">Start measurement:</label>
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

    <!-- Modal measure complete -->
    <div class="modal fade" id="ModalSuccessMeasurement" tabindex="-1" role="dialog" aria-labelledby="ModalSuccessMeasurement" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalSuccessMeasurement">Measurement Completed</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-complete" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id6" name="edit_id6" />
                    <div class="mb-3">
                        <label for="measure_complete" class="col-form-label">Completed:</label>
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

    <!-- Modal save Record Summary -->
    <div class="modal fade" id="ModalSuccessSummary" tabindex="-1" role="dialog" aria-labelledby="ModalSuccessSummary" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalSuccessSummary">Actual summary date </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                <form  method="post" id="fm-summary" >
                {{ csrf_field() }}
                    <input type="hidden" id="edit_id7" name="edit_id7" />
                    
                    <div class="mb-3">
                        <label for="date" class="col-form-label">Requester Time:</label>
                        <input type="text" class="form-control" id="date" readonly>
                    </div> 
                    <div class="mb-3">
                        <label for="accept_date" class="col-form-label">Acceptance:</label>
                        <input type="text" class="form-control" id="accept_date" readonly>
                    </div> 

                    <div class="mb-3">
                        <label for="measure_date" class="col-form-label">Measurement:</label>
                        <input type="text" class="form-control" id="measure_date" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="summary_date" class="col-form-label">Submit:</label>
                        <input type="datetime-local" class="form-control" id="summary_date" name="summary_date" />
                    </div> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveSummary()">Save</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Notification For QA --> 
    @if(Auth::user()->status == 2)
        @if(count($queuelist) > 0) 
        <div class="modal fade" id="ModalNotification" tabindex="-1" role="dialog" aria-labelledby="exampleModalXLLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalNotificationTitle"><i class="ti-bell mr-2"></i> Please start the measurement process and complete the summary</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> 
                    <div class="modal-body"  style="overflow-x: auto;">
                        <table class="table table-bordered" style="width: 100%;text-align: center;">
                            <thead>
                                <tr>
                                    <th scope="col" style=" text-align: center; ">Machine</th>
                                    <th style="text-align: center; ">Job no</th>
                                    <th style="text-align: center; ">Model</th>
                                    <th style="text-align: center; ">Job detail</th>
                                    <th style="text-align: center; ">Due date Measurement</th>
                                    <th style="text-align: center; ">Due date Summary</th>
                                    <th style="text-align: center; ">Requester</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($queuelist as $item)
                                <tr>
                                    <td scope="row">{{ $item['machine'] }}</td>
                                    <td>{{ $item['jobno'] }}</td>
                                    <td>{{ $item['model'] }}</td>
                                    <td>{{ $item['title'] }}</td>

                                    @if($item['st'] == 1)
                                        <td style="background-color: #F38181;">{{ date('d/m/Y', strtotime($item['measurementtime'])) }}</td>
                                        <td>{{ (!empty($item['summarytime']))? date('d/m/Y', strtotime($item['summarytime'])) : '-' }}</td>
                                    @elseif($item['st'] == 2)
                                        <td>{{ date('d/m/Y', strtotime($item['measurementtime'])) }}</td>
                                        <td style="background-color: #F38181;">{{ (!empty($item['summarytime']))? date('d/m/Y', strtotime($item['summarytime'])) : '-' }}</td>
                                    @else
                                        <td style="background-color: #F38181;">{{ date('d/m/Y', strtotime($item['measurementtime'])) }}</td>
                                        <td style="background-color: #F38181;">{{ date('d/m/Y', strtotime($item['summarytime'])) }}</td>
                                    @endif

                                    <td>{{ $item['requester'] }}</td>
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

@endsection

@section('scripts')
    <script>

    var userstatus = $('#userstatus').val();

    var oTable = $('#datatables').DataTable({
        processing: true,
        serverSide: true,
        searching: false,
        responsive: true,
        lengthChange: false,
        ordering: false,
        ajax: {
            "url": "{{ url('datatablesqueue') }}",
            "type": "POST",
            "data": function (d) {
                d.title = $("#title").val();
                d.requester = $("#requester").val();
                d.duedate = $("#duedate").val();
                d.status = $("#status").val();
                d.tester = $("#tester").val();
            }
        },
        order: [[2, 'asc']],
        columns: [
            {
                'className': "text-center",
                data: 'id_tester',
                name: 'id_tester'
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
                data: 'measurement',
                name: 'measurement'
            },
             {
                'className': "text-center",
                data: 'summary',
                name: 'summary'
            },

        ],
        rowCallback: function (row, data, index) {

            var button = '';
            var mst = '';
            var say = '';


            if(data.status == 0){

                // button += '<a href="queue-edit/'+data.id+'"><button type="button" class="btn btn-warning btn-sm" title="View data" style="font-size:14px;"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</button></a>&nbsp;';

                // button += '<button type="button" class="btn btn-success btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#AcceptanceModal" title="Acceptance" style="font-size:14px;"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Acceptance</button></a>&nbsp;';

            }else if(data.status == 1){ 
                if(data.measure_status == 0){ 

                    // พนักงานหน้างานยืนยันรับ Sample => Admin
                    button += '<button type="button" class="btn btn-success btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#exampleModalSemple" title="Receiving Sample" style="font-size:14px;"><i class="fa fa-hand-paper-o" aria-hidden="true"></i>  Actual receive sample date</button></a>&nbsp;';
                }
            }

            if(data.measure_status == 1){ 

                // // Edit fifo
                // button += '<button type="button" class="btn btn-warning btn-sm" onclick="getSample('+data.id+')" data-toggle="modal" data-target="#editfifo" title="Edit" style="font-size:14px;"><i class="fa fa-pencil" aria-hidden="true"></i> EDIT QUEUE</button></a>&nbsp;';

                // // เริ่มกระบวนการวัด และ Summary
                button += '<button type="button" class="btn btn-success btn-sm" onclick="getSample('+data.id_tester+')" data-toggle="modal" data-target="#exampleMeasurement" title="Save Measure and Summary" style="font-size:14px;"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Record Measurement</button></a>&nbsp;';
                
            }else if(data.measure_status == 2){ 
                
                // อัปเดตสถานะงานเป็น "เสร็จสิ้น"
                button += '<button type="button" class="btn btn-success btn-sm" onclick="getSample('+data.id_tester+')" data-toggle="modal" data-target="#ModalSuccessMeasurement"" title="Update Success" style="font-size:14px;"><i class="fa fa-flag" aria-hidden="true"></i> Complete Measurement</button></a>&nbsp;';

            }else if(data.measure_status == 3){ 

                // if(data.summary_status != 3){ 
                //     button += '<button type="button" class="btn btn-success btn-sm" onclick="getSummary('+data.id_tester+')" data-toggle="modal" data-target="#ModalSuccessSummary" title="Update Success" style="font-size:14px;"><i class="fa fa-floppy-o" aria-hidden="true"></i> Record Summary</button></a>&nbsp;';
                // }

            }

            button += '<a href="queue-view/'+data.id+'"><button type="button" class="btn btn-info btn-sm" title="View data" style="font-size:14px;"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>&nbsp;';

             
            if(data['measurement'] == 'running'){
                mst += '<span class="label label-warning">Running</span>';
            }else if(data['measurement'] == 'complate'){
                mst += '<span class="label label-success">Complete</span>';
            }else{
                mst += '<span class="label label-primary">Not start</span>';
            }

            if(data['summary'] == 'running'){
                say += '<span class="label label-warning">Running</span>';
            }else if(data['summary'] == 'complate'){
                say += '<span class="label label-success">Complete</span>';
            }else{
                say += '<span class="label label-primary">Not start</span>';
            }

            $('td:eq(0)', row).html(button);
            $('td:eq(6)', row).html(mst);
            $('td:eq(7)', row).html(say);
        
        }
    });

    $('#chk').on('click', function(e) { 
        var chktester = $('#tester').val().length;
        if(chktester < 1){
            swal('Warning!',
            'Select a tester before searching.',
            'warning')
        }
        oTable.draw();
    });

    function getconfirmList(id)
    {
        $('#edit_id').val(id);
    } 

    function getSample(id)
    {
        $('#edit_id').val(id);
        $('#edit_id2').val(id);
        $('#edit_id4').val(id);
        $('#edit_id5').val(id);
        $('#edit_id6').val(id); 
    }
    
    function getSummary(id)
    {
        $('#edit_id7').val(id); 

        $.ajax({
            type: 'POST',
            url: `{{ url('select_info_queue') }}`,
            dataType: 'json',
            data : { 
                id : id,
                "_token" : "{{ csrf_token() }}",
            },
            success: function (result) { 
                
                $('#date').val(result.requestdate);
                $('#accept_date').val(result.accept_time);
                $('#measure_date').val(result.measure_time);

            }
        })

    }

    function getMeasure(id)
    {
        $('#edit_id3').val(id);
    }

    $( document ).ready(function() {
        console.log({{ count($queuelist) }});
        $('#ModalNotification').modal('show');
    });

    function cancelList()
    {
        var id = $('#edit_id').val();
        $('#exampleModalCenter').modal('hide');
        swal({
            title: "Are you sure?",
            text: "You wont be able to revert this!",
            type: "error",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Delete",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                'dataType': 'json',
                'type': 'post',
                'url': "{{url('cancelqueue')}}",
                'data': {
                    'id': id,
                    '_token': "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.success == 1) {
                        swal('Canceled!',
                            'Your file has been canceled.',
                            'success');
                        setTimeout(function () {
                            window.location.href = "{{ url('queue') }}";
                        }, 1000);
                    } else {

                    }
                }
            });
        });
    }

    function delteJob(id)
    {
        swal({
            title: "Are you sure?",
            text: "You wont be able to revert this!",
            type: "error",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Delete",
            closeOnConfirm: false
        },
        function () {
            $.ajax({
                'dataType': 'json',
                'type': 'post',
                'url': "{{url('deletequeue')}}",
                'data': {
                    'id': id,
                    '_token': "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.success == 1) {
                        swal('Canceled!',
                            'Your file has been canceled.',
                            'success');
                        setTimeout(function () {
                            window.location.href = "{{ url('queue') }}";
                        }, 1000);
                    } else {

                    }
                }
            });
        });
    }

    function confirmList()
    {
        $('#exampleModalCenter').modal('hide');

        var id = $('#edit_id').val();
        var tag = $('#edit_tag').val();
        var measurementtime = $('#measurementtime').val();
        var summarytime = $('#summarytime').val();
        
        if(id != "" && tag != "" && measurementtime != "" && summarytime != ""){

            swal({
                title: "",
                text: "Please wait..",
                imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                showConfirmButton: false
            });

            var forms = $('#fm-edit')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('acceptanceQueue') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    swal.close(); 
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

    function confirmSemple()
    {
        $('#exampleModalSemple').modal('hide');

        var id = $('#edit_id2').val();
        var sample_date = $('#sample_date').val();
        var sample_operator = $('#sample_operator').val();
        
        if(id != "" && sample_date != "" && sample_operator != ""){
            swal({
                title: "",
                text: "Please wait..",
                imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                showConfirmButton: false
            });

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

        var id = $('#edit_id4').val();
        var edit_deadline = $('#edit_deadline').val();
        
        if(id != "" && edit_deadline != ""){

            swal({
                title: "",
                text: "Please wait..",
                imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                showConfirmButton: false
            });

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
    
    function confirmmeasure()
    {
        $('#exampleMeasurement').modal('hide');

        var id = $('#edit_id5').val();
        var date = $('#measurement_date').val();
        var operator = $('#measurement_operator').val();
        
        if(id != "" && date != "" && operator != ""){
            swal({
                title: "",
                text: "Please wait..",
                imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                showConfirmButton: false
            });            

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

        var id = $('#edit_id6').val();
        var complete_date = $('#measure_complete').val();
        
        if(id != "" && complete_date != ""){
            swal({
                title: "",
                text: "Please wait..",
                imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                showConfirmButton: false
            });
            var forms = $('#fm-complete')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('queue-measure-complete') }}",
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

    function saveSummary()
    {
        $('#ModalSuccessSummary').modal('hide');

        var id = $('#edit_id7').val();
        var summary_date = $('#summary_date').val();
        
        if(id != "" && summary_date != ""){
            swal({
                title: "",
                text: "Please wait..",
                imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                showConfirmButton: false
            });
            var forms = $('#fm-summary')[0];
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

    
    function searchtop(){   
        
        var startdate = $('#startdate').val();
        var enddate = $('#enddate').val();

        if(startdate.trim() !== '' && enddate.trim() !== ''){

            $.ajax({
                type: 'POST',
                url: `{{ url('queue_search_top') }}`,
                dataType: 'json',
                data : { 
                    startdate:startdate,
                    enddate:enddate,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (result) { 

                    $('#sem_1').text(result.sem_count_0);
                    $('#sem_2').text(result.sem_count_1);
                    $('#sem_3').text(result.sem_count_2);

                    $('#ftir_1').text(result.ftir_count_0);
                    $('#ftir_2').text(result.ftir_count_1);
                    $('#ftir_3').text(result.ftir_count_2);

                    $('#lpc_1').text(result.lpc_count_0);
                    $('#lpc_2').text(result.lpc_count_1);
                    $('#lpc_3').text(result.lpc_count_2);

                    $('#gcms_1').text(result.gcms_count_0);
                    $('#gcms_2').text(result.gcms_count_1);
                    $('#gcms_3').text(result.gcms_count_1);

                    $('#ic_1').text(result.ic_count_0);
                    $('#ic_2').text(result.ic_count_1);
                    $('#ic_3').text(result.ic_count_2);

                    $('#dsc_1').text(result.dsc_count_0);
                    $('#dsc_2').text(result.dsc_count_1);
                    $('#dsc_3').text(result.dsc_count_2);

                    $('#weight_1').text(result.weight_count_0);
                    $('#weight_2').text(result.weight_count_1);
                    $('#weight_3').text(result.weight_count_2);  

                    $('#dust_1').text(result.dust_count_0);
                    $('#dust_2').text(result.dust_count_1);
                    $('#dust_3').text(result.dust_count_2);

                },
                error: function (data) {
                }
            });

        }else{

            swal("warning", "Select start and end dates before searching.", "warning");

        }

    }

    
    </script>
@endsection
