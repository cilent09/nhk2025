@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Calculation')
@section('breadcrumb-title', 'Calculation')


@section('content')

<style>

    .middle {
        vertical-align: middle !important;
    }

</style>
<form class="custom-validation" method="post" id="my_form" action="../output/raw_exportpdf">
{{ csrf_field() }}
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            <h2><img src="{{ asset('template/assets/images/item/cal02.JPG')}}" alt="Combine"> Combine data</h2>
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
                        <label>Count data back </label>
                        <input type="number" min="1" step="1" max="999" name="numbercount" id="numbercount" class="form-control">
                    </div>
                    <div class="col-sm-12 col-xl-1">
                        <label style="color: white;">setcountbank</label>
                        <input class="form-control" type="checkbox" value="1" id="setcountbank" style="width: 30px; height: 30px;">
                        <input type="hidden" id="checkcountbank" name="checkcountbank" value="0">
                    </div>
                    <div class="col-sm-12 col-xl-1">
                        <label>&nbsp;</label>
                        <button type="button" id="chk" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div class="card-block">
                            <div class="dt-responsive table-responsive">
                                <div style="text-align: right">
                                    <a onclick="newexportexcel()"><button type="button" class="btn btn-primary">&nbsp;&nbsp;<i
                                        class="fa fa-file-pdf-o"></i> Export&nbsp;&nbsp;</button>  </a> 
                                </div>

                                <input type="hidden" name="numcountdata" id="numcountdata" value="0">

                                <br>
                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables" style="font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th rowspan="3" class="text-center" width="150"><input name="select_all" value="1" id="selectAllAgents" type="checkbox"/></th>
                                            <th rowspan="3" class="text-center">Site</th>
                                            <th rowspan="3" class="text-center">Model</th>
                                            <th rowspan="3" class="text-center">Drive Name</th>
                                            <th rowspan="3" class="text-center">Part Number</th>
                                            <th rowspan="3" class="text-center">First date</th>
                                            <th rowspan="3" class="text-center">Last date</th>
                                            <th colspan="5" class="text-center">Count data</th>
                                        </tr>
                                        <tr>
                                           <th class="text-center">      
                                                <a onclick="exportexcellpc()"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</button></a>
                                            </th>
                                            <th class="text-center">      
                                                <a onclick="exportexcelsem()"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</button></a>
                                            </th>
                                            <th class="text-center">      
                                                <a onclick="exportexcelic()"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</button></a>
                                            </th>
                                            <th class="text-center">      
                                                <a onclick="exportexcelgcms()"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</button></a>
                                            </th>
                                            <th class="text-center">      
                                                <a onclick="exportexcelftir()"><button type="button" class="btn btn-success btn-sm"><i class="fa fa-file-excel-o"></i> Export</button></a>
                                            </th>
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

                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>
  
</form>

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
                    ordering : false,
                    pageLength : 50,
                    lengthMenu: [[50, 100, 20, -1], [50, 100, 150, 'All']],
                    ajax: {
                        "url": "{{url('datatablecombine')}}",
                        "data": function (d) {
                         d.date_start =  $("#start_date").val();
                         d.date_end =  $("#end_date").val();
                         d.number = $("#numbercount").val();
                        }
                    },
                    columns: [{
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name'
                        },
                        {
                            'className': "text-center middle",
                            data: 'site',
                            name: 'site'
                        },
                        {
                            data: 'model',
                            name: 'model'
                        },
                        {
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name' 
                        },
                        {
                            data: 'partnumber',
                            name: 'partnumber'
                        },
                        {
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name'
                        },
                        {
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name'
                        },
                        {
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name'
                        },
                        {
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name'
                        },
                        {
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name'
                        },
                        {
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name'
                        },
                        {
                            'className': "text-center middle",
                            data: 'drive_name',
                            name: 'drive_name'
                        },
                        
                    ],
                    "order": [
                        [0, "desc"]
                    ],
                    rowCallback: function (row, data, index) {
                        i++;

                        var showdata = 0;
                        if(data.lpccount != null)
                        {
                            var lpccount = '<span id="lpcshowcount'+data.indexs+'">'+data.lpccount+' <input type="hidden" name="lpccount'+data.indexs+'" id="" value="'+data.lpccount+'"></span>';
                            showdata = showdata + data.lpccount;
                        }
                        else
                        {
                            var lpccount = '<span id="lpcshowcount'+data.indexs+'">-</span>';
                        }

                        if(data.semcount != null)
                        {
                            var semcount = '<span id="semshowcount'+data.indexs+'">'+data.semcount+' <input type="hidden" name="semcount'+data.indexs+'" id="" value="'+data.semcount+'"></span>';
                            showdata = showdata + data.semcount;
                        }
                        else
                        {
                            var semcount = '<span id="semshowcount'+data.indexs+'">-</span>';
                        }
                        if(data.iccount != null)
                        {
                            var iccount = '<span id="icshowcount'+data.indexs+'">'+data.iccount+' <input type="hidden" name="iccount'+data.indexs+'" id="" value="'+data.iccount+'"></span>';
                            showdata = showdata + data.iccount;
                        }
                        else
                        {
                            var iccount = '<span id="icshowcount'+data.indexs+'">-</span>';
                        }
                        if(data.gcmscount != null)
                        {
                            var gcmscount = '<span id="gcmsshowcount'+data.indexs+'">'+data.gcmscount+' <input type="hidden" name="gcmscount'+data.indexs+'" id="" value="'+data.gcmscount+'"></span>';
                            showdata = showdata + data.gcmscount;
                        }
                        else
                        {
                            var gcmscount = '<span id="gcmsshowcount'+data.indexs+'">-</span>';
                        }
                        if(data.ftircount != null)
                        {
                            var ftircount = '<span id="ftirshowcount'+data.indexs+'">'+data.ftircount+' <input type="hidden" name="ftircount'+data.indexs+'" id="" value="'+data.ftircount+'"></span>';
                            showdata = showdata + data.ftircount;
                        }
                        else
                        {
                            var ftircount = '<span id="ftirshowcount'+data.indexs+'">-</span>';
                        }
                       
                        $('td:eq(0)', row).html(`<input type="checkbox" id="id_${data.id_model}" name="id[]" value="${data.id_model}"><input type="hidden" name="idlab_lpc[${data.id_model}]" value="${data.lpccount_idlab}"><input type="hidden" name="idlab_sem[${data.id_model}]" value="${data.semcount_idlab}"><input type="hidden" name="idlab_ic[${data.id_model}]" value="${data.iccount_idlab}"><input type="hidden" name="idlab_gcms[${data.id_model}]" value="${data.gcmscount_idlab}"><input type="hidden" name="idlab_ftir[${data.id_model}]" value="${data.ftircount_idlab}">`);

                        $('td:eq(7)', row).html(lpccount);
                        $('td:eq(8)', row).html(semcount);
                        $('td:eq(9)', row).html(iccount);
                        $('td:eq(10)', row).html(gcmscount);
                        $('td:eq(11)', row).html(ftircount);
                  
                        if(data.lastdate != '')
                        {
                            $('td:eq(6)', row).html(`<input style="width: 80%" class="form-control" type="date" id="dateend${data.indexs}" name="dateend${data.indexs}" onchange="dateendfs('${data.indexs}')" value='${data.lastdate}'>`);
                        }
                        if(data.firstdate != '')
                        {
                            $('td:eq(5)', row).html(`<input style="width: 80%" class="form-control" type="date" id="datestart${data.indexs}" name="datestart${data.indexs}" onchange="datestartfs('${data.indexs}')" value='${data.firstdate}'>`);
                        }
                        if(showdata == 0)
                        {
                            if(data.countdata == 2)
                            {
                                $(row).html("");
                            }
                        }
                        $('#numcountdata').val(index+1);
                    }
                });
                $('#chk').click(function(){
                    oTable.ajax.reload();
                });
            });

            $('#setcountbank').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#start_date').prop('disabled', true);
                } else {
                    $('#start_date').prop('disabled', false);
                }
            });
 
            function datestartfs(id) {
                swal({
                    title: "",
                    text: "Please wait..",
                    imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                    showConfirmButton: false
                });
                var splitnames = id.split("_");
                var datestart = $('#datestart' + id).val();
                var dateend = $('#dateend' + id).val();
                if (datestart != "" && dateend != "") {
                    $.ajax({
                        type: 'get',
                        url: "{{ url('showcombinedata') }}",
                        data: 
                        {
                            'datestart' : datestart,
                            'dateend' : dateend,
                            'id' : id
                        },
                        success: function (data) {
                            swal.close();
                            $('#lpcshowcount'+id).html(data.lpccount+'<input type="hidden" name="lpccount'+id+'" id="lpccount'+id+'" value="'+data.lpccount+'">');
                            $('#semshowcount'+id).html(data.semcount+'<input type="hidden" name="semcount'+id+'" id="semcount'+id+'" value="'+data.semcount+'">');
                            $('#icshowcount'+id).html(data.iccount+'<input type="hidden" name="iccount'+id+'" id="iccount'+id+'" value="'+data.iccount+'">');
                            $('#gcmsshowcount'+id).html(data.gcmscount+'<input type="hidden" name="gcmscount'+id+'" id="gcmscount'+id+'" value="'+data.gcmscount+'">');
                            $('#ftirshowcount'+id).html(data.ftircount+'<input type="hidden" name="ftircount'+id+'" id="ftircount'+id+'" value="'+data.ftircount+'">');
                        }
                    });
                }
            }

            $("#selectAllAgents").click(function(){
                $('input[name="id[]"]').prop('checked', true);
            }); 

            function dateendfs(id) {
                swal({
                    title: "",
                    text: "Please wait..",
                    imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                    showConfirmButton: false
                });
                var splitnames = id.split("_");
                var datestart = $('#datestart' + id).val();
                var dateend = $('#dateend' + id).val();
                if (datestart != "" && dateend != "") {
                    $.ajax({
                        type: 'get',
                        url: "{{ url('showcombinedata') }}",
                        data: 
                        {
                            'datestart' : datestart,
                            'dateend' : dateend,
                            'id' : id
                        },
                        success: function (data) {
                            swal.close();
                            $('#lpcshowcount'+id).html(data.lpccount+'<input type="hidden" name="lpccount'+id+'" id="lpccount'+id+'" value="'+data.lpccount+'">');
                            $('#semshowcount'+id).html(data.semcount+'<input type="hidden" name="semcount'+id+'" id="semcount'+id+'" value="'+data.semcount+'">');
                            $('#icshowcount'+id).html(data.iccount+'<input type="hidden" name="iccount'+id+'" id="iccount'+id+'" value="'+data.iccount+'">');
                            $('#gcmsshowcount'+id).html(data.gcmscount+'<input type="hidden" name="gcmscount'+id+'" id="gcmscount'+id+'" value="'+data.gcmscount+'">');
                            $('#ftirshowcount'+id).html(data.ftircount+'<input type="hidden" name="ftircount'+id+'" id="ftircount'+id+'" value="'+data.ftircount+'">');
                        }
                    });
                }
            }

            function newexportexcel()
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
                    url: "{{ url('combinesexportall')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        console.log(data);
                        if (data.success == 1) {
                            swal.close();
                            window.location.href = "../local/storage/NHK-File/report/calculation/"+data.filename;
                        } 
                        if(data.success == 2) {
                            swal("warning", "Please selete model", "warning");
                        }
                        if(data.success == 3) {
                            swal("warning", "Please selete model", "warning");
                        }
                        if(data.success == 4) { 
                            swal("warning", "Please selete model", "warning");
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
                    url: "{{ url('lpccombineexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        console.log(data);
                        if (data.success == 1) {
                            swal.close();
                            window.location.href = "../local/storage/NHK-File/report/calculation/"+data.filename;
                        } 
                        else if(data.success == 2) {
                            swal("warning", "Please selete model", "warning");
                        }
                        else if(data.success == 3) {
                            swal("warning", "Please selete model", "warning");
                        }
                        else if(data.success == 4) { 
                            swal("warning", "Please selete model", "warning");
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
                    url: "{{ url('semcombineexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        console.log(data);
                        if (data.success == 1) {
                            swal.close();
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
                    url: "{{ url('iccombineexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        console.log(data);
                        if (data.success == 1) {
                            swal.close();
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
                    url: "{{ url('gcmscombinesexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        console.log(data);
                        if (data.success == 1) {
                            swal.close();
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
                    url: "{{ url('ftircombinesexport')}}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        console.log(data);
                        if (data.success == 1) {
                            swal.close();
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

            


            
            


</script>
@endsection