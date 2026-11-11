@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Calculation')
@section('breadcrumb-title', 'Calculation')


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
            <div class="card-header">

            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div class="card-block">
                        <form class="custom-validation" method="post" id="my_form" action="../output/raw_exportpdf">
                            {{ csrf_field() }}
                            <div class="dt-responsive table-responsive">
                                <div style="text-align: right">
                                    <a onclick="exportexcel()"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                        class="fa fa-file-pdf-o"></i> Export&nbsp;&nbsp;</button>  </a> 
                                </div>
                                <br>
                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                    <thead>
                                        <tr>
                                            <th rowspan="3" class="text-center" width="150"><input name="select_all"
                                                    value="1" id="selectAllAgents" type="checkbox"/></th>
                                            <th rowspan="3" class="text-center">Model</th>
                                            <th rowspan="3" class="text-center">Part UP/DN</th>
                                            <th rowspan="3" class="text-center">Part Number</th>
                                            <th rowspan="3" class="text-center">First date</th>
                                            <th rowspan="3" class="text-center">Last date</th>
                                            <th colspan="5" class="text-center">Count data</th>
                                        </tr>
                                        <tr>
                                                <th class="text-center">      <a onclick="exportexcellpc()"><button type="button"
                                                    class="btn btn-success btn-sm"><i
                                                        class="fa fa-file-excel-o"></i> Export</button></a></th>
                                            <th class="text-center">      <a onclick="exportexcelsem()"><button type="button"
                                                class="btn btn-success btn-sm"><i
                                                    class="fa fa-file-excel-o"></i> Export</button></a></th>
                                            <th class="text-center">      <a onclick="exportexcelic()"><button type="button"
                                                class="btn btn-success btn-sm"><i
                                                    class="fa fa-file-excel-o"></i> Export</button></a></th>
                                            <th class="text-center">      <a onclick="exportexcelgcms()"><button type="button"
                                                class="btn btn-success btn-sm"><i
                                                    class="fa fa-file-excel-o"></i> Export</button></a></th>
                                            <th class="text-center">      <a onclick="exportexcelftir()"><button type="button"
                                                class="btn btn-success btn-sm"><i
                                                    class="fa fa-file-excel-o"></i> Export</button></a></th>

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
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Export PDF</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
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
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-danger">&nbsp;&nbsp;<i
                                                        class="fa fa-file-pdf-o"></i> PDF&nbsp;&nbsp;</button>
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
                    "deferLoading": 57,
                    pageLength : 50,
                    lengthMenu: [[50, 100, 20, -1], [50, 100, 150, 'All']],
                    ajax: {
                        "url": "{{url('row/datatablecalculation')}}",
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
                            return '<input type="checkbox" name="id[]" value="' +
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
                            var semcount = '<span id="semshowcount'+data.id_model+'">'+data.semcount+' <input type="hidden" name="semcount'+data.id_model+'" id="" value="'+data.semcount+'"></span>';
                            showdata = showdata + data.semcount;
                        }
                        else
                        {
                            var semcount = '<span id="semshowcount'+data.id_model+'">-</span>';
                        }
                        if(data.iccount != null)
                        {
                            var iccount = '<span id="icshowcount'+data.id_model+'">'+data.iccount+' <input type="hidden" name="iccount'+data.id_model+'" id="" value="'+data.iccount+'"></span>';
                            showdata = showdata + data.iccount;
                        }
                        else
                        {
                            var iccount = '<span id="icshowcount'+data.id_model+'">-</span>';
                        }
                        if(data.gcmscount != null)
                        {
                            var gcmscount = '<span id="gcmsshowcount'+data.id_model+'">'+data.gcmscount+' <input type="hidden" name="gcmscount'+data.id_model+'" id="" value="'+data.gcmscount+'"></span>';
                            showdata = showdata + data.gcmscount;
                        }
                        else
                        {
                            var gcmscount = '<span id="gcmsshowcount'+data.id_model+'">-</span>';
                        }
                        if(data.ftircount != null)
                        {
                            var ftircount = '<span id="ftirshowcount'+data.id_model+'">'+data.ftircount+' <input type="hidden" name="ftircount'+data.id_model+'" id="" value="'+data.ftircount+'"></span>';
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
    
            $('.btn-success').click(function(){
                //alert(0);
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
            function exportexcel()
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
                    url: "{{ url('calculationexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close();
                            //window.location.href = "{{ url('local/storage/NHK-File/report/calculation/calculation.xlsx') }}";
                            window.location.href = "../local/storage/NHK-File/report/calculation/"+data.filename;
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


            function exportexcellpc()
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
                    url: "{{ url('lpccalculationexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close();
                            //window.location.href = "{{ url('local/storage/NHK-File/report/calculation/calculation.xlsx') }}";
                            window.location.href = "../local/storage/NHK-File/report/calculation/"+data.filename;
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


            function exportexcelsem()
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
                    url: "{{ url('semcalculationexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close();
                            //window.location.href = "{{ url('local/storage/NHK-File/report/calculation/calculation.xlsx') }}";
                            window.location.href = "../local/storage/NHK-File/report/calculation/"+data.filename;
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


            function exportexcelic()
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
                    url: "{{ url('iccalculationexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close();
                            //window.location.href = "{{ url('local/storage/NHK-File/report/calculation/calculation.xlsx') }}";
                            window.location.href = "../local/storage/NHK-File/report/calculation/"+data.filename;
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


            function exportexcelgcms()
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
                    url: "{{ url('gcmscalculationexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close();
                            //window.location.href = "{{ url('local/storage/NHK-File/report/calculation/calculation.xlsx') }}";
                            window.location.href = "../local/storage/NHK-File/report/calculation/"+data.filename;
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

            function exportexcelftir()
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
                    url: "{{ url('ftircalculationexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal.close();
                            //window.location.href = "{{ url('local/storage/NHK-File/report/calculation/calculation.xlsx') }}";
                            window.location.href = "../local/storage/NHK-File/report/calculation/"+data.filename;
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
                window.location.href = "{{ url('/output/view') }}";
            }
            function datestartfs(id) {
                var datestart = $('#datestart' + id).val();
                var dateend = $('#dateend' + id).val();
                if (datestart != "" && dateend != "") {
                    $.ajax({
                        type: 'get',
                        url: "{{ url('showcalculation') }}",
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
                            $('#mean'+id).html(data.mean+'<input type="hidden" name="mean'+data.id_model+'" id="" value="'+data.mean+'">');
                            $('#max'+id).html(data.max+'<input type="hidden" name="max'+data.id_model+'" id="" value="'+data.max+'">');
                            $('#min'+id).html(data.min+'<input type="hidden" name="std'+data.id_model+'" id="" value="'+data.std+'">');
                            $('#std'+id).html(data.std+'<input type="hidden" name="std'+data.id_model+'" id="" value="'+data.std+'">');
                            $('#mean3'+id).html(data.mean3+'<input type="hidden" name="mean3'+data.id_model+'" id="" value="'+data.mean3+'">');
                            $('#mean4'+id).html(data.mean4+'<input type="hidden" name="mean4'+data.id_model+'" id="" value="'+data.mean4+'">');
                            $('#mean4_5'+id).html(data.mean4_5+'<input type="hidden" name="mean4_5'+data.id_model+'" id="" value="'+data.mean4_5+'">');
                            $('#mean6'+id).html(data.mean6+'<input type="hidden" name="mean6'+data.id_model+'" id="" value="'+data.mean6+'">');
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
                        url: "{{ url('showcalculation') }}",
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
                            $('#mean'+id).html(data.mean+'<input type="hidden" name="mean'+data.id_model+'" id="" value="'+data.mean+'">');
                            $('#max'+id).html(data.max+'<input type="hidden" name="max'+data.id_model+'" id="" value="'+data.max+'">');
                            $('#min'+id).html(data.min+'<input type="hidden" name="std'+data.id_model+'" id="" value="'+data.std+'">');
                            $('#std'+id).html(data.std+'<input type="hidden" name="std'+data.id_model+'" id="" value="'+data.std+'">');
                            $('#mean3'+id).html(data.mean3+'<input type="hidden" name="mean3'+data.id_model+'" id="" value="'+data.mean3+'">');
                            $('#mean4'+id).html(data.mean4+'<input type="hidden" name="mean4'+data.id_model+'" id="" value="'+data.mean4+'">');
                            $('#mean4_5'+id).html(data.mean4_5+'<input type="hidden" name="mean4_5'+data.id_model+'" id="" value="'+data.mean4_5+'">');
                            $('#mean6'+id).html(data.mean6+'<input type="hidden" name="mean6'+data.id_model+'" id="" value="'+data.mean6+'">');
                        }
                    });
                }
            }
</script>
@endsection