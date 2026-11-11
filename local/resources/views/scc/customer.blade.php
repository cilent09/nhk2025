
@extends('layouts.main')
@section('title', 'Report Customer')
@section('breadcrumb-title', 'Report Customer')


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
                        <label>Days back</label>
                        <select  class="form-control" name="numbercount" id="numbercount">
                            <option value="0">Null</option>
                            <option value="90">90</option>
                            <option value="120">120</option>
                            <option value="150">150</option>
                            <option value="180">180</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-xl-2">
                            <label>&nbsp;</label><br>
                            {{-- <input type="submit" name="submit" id="submit" class="btn btn-success" value="Search"> --}}
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
                        <form class="custom-validation" method="post" id="my_form" target="_blank">
                            {{ csrf_field() }}
                            <div class="dt-responsive table-responsive">
                                <div style="text-align: right">
                                    <a onclick="sccreport()"><button type="button"
                                        class="btn btn-warning btn-sm">SCC Report</button></a>
                                </div>
                                <br>
                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" width="150"><input name="select_all" value="1" id="selectAllAgents" type="checkbox"/></th>
                                            <th rowspan="2" class="text-center">Model</th>
                                            <th rowspan="2" class="text-center">Part UP/DN</th>
                                            <th rowspan="2" class="text-center">Part Number</th>
                                            <th rowspan="2" class="text-center">First date</th>
                                            <th rowspan="2" class="text-center">Last date</th>
                                            <th colspan="5" class="text-center">Count data</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">LPC</th> 
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
                        "url": "{{url('row/datatablesWD')}}",
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
                        if(data.lpccount != null)
                        {
                            var lpccount = '<span id="lpcshowcount'+data.id_model+'">'+data.lpccount+' <input type="hidden" name="lpccount'+data.id_model+'" id="" value="'+data.lpccount+'"></span>';
                            showdata = showdata + data.lpccount;
                        }
                        else
                        {
                            var lpccount = '<span id="lpcshowcount'+data.id_model+'">-</span>';
                        }
                         
                        $('td:eq(4)', row).html(first);
                        $('td:eq(5)', row).html(last);
                        $('td:eq(6)', row).html(lpccount); 
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
             
            function sccreport()
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
                    url: "{{ url('report_scc_customer')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Success", "File downloaded successfully", "success");
                           window.open("../local/storage/NHK-File/report/scc/"+data.filename);
                        } 
                        else if(data.success == 2) {
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 3) {
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else {
                            swal("warning", data.error , "error");
                        }
                    }
                });
            }  

            
</script>
@endsection
