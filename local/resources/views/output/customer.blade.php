
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
                            <button id="chksearch" class="btn btn-success "><i class="fa fa-search"></i> Search</button>
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
                        <form class="custom-validation" method="post" id="my_form" action="../output/customer_exportpdf" target="_blank">
                            {{ csrf_field() }}
                            <div class="dt-responsive table-responsive">
                                <div style="text-align: right">
                                    <a onclick="viewgraph()"><button type="button"
                                        class="btn btn-primary btn-sm">&nbsp;&nbsp;<i
                                            class="fa fa-line-chart"></i> VIEW&nbsp;&nbsp;</button></a>
                                    <a onclick="csvexceltes()"><button type="button"class="btn btn-success btn-sm">&nbsp;&nbsp;<i
                                            class="fa fa-file-excel-o"></i> CSV&nbsp;&nbsp;</button></a>
                                    <a onclick="Graphexcel()"><button type="button" class="btn btn-info btn-sm">&nbsp;&nbsp;<i
                                            class="fa fa-file-pdf-o"></i> GRAPH&nbsp;&nbsp;</button>  </a>
                                    <a onclick="exportpdf()"><button type="button" class="btn btn-danger btn-sm" >&nbsp;&nbsp;<i
                                            class="fa fa-file-pdf-o"></i> PDF&nbsp;&nbsp;</button></a>  
                                    <a onclick="exportexcelratio()"><button type="button" class="btn btn-danger btn-sm" >&nbsp;&nbsp;<i
                                            class="fa fa-file-excel-o"></i> SIGMA RATIO&nbsp;&nbsp;</button></a>                          
                                </div>
                                <br>
                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" width="150"><input name="select_all"
                                                    value="1" id="selectAllAgents" type="checkbox"/></th>
                                            <th rowspan="2" class="text-center">Model</th>
                                            <th rowspan="2" class="text-center">Part UP/DN</th>
                                            <th rowspan="2" class="text-center">Part Number</th>
                                            <th rowspan="2" class="text-center">First date</th>
                                            <th rowspan="2" class="text-center">Last date</th>
                                            <th colspan="5" class="text-center">Count data</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">LPC</th>
                                            <th class="text-center">SEM</th>
                                            <th class="text-center">IC</th>
                                            <th class="text-center">GCMS</th>
                                            <th class="text-center">FTIR</th>

                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
                                <div class="modal-dialog  modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Export PDF</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-sm">
                                                    <div class="col">
                                                        <div class="col-sm-12">
                                                            <label for="name-2" class="block">Date
                                                        </div>
                                                        @php
                                                            $datenow = date('Y-m-d');
                                                        @endphp
                                                        <div class="col-sm-12">
                                                        <input id="textdate" name="textdate" class="form-control" type="date" value="{{$datenow}}" />
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="col">
                                                        <div class="col-sm-12">
                                                            <label for="surname-2" class="block">Approval name</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <input id="textnamepproval" name="textnamepproval" class="form-control" type="text" value="QE" />
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="col">
                                                        <div class="col-sm-12">
                                                            <label for="surname-2" class="block">Prepare name</label>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <input id="textname" name="textname" class="form-control" type="text" />
                                                        </div>
                                                    </div>
                                                    <br>
                                                </div>
                                                <div class="col-sm">
                                                    <div class="col-sm-12">
                                                        <div class="col-md-10 col-xl-12">
                                                                <div class="card-block seo-statustic">
                                                                    <h5>Show line</h5>
                                                                    <p>&nbsp;</p>
                                                                    <div class="to-do-list">
                                                                        <div class="checkbox-fade fade-in-primary">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    id="spec"
                                                                                    name="spec" value="1"
                                                                                    style="display:none" checked>
                                                                                <span class="cr">
                                                                                    <i
                                                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                </span>
                                                                                <span>Specification</span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="to-do-list">
                                                                        <div class="checkbox-fade fade-in-primary">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    id="tl"
                                                                                    name="tl" value="1"
                                                                                    style="display:none" checked>
                                                                                <span class="cr">
                                                                                    <i
                                                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                </span>
                                                                                <span>Trigger limit</span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="to-do-list">
                                                                        <div class="checkbox-fade fade-in-primary">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    id="ucl"
                                                                                    name="ucl" value="1"
                                                                                    style="display:none" checked>
                                                                                <span class="cr">
                                                                                    <i
                                                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                </span>
                                                                                <span>Upper control limit</span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="to-do-list">
                                                                        <div class="checkbox-fade fade-in-primary">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    id="lcl"
                                                                                    name="lcl" value="1"
                                                                                    style="display:none" checked>
                                                                                <span class="cr">
                                                                                    <i
                                                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                </span>
                                                                                <span>Lower control limit</span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="to-do-list">
                                                                        <div class="checkbox-fade fade-in-primary">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    id="cl"
                                                                                    name="cl" value="1"
                                                                                    style="display:none" checked>
                                                                                <span class="cr">
                                                                                    <i
                                                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                </span>
                                                                                <span>Center line</span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="to-do-list">
                                                                        <div class="checkbox-fade fade-in-primary">
                                                                            <label>
                                                                                <input type="checkbox"
                                                                                    id="is"
                                                                                    name="is" value="1"
                                                                                    style="display:none" checked>
                                                                                <span class="cr">
                                                                                    <i
                                                                                        class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                                </span>
                                                                                <span>Internal specification</span>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
        
                                                            </div>
                                                    </div>
                                                </div>
                                          
                                              </div>
                                         
                                          

                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">&nbsp;&nbsp;<i
                                                        class="fa fa-file-pdf-o"></i> PDF&nbsp;&nbsp;</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="default-Modaleexcel" tabindex="-1" role="dialog">
                                <div class="modal-dialog  modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Export Excel</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <center><div class="row">
                                                <div class="col-sm">
                                                    {{-- <a href="#" onclick="Graphexcel1()"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                                        class="fa fa-file-excel-o"></i> Data&nbsp;&nbsp;</button></a> --}}
                                                    <a href="#" onclick="Graphexcel1_2()"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                                        class="fa fa-file-excel-o"></i> Data&nbsp;&nbsp;</button></a>
                                                    <a href="#" onclick="Graphexcel2()"><button type="button" class="btn btn-danger">&nbsp;&nbsp;<i
                                                        class="fa fa-line-chart"></i> Graph&nbsp;&nbsp;</button></a>

                                                    {{-- <a href="#" onclick="Graphexcel1_2()"><button type="button" class="btn btn-warning">&nbsp;&nbsp;<i
                                                        class="fa fa-file-excel-o"></i> DataForTest&nbsp;&nbsp;</button></a> --}}
                                                </div>
                                          
                                              </div></center>
                                         
                                          
    
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                                               
                                        </div>
                                    </div>
                                </div>
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
                        "url": "{{url('customer/datatables')}}",
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
                        if(data.semcount != null)
                        {
                            var semcount = '<span id="semshowcount'+data.id_model+'">'+data.semcount+'<input type="hidden" name="semcount'+data.id_model+'" id="" value="'+data.semcount+'"></span>';
                            showdata = showdata + data.semcount;
                        }
                        else
                        {
                            var semcount = '<span id="semshowcount'+data.id_model+'">-</span>';
                        }
                        if(data.iccount != null)
                        {
                            var iccount = '<span id="icshowcount'+data.id_model+'">'+data.iccount+'<input type="hidden" name="iccount'+data.id_model+'" id="" value="'+data.iccount+'"></span>';
                            showdata = showdata + data.iccount;
                        }
                        else
                        {
                            var iccount = '<span id="icshowcount'+data.id_model+'">-</span>';
                        }
                        if(data.gcmscount != null)
                        {
                            var gcmscount = '<span id="gcmsshowcount'+data.id_model+'">'+data.gcmscount+'<input type="hidden" name="gcmscount'+data.id_model+'" id="" value="'+data.gcmscount+'"></span>';
                            showdata = showdata + data.gcmscount;
                        }
                        else
                        {
                            var gcmscount = '<span id="gcmsshowcount'+data.id_model+'">-</span>';
                        }
                        if(data.ftircount != null)
                        {
                            var ftircount = '<span id="ftirshowcount'+data.id_model+'">'+data.ftircount+'<input type="hidden" name="ftircount'+data.id_model+'" id="" value="'+data.ftircount+'"></span>';
                            showdata = showdata + data.ftircount;
                        }
                        else
                        {
                            var ftircount = '<span id="ftirshowcount'+data.id_model+'">-</span>';
                        }
                        $('td:eq(4)', row).html(first);
                        $('td:eq(5)', row).html(last);
                        $('td:eq(6)', row).html(lpccount);
                        $('td:eq(7)', row).html(semcount);
                        $('td:eq(8)', row).html(iccount);
                        $('td:eq(9)', row).html(gcmscount);
                        $('td:eq(10)', row).html(ftircount);
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


            function Graphexcel()
            {
                $('#default-Modaleexcel').modal('show');
            }

            function Graphexcel1()
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
                    url: "{{ url('customer_exportexcel')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close();
                                window.location.href = "../local/storage/NHK-File/report/graphexcel/"+data.filename;
                        }
                        else if(data.success == 2) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 3) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 4) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else {
                        }
                    }
                });
            }

            function Graphexcel2()
            {
                //$('#default-Modaleexcel').modal('hide');
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
                    url: "{{ url('excelc_exportexcel')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                       
                        if (data.success == 1) { 
                            swal.close();
                        window.location.href = "../local/storage/NHK-File/report/graphexport/"+data.filename;  
                        }
                        else if(data.success == 2) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 3) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 4) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else {
                        }
                           
                    }
                });
            }
            
            function Graphexcel1_2()
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
                    url: "{{ url('test__customerexportexcel')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close();
                                window.location.href = "../local/storage/NHK-File/report/graphexcel/"+data.filename;
                        }
                        else if(data.success == 2) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 3) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 4) {
                            $('#default-Modaleexcel').modal('hide');
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else {
                        }
                    }
                });
            }

            function csvexceltes()
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
                    url: "{{ url('customer_csvexcel')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close()
                               
                                window.location.href = "../local/storage/NHK-File/report/CSV/"+data.filename;
                        } 
                        else if(data.success == 2) {
                           
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 3) {
                            
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else if(data.success == 4) {
                    
                            swal("warning", "Please selete UP and DN with same model", "warning");
                        }
                        else {
                        }
                    }
                });
            }  
            
            function exportpdf()
            {
                var forms = $('#my_form')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'post',
                    url: "{{ url('customer_chkexportpdf')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            $('#default-Modal').modal('show');
                        } 
                        else if(data.success == 2) {
                            swal("warning", "please select 1 model", "warning");
                        }
                        else if(data.success == 3) {
                            swal("warning", "please select 1 model", "warning");
                        }
                        else {
                        }
                    }
                });
            }  
            
            function viewgraph() {

               
                var forms = $('#my_form')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'post',
                    url: "{{ url('customer_chkexportpdf')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            window.location.href = "{{ url('/output/viewdatacustomerall') }}/"+data.id;
                            //window.location.href = "../local/storage/NHK-File/report/graphexport/"+data.filename;  
                        } 
                        else if(data.success == 2) {
                            swal("warning", "please select 1 model", "warning");
                        }
                        else if(data.success == 3) {
                            swal("warning", "please select 1 model", "warning");
                        }
                        else {
                        }
                    }
                });
            }

            function exportexcelratio(){
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
                    url: "{{ url('customer_excelratio')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {   
                            swal.close(); 
                            window.location.href = "../local/storage/NHK-File/report/graphexportratio/"+data.filename;
                        }else if(data.success == 2) {
                            swal("warning", "please select 1 model", "warning");
                        }
                    }
                });
            }
            
            function exportData() {
                swal({
                    title: "Are you sure?",
                    text: "Export Data",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Confirm",
                    closeOnConfirm: false
                },
                function(){
                    swal("Successed", "Export data successed", "success");
                });
            }
            
            function viewData() {
                window.location.href = "{{ url('/output/viewdataall') }}";
            }
            
            function datestartfs(id) {
                var datestart = $('#datestart' + id).val();
                var dateend = $('#dateend' + id).val();
                if (datestart != "" && dateend != "") {
                    $.ajax({
                        type: 'get',
                        url: "{{ url('showcountdata') }}",
                        data: 
                        {
                            'datestart' : datestart,
                            'dateend' : dateend,
                            'id' : id
                        },
                        success: function (data) {
                            $('#lpcshowcount'+id).html(data.lpccount+'<input type="hidden" name="lpccount'+data.id_model+'" id="" value="'+data.lpccount+'">');
                            $('#semshowcount'+id).html(data.semcount+'<input type="hidden" name="semcount'+data.id_model+'" id="" value="'+data.semcount+'">');
                            $('#icshowcount'+id).html(data.iccount+'<input type="hidden" name="iccount'+data.id_model+'" id="" value="'+data.iccount+'">');
                            $('#gcmsshowcount'+id).html(data.gcmscount+'<input type="hidden" name="gcmscount'+data.id_model+'" id="" value="'+data.gcmscount+'">');
                            $('#ftirshowcount'+id).html(data.ftircount+'<input type="hidden" name="ftircount'+data.id_model+'" id="" value="'+data.ftircount+'">');
                        }
                    });
                }
            }
            
            function dateendfs(id)
            {
                var datestart  = $('#datestart'+id).val();
                var dateend  = $('#dateend'+id).val();
                if(datestart != "" && dateend != "")
                {
                    $.ajax({
                        type: 'get',
                        url: "{{ url('showcountdata') }}",
                        data: 
                        {
                            'datestart' : datestart,
                            'dateend' : dateend,
                            'id' : id
                        },
                        success: function (data) {
                            $('#lpcshowcount'+id).html(data.lpccount+'<input type="hidden" name="lpccount'+data.id_model+'" id="" value="'+data.lpccount+'">');
                            $('#semshowcount'+id).html(data.semcount+'<input type="hidden" name="semcount'+data.id_model+'" id="" value="'+data.semcount+'">');
                            $('#icshowcount'+id).html(data.iccount+'<input type="hidden" name="iccount'+data.id_model+'" id="" value="'+data.iccount+'">');
                            $('#gcmsshowcount'+id).html(data.gcmscount+'<input type="hidden" name="gcmscount'+data.id_model+'" id="" value="'+data.gcmscount+'">');
                            $('#ftirshowcount'+id).html(data.ftircount+'<input type="hidden" name="ftircount'+data.id_model+'" id="" value="'+data.ftircount+'">');
                        }
                    });
                }
            }
</script>
@endsection