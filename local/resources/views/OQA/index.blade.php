
@extends('layouts.main')
@section('title', 'New OQA lot')
@section('breadcrumb-title', 'New OQA lot')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ url('/OQA/Createlab') }}"><button class="btn btn-primary"><i class="fa fa-plus"></i> Create New OQA lot .</button></a>
                  
                </div>
                <div class="form-group row">
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-12">
                            <label class="block">Model <span style="font-size: 16px"  class="text-danger"></span></label>
                            
                        </div>
                        <div class="col-sm-12">
                            <input type="text" name="textmodel" id="textmodel"
                                value="" class="required form-control valid">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-12">
                            <label class="block">Lot number <span style="font-size: 16px"  class="text-danger"></span></label>
                        </div>
                        <div class="col-sm-12">
                            <input id="textlot" name="textlot" type="text"
                                class="required form-control valid" value="">
                        </div>
                    </div>                
                </div> 
                <div class="form-group row">
                    <div class="col-sm-1">
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-12">
                            <label class="block">Start <span style="font-size: 16px"  class="text-danger">*</span></label>
                            
                        </div>
                        <div class="col-sm-12">
                            <input type="date" name="date_start" id="date_start"
                                value="" class="required form-control valid">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="col-sm-12">
                            <label class="block">End <span style="font-size: 16px"  class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-12">
                            <input id="date_end" name="date_end" type="date"
                                class="required form-control valid" value="">
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="col-sm-12">
                            <label class="block">&nbsp;</label>
                        </div>
                        <div class="col-sm-12">
                            <button id="chk" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                        </div>
                       
                    </div>
                   
                </div> 
                <br>
                <br>
                
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-block p-0">
                    
                    <!-- Tab panes -->
                    <div class="tab-content card-block" >
                        
                            
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-12">
                                            <form class="custom-validation" method="post" id="my_form" >
                                            {{ csrf_field() }}

                                                @if (Auth::user()->status == 1)
                                                    <div style="text-align: right">
                                                        <a onclick="deleteoqaall()">
                                                            <button type="button" class="btn btn-danger" >&nbsp;&nbsp;
                                                            <i class="fa fa-trash"></i> Delete Select&nbsp;&nbsp;
                                                            </button>
                                                        </a>       
                                                    </div>
                                                @endif

                                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                                    <thead>
                                                    <tr>
                                                        @if (Auth::user()->status == 1)
                                                        <th  class="text-center" width="150"><input name="select_all"
                                                            value="1" id="selectAllAgents" type="checkbox"/></th>
                                                        @endif
                                                        <th class="text-center" width="150">#</th>
                                                        <th class="text-center">Model</th>
                                                        <th class="text-center">Part UP/DN</th>
                                                        <th class="text-center">Type</th>
                                                        <th class="text-center">Name</th>
                                                        <th class="text-center">ID</th>
                                                        <th class="text-center">Lot Number</th>
                                                        <th class="text-center">Start Time<br>(Receive)</th>
                                                        <th class="text-center">TGA date code<br>(Produced)</th>
                                                        <th class="text-center">Status</th>
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


<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">
@endsection

@section('scripts')
    <script>

    var userstatus = $('#userstatus').val();
    console.log('{{session('namemenu')}}');
    @if(session('namemenu') == "oqa")
        $(document).ready(function(){
            if (userstatus == 1) {
                var oTable = $('#datatables').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        "url": "{{url('OQA/datatables')}}",
                        "type": "POST",
                        "data": function (d) {
                            d.date_start = $("#date_start").val();
                            d.date_end = $("#date_end").val();
                            d.textmodel = $("#textmodel").val();
                            d.textlot = $("#textlot").val();
                        }
                    },
                    columns: [{
                            'className': "text-center",
                            data: 'id_createlab',
                            name: 'id_createlab'
                        },
                        {
                            data: 'name_model',
                            name: 'name_model'
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
                            data: 'type_lab',
                            name: 'type_lab'
                        },
                        {
                            data: 'name_lab',
                            name: 'name_lab'
                        },
                        {
                            data: 'code_lab',
                            name: 'code_lab'
                        },
                        {
                            data: 'lotnumber_lab',
                            name: 'lotnumber_lab'
                        },
                        {
                            data: 'starttimeas',
                            name: 'starttime'
                        },
                        {
                            data: 'tga',
                            name: 'tga'
                        },
                        {
                            data: 'starttimeas',
                            name: 'starttimeas'
                        },
                    ],
                    "order": [
                        [7, 'desc']
                    ],
                    'columnDefs': [{
                        'targets': 0,
                        'searchable': false,
                        'orderable': false,
                        'className': 'text-center',
                        'render': function (data, type, full, meta) {
                            return '<input type="checkbox" name="id[]" id="id[]" value="' +
                                $('<div/>').text(data).html() + '">';
                        }
                    }],
                    rowCallback: function (row, data, index) {
                        var p_st = 0;
                        var c_st = 0;
                        if (data.st_lpc == 1) {
                            p_st++;
                            if (data.lpc_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_sem == 1) {
                            p_st++;
                            if (data.sem_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_ic == 1) {
                            p_st++;
                            if (data.ic_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_gcms == 1) {
                            p_st++;
                            if (data.gcms_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_ftir == 1) {
                            p_st++;
                            if (data.ftir_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        
                        if (data.st_semtest == 1) {
                            p_st++;
                            if (data.semtest_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        var status = '';
                        if (p_st == c_st) {
                            status += '<span class="label label-success">done</span>';
                        } else {
                            status += '<span class="label label-warning">on going</span>';
                        }
                        var button = '';
                        button += '<a  href="{{asset("OQA/split/")}}/' + data.id_createlab + '" target="_blank"> <button  type="button" class="btn btn-info btn-sm" id="searchModel" title="View" ><font size="2"><i class="fa fa-print"style="font-size:18px;"></i></font></button></a>&nbsp;';
                        if (userstatus != 3) {
                            button += '<a href="{{asset("OQA/oqafile/")}}/' + data.id_createlab + '" ><button type="button" class="btn btn-warning btn-sm"  title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                        }
                        if (userstatus == 1) {
                            button += '<button type="button" class="btn btn-danger btn-sm" onclick="deleteoqa(' + data.id_createlab + ')" title="Delete"><i class="fa fa-trash" style="font-size:18px;"></i></button>&nbsp;';
                        }
                       // $('td:eq(8)', row).html(Getdatetime(data.starttime));
                        $('td:eq(1)', row).html(button);
                        $('td:eq(10)', row).html(status);
                    }
                });
                $('#datatables').on('page.dt', function () {
                    $('#selectAllAgents').prop('checked', false);
                });
                $('body').on('change', '#selectAllAgents', function () {
                    var rows, checked;
                    rows = $('#datatables').find('tbody tr');
                    checked = $(this).prop('checked');
                    $.each(rows, function () {
                        var checkbox = $($(this).find('td').eq(0)).find('input').prop('checked', checked);
                    });
                });
            } else {
                var oTable = $('#datatables').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        "url": "{{url('OQA/datatables')}}",
                        "type": "POST",
                        "data": function (d) {
                            d.date_start = $("#date_start").val();
                            d.date_end = $("#date_end").val();
                            d.textmodel = $("#textmodel").val();
                            d.textlot = $("#textlot").val();
                        }
                    },
                    columns: [{
                            'className': "text-center",
                            data: 'id_createlab',
                            name: 'id_createlab'
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
                            data: 'type_lab',
                            name: 'type_lab'
                        },
                        {
                            data: 'name_lab',
                            name: 'name_lab'
                        },
                        {
                            data: 'code_lab',
                            name: 'code_lab'
                        },
                        {
                            data: 'lotnumber_lab',
                            name: 'lotnumber_lab'
                        },
                        {
                            data: 'starttime',
                            name: 'starttime'
                        },
                        {
                            data: 'tga',
                            name: 'tga'
                        },
                        {
                            data: 'starttime',
                            name: 'starttime'
                        },
                    ],
                    "order": [
                        [7, 'desc']
                    ],
                    rowCallback: function (row, data, index) {
                        var p_st = 0;
                        var c_st = 0;
                        if (data.st_lpc == 1) {
                            p_st++;
                            if (data.lpc_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_sem == 1) {
                            p_st++;
                            if (data.sem_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_ic == 1) {
                            p_st++;
                            if (data.ic_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_gcms == 1) {
                            p_st++;
                            if (data.gcms_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_ftir == 1) {
                            p_st++;
                            if (data.ftir_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_semtest == 1) {
                            p_st++;
                            if (data.semtest_confirm_st == 1) {
                                c_st++;
                            }
                        }  
                        var status = '';
                        if (p_st == c_st) {
                            status += '<span class="label label-success">done</span>';
                        } else {
                            status += '<span class="label label-warning">on going</span>';
                        }
                        var button = '';
                        button += '<a  href="{{asset("OQA/split/")}}/' + data.id_createlab + '" target="_blank"> <button type="button" class="btn btn-info btn-sm" id="searchModel" title="View" ><font size="2"><i class="fa fa-print"style="font-size:18px;"></i></font></button></a>&nbsp;'; 
                        button += '<a href="{{asset("OQA/oqafile/")}}/' + data.id_createlab + '" ><button type="button" class="btn btn-warning btn-sm"  title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';                         
                        if (userstatus == 1) {
                            button += '<button type="button" class="btn btn-danger btn-sm" onclick="deleteoqa(' + data.id_createlab + ')" title="Delete"><i class="fa fa-trash" style="font-size:18px;"></i></button>&nbsp;';
                        }
                        if (userstatus != 1) {
                            $('td:eq(0)', row).html('');
                        }
                        
                        $('td:eq(0)', row).html(button);
                        $('td:eq(9)', row).html(status);
                    }
                });

            } 

            $('.btn-success').click(function(){
                oTable.ajax.reload();
            });
            var inputmodel = document.getElementById("textmodel");
            inputmodel.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                oTable.ajax.reload();
            }
            });

            var inputlot = document.getElementById("textlot");
            inputlot.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                oTable.ajax.reload();
            }
            });


            $('.btn-success').click(function(){
                oTablewd.ajax.reload();
            });
            var inputmodel = document.getElementById("textmodel");
            inputmodel.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                oTablewd.ajax.reload();
            }
            });

            var inputlot = document.getElementById("textlot");
            inputlot.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    oTablewd.ajax.reload();
                }
            });

        });
    @endif

    @if(session('namemenu') == "oqa2")
        $(document).ready(function(){
            if (userstatus == 1) {
                var oTablewd = $('#datatables').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        "url": "{{url('OQA/datatables_wd')}}",
                        "type": "POST",
                        "data": function (d) {
                            d.date_start = $("#date_start").val();
                            d.date_end = $("#date_end").val();
                            d.textmodel = $("#textmodel").val();
                            d.textlot = $("#textlot").val();
                        }
                    },
                    columns: [{
                            'className': "text-center",
                            data: 'id_createlab',
                            name: 'id_createlab'
                        },
                        {
                            data: 'name_model',
                            name: 'name_model'
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
                            data: 'type_lab',
                            name: 'type_lab'
                        },
                        {
                            data: 'name_lab',
                            name: 'name_lab'
                        },
                        {
                            data: 'code_lab',
                            name: 'code_lab'
                        },
                        {
                            data: 'lotnumber_lab',
                            name: 'lotnumber_lab'
                        },
                        {
                            data: 'starttimeas',
                            name: 'starttime'
                        },
                        {
                            data: 'tga',
                            name: 'tga'
                        },
                        {
                            data: 'starttimeas',
                            name: 'starttimeas'
                        },
                    ],
                    "order": [
                        [7, 'desc']
                    ],
                    'columnDefs': [{
                        'targets': 0,
                        'searchable': false,
                        'orderable': false,
                        'className': 'text-center',
                        'render': function (data, type, full, meta) {
                            return '<input type="checkbox" name="id[]" id="id[]" value="' +
                                $('<div/>').text(data).html() + '">';
                        }
                    }],
                    rowCallback: function (row, data, index) {

                        var p_st = 0;
                        var c_st = 0;
                        if (data.st_lpc == 1) {
                            p_st++;
                            if (data.lpc_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_sem == 1) {
                            p_st++;
                            if (data.sem_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_ic == 1) {
                            p_st++;
                            if (data.ic_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_gcms == 1) {
                            p_st++;
                            if (data.gcms_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_ftir == 1) {
                            p_st++;
                            if (data.ftir_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        
                        if (data.st_semtest == 1) {
                            p_st++;
                            if (data.semtest_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        var status = '';
                        if (p_st == c_st) {
                            status += '<span class="label label-success">done</span>';
                        } else {
                            status += '<span class="label label-warning">on going</span>';
                        }
                        var button = '';
                        button += '<a  href="{{asset("OQA/split/")}}/' + data.id_createlab + '" target="_blank"> <button  type="button" class="btn btn-info btn-sm" id="searchModel" title="View" ><font size="2"><i class="fa fa-print"style="font-size:18px;"></i></font></button></a>&nbsp;';
                        if (userstatus != 3) {
                            button += '<a href="{{asset("OQA/oqafilewd/")}}/' + data.id_createlab + '" ><button type="button" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                        }
                        if (userstatus == 1) {
                            button += '<button type="button" class="btn btn-danger btn-sm" onclick="deleteoqa(' + data.id_createlab + ')" title="Delete"><i class="fa fa-trash" style="font-size:18px;"></i></button>&nbsp;';
                        }
                       // $('td:eq(8)', row).html(Getdatetime(data.starttime));
                        $('td:eq(1)', row).html(button);
                        $('td:eq(10)', row).html(status);
                    }
                });
                $('#datatables').on('page.dt', function () {
                    $('#selectAllAgents').prop('checked', false);
                });
                $('body').on('change', '#selectAllAgents', function () {
                    var rows, checked;
                    rows = $('#datatables').find('tbody tr');
                    checked = $(this).prop('checked');
                    $.each(rows, function () {
                        var checkbox = $($(this).find('td').eq(0)).find('input').prop('checked', checked);
                    });
                });

                $('.btn-success').click(function(){
                    oTablewd.ajax.reload();
                });
                var inputmodel = document.getElementById("textmodel");
                inputmodel.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    oTablewd.ajax.reload();
                }
                });

                var inputlot = document.getElementById("textlot");
                inputlot.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    oTablewd.ajax.reload();
                }
                });


                $('.btn-success').click(function(){
                    oTablewd.ajax.reload();
                });
                var inputmodel = document.getElementById("textmodel");
                inputmodel.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    oTablewd.ajax.reload();
                }
                });

                var inputlot = document.getElementById("textlot");
                inputlot.addEventListener("keyup", function(event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        oTablewd.ajax.reload();
                    }
                });
            } else {
                var oTablewd = $('#datatables').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        "url": "{{url('OQA/datatables_wd')}}",
                        "type": "POST",
                        "data": function (d) {
                            d.date_start = $("#date_start").val();
                            d.date_end = $("#date_end").val();
                            d.textmodel = $("#textmodel").val();
                            d.textlot = $("#textlot").val();
                        }
                    },
                    columns: [{
                            'className': "text-center",
                            data: 'id_createlab',
                            name: 'id_createlab'
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
                            data: 'type_lab',
                            name: 'type_lab'
                        },
                        {
                            data: 'name_lab',
                            name: 'name_lab'
                        },
                        {
                            data: 'code_lab',
                            name: 'code_lab'
                        },
                        {
                            data: 'lotnumber_lab',
                            name: 'lotnumber_lab'
                        },
                        {
                            data: 'starttime',
                            name: 'starttime'
                        },
                        {
                            data: 'tga',
                            name: 'tga'
                        },
                        {
                            data: 'starttime',
                            name: 'starttime'
                        },
                    ],
                    "order": [
                        [7, 'desc']
                    ],
                    rowCallback: function (row, data, index) {
                        var p_st = 0;
                        var c_st = 0;
                        if (data.st_lpc == 1) {
                            p_st++;
                            if (data.lpc_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_sem == 1) {
                            p_st++;
                            if (data.sem_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_ic == 1) {
                            p_st++;
                            if (data.ic_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_gcms == 1) {
                            p_st++;
                            if (data.gcms_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_ftir == 1) {
                            p_st++;
                            if (data.ftir_confirm_st == 1) {
                                c_st++;
                            }
                        }
                        if (data.st_semtest == 1) {
                            p_st++;
                            if (data.semtest_confirm_st == 1) {
                                c_st++;
                            }
                        }  
                        var status = '';
                        if (p_st == c_st) {
                            status += '<span class="label label-success">done</span>';
                        } else {
                            status += '<span class="label label-warning">on going</span>';
                        }
                        var button = '';
                        button += '<a  href="{{asset("OQA/split/")}}/' + data.id_createlab + '" target="_blank"> <button type="button" class="btn btn-info btn-sm" id="searchModel" title="View" ><font size="2"><i class="fa fa-print"style="font-size:18px;"></i></font></button></a>&nbsp;'; 
                        button += '<a href="{{asset("OQA/oqafilewd/")}}/' + data.id_createlab + '" ><button type="button" class="btn btn-warning btn-sm"  title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';                         
                        if (userstatus == 1) {
                            button += '<button type="button" class="btn btn-danger btn-sm" onclick="deleteoqa(' + data.id_createlab + ')" title="Delete"><i class="fa fa-trash" style="font-size:18px;"></i></button>&nbsp;';
                        }
                        if (userstatus != 1) {
                            $('td:eq(0)', row).html('');
                        }
                        
                        $('td:eq(0)', row).html(button);
                        $('td:eq(9)', row).html(status);
                    }
                });

                $('.btn-success').click(function(){
                    oTablewd.ajax.reload();
                });
                var inputmodel = document.getElementById("textmodel");
                inputmodel.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    oTablewd.ajax.reload();
                }
                });

                var inputlot = document.getElementById("textlot");
                inputlot.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    oTablewd.ajax.reload();
                }
                });


                $('.btn-success').click(function(){
                    oTablewd.ajax.reload();
                });
                var inputmodel = document.getElementById("textmodel");
                inputmodel.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                    event.preventDefault();
                    oTablewd.ajax.reload();
                }
                });

                var inputlot = document.getElementById("textlot");
                inputlot.addEventListener("keyup", function(event) {
                    if (event.keyCode === 13) {
                        event.preventDefault();
                        oTablewd.ajax.reload();
                    }
                });

            } 
        });

    @endif
    
    function deleteoqa(id)
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
                    'url': "{{url('deleteoqaSubmit')}}",
                    'data': {
                        'id': id,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Deleted!',
                                'Your file has been deleted.',
                                'success');
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('OQA/incoming') }}";
                            }, 1000);
                        } else {

                        }
                    }
                });
            });
    }

    function deleteoqaall() {
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
                var forms = $('#my_form')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'post',
                    url: "{{ url('deleteoqaall')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Deleted!',
                                'Your file has been deleted.',
                                'success');
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('OQA/incoming') }}";
                            }, 1000);
                        } else {}
                    }
                });
            });

    }
    
    function deleteoqaall_wd() {
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
                var forms = $('#my_form_wd')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'post',
                    url: "{{ url('deleteoqaallwd')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Deleted!',
                                'Your file has been deleted.',
                                'success');
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('OQA/incoming') }}";
                            }, 1000);
                        } else {}
                    }
                });
            });

    }
    </script>
@endsection
