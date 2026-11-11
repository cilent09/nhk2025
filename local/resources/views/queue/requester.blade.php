
@extends('layouts.main')
@section('title', 'Queuing')
@section('breadcrumb-title', 'Queuing') 


@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('queue/add') }}"><button class="btn btn-primary"><i class="fa fa-plus"></i> Create Queuing</button></a> 
                </div>

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
                                                            <th class="text-center">Job no</th> 
                                                            <th class="text-center">Job name</th> 
                                                            <th class="text-center">Due date</th>
                                                            <th class="text-center">Requester.</th>
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
                data: 'summary',
                name: 'summary'
            },

        ],
        rowCallback: function (row, data, index) {

            var say = '';
  
            if(data['summary'] == 'running'){
                say += '<span class="label label-warning">Running</span>';
            }else if(data['summary'] == 'complate'){
                say += '<span class="label label-success">Complete</span>';
            }else{
                say += '<span class="label label-primary">Not start</span>';
            }

            $('td:eq(6)', row).html(say);
			
			button = '<a href="queue-view/'+data.id+'"><button type="button" class="btn btn-info btn-sm" title="View data" style="font-size:14px;"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>&nbsp;';
			$('td:eq(0)', row).html(button);
        
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


    </script>
@endsection
