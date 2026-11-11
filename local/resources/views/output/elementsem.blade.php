
@extends('layouts.main')
@section('title', 'Element SEM')
@section('breadcrumb-title', 'Element SEM')


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
                        <div class="col-sm-12 col-xl-2">
                            <label>&nbsp;</label><br>
                            <button id="chksearch" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div class="card-block">
                        <form class="custom-validation" method="post" id="my_form" action="../output/raw_exportpdf" target="_blank">
                            {{ csrf_field() }}
                            <div class="dt-responsive table-responsive">
                                <div style="text-align: right">
                                    <a onclick="csvexcel()"><button type="button"
                                        class="btn btn-primary btn-sm">&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i> Element raw report&nbsp;&nbsp;</button></a>
                                    <a onclick="csvexcel_customer()"><button type="button"
                                        class="btn btn-success btn-sm">&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i> Element customer report&nbsp;&nbsp;</button></a>
                                               
                                </div>
                                <br>
                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" width="150">
                                                <input name="select_all" value="1" id="selectAllAgents" type="checkbox"/>
                                            </th>
                                            <th rowspan="2" class="text-center">Model</th>
                                            <th rowspan="2" class="text-center">Part UP/DN</th>
                                            <th rowspan="2" class="text-center">Part Number</th>
                                            <th rowspan="2" class="text-center">First date</th>
                                            <th rowspan="2" class="text-center">Last date</th>
                                            <th  class="text-center">Count data</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">SEM</th>

                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>

                       
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('scripts')
<script>
             $(document).ready(function () {
                var i = 0;
                var oTable = $('#datatables').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    lengthChange: true,
                    pageLength : 50,
                    lengthMenu: [[50, 100, 20, -1], [50, 100, 150, 'All']],
                    ajax: {
                        "url": "{{url('row/datatables')}}",
                        "data": function (d) {
                         d.date_start =  $("#start_date").val();
                         d.date_end =  $("#end_date").val();
                         d.number = $("#numbercount").val();
                        }
                    },
                    columns: [{
                            'className': "text-center",
                            data: 'id_model',
                            name: 'id_model'
                        },
                        {
                            data: 'name_model',
                            name: 'name_model'
                        },
                        {
                            data: 'part',
                            name: 'part'
                        },
                        {
                            data: 'part_number',
                            name: 'part_number'
                        },
                        {
                            'className': "text-center",
                            data: 'line_type',
                            name: 'line_type'
                        },
                        {
                            'className': "text-center",
                            data: 'line_type',
                            name: 'line_type'
                        },
                        {
                            'className': "text-center",
                            data: 'line_type',
                            name: 'line_type'
                        },
                       
                        
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    'columnDefs': [{
                        'targets': 0,
                        'searchable': false,
                        'orderable': false,
                        'className': 'dt-body-center',
                        'render': function (data, type, full, meta) {
                            return '<input type="checkbox" name="id[]" id="id[]" value="' +
                                $('<div/>').text(data).html() + '">';
                        }
                    }],
                    rowCallback: function (row, data, index) {
                        i++;
                        var button = '<input type="hidden" id="datecount'+data.id_model+'" name="datecount'+data.id_model+'" value=""><input type="checkbox" name="checkbox">';
                        var first = '<input style="width: 80%" class="form-control" type="date" id="datestart'+data.id_model+'" name="datestart'+data.id_model+'" onchange="datestartfs('+data.id_model+')">';
                        var last = '<input style="width: 80%" class="form-control" type="date" id="dateend'+data.id_model+'" name="dateend'+data.id_model+'" onchange="dateendfs('+data.id_model+')">';
                        var showdata = 0;
                         
                        if(data.semcount != null)
                        {
                            var semcount = '<span id="semshowcount'+data.id_model+'">'+data.semcount+'<input type="hidden" name="semcount'+data.id_model+'" id="" value="'+data.semcount+'"></span>';
                            showdata = showdata + data.semcount;
                        }
                        else
                        {
                            var semcount = '<span id="semshowcount'+data.id_model+'">-</span>';
                        }
                         
                        $('td:eq(4)', row).html(first);
                        $('td:eq(5)', row).html(last);
                        $('td:eq(6)', row).html(semcount);
                        if(data.lastdate != '')
                        {
                            var showlast = '<input style="width: 80%" class="form-control" type="date" id="dateend'+data.id_model+'" name="dateend'+data.id_model+'" onchange="dateendfs('+data.id_model+')" value='+data.lastdate+'>';
                            $('td:eq(5)', row).html(showlast);
                        }
                        if(data.firstdate != '')
                        {
                            var showlast = '<input style="width: 80%" class="form-control" type="date" id="datestart'+data.id_model+'" name="datestart'+data.id_model+'" onchange="datestartfs('+data.id_model+')" value='+data.firstdate+'>';
                            $('td:eq(4)', row).html(showlast);
                        }
                        if(showdata == 0)
                        {
                            if(data.countdata == 2)
                            {
                                $(row).html("");
                            }
                        }
                    }
                });
                $('#datatables').on('page.dt', function () {
                    $('#selectAllAgents').prop('checked', false);
                });
                $('#chksearch').click(function(){
                oTable.ajax.reload();
                });
                $('body').on('change', '#selectAllAgents', function () {
                    var rows, checked;
                    rows = $('#datatables').find('tbody tr');
                    checked = $(this).prop('checked');
                    $.each(rows, function () {
                        var checkbox = $($(this).find('td').eq(0)).find('input').prop('checked', checked);
                    });
                });
            });
            
            function csvexcel()
            {
                swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                var forms = $('#my_form')[0];
                var data = new FormData(forms);
                
                $.ajax({
                    type: 'post',
                    url: "{{ url('excelelementsem')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {   
                            swal.close(); 
                            window.location.href = "../local/storage/NHK-File/report/elementsem/"+data.filename;
                        }else if(data.success == 2) {
                            swal("warning", "please select 1 model", "warning");
                        }
                    }
                });
            }  

            
            function csvexcel_customer()
            {
                swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                var forms = $('#my_form')[0];
                var data = new FormData(forms);
                
                $.ajax({
                    type: 'post',
                    url: "{{ url('customer_elementsem')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {   
                            swal.close(); 
                            window.location.href = "../local/storage/NHK-File/report/elementsem/"+data.filename;
                        }else if(data.success == 2) {
                            swal("warning", "please select 1 model", "warning");
                        }
                    }
                });
            } 

            
            
          
</script>
@endsection