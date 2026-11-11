@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Results')
@section('breadcrumb-title', 'Results')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-sm-12 col-xl-4">
                        <label>Model</label>
                        <input type="text" name="textmodel" id="textmodel" class="form-control">
                    </div>
                    <div class="col-sm-12 col-xl-4">
                        <label>Lot number</label>
                        <input type="text" name="textlot" id="textlot" class="form-control">
                    </div>
                </div>
                <br>
                    <div class="row">
                        <div class="col-sm-12 col-xl-4">
                            <label>Start date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <label>End date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-4">
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
            <div class="card-header text-center">
                <label>Data Status :</label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-circle" style="color:limegreen;"></i> PASS</label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-circle" style="color:red;"></i> NOT PASS</label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-exclamation-triangle" style="color:gold;"></i> WAITING DATA</label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-minus" style="color:gray;"></i> NO TESTING</label>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table valign="bottom" id="dt" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <input type="hidden"  id="" name=""  value="">
                                <th class="text-center" rowspan="2">Model</th>
                                <th class="text-center" rowspan="2">Lot</th>
                                <th class="text-center" colspan="6">Data Status</th>
                                <th class="text-center" rowspan="2">Report</th>           
                            </tr>
                            <tr>
                                <th class="text-center">LPC</th>
                                <th class="text-center">SEM</th>
                                <th class="text-center">IC</th>
                                <th class="text-center">GCMS</th>
                                <th class="text-center">FTIR</th>
                                <th class="text-center">Ghost</th>
                            </tr>     
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Model Permission</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table  class="table">
                    <tr>
                        <th>MODEL</th>
                        <th>LPC</th>
                        <th>SEM</th>
                        <th>IC</th>
                        <th>GCMS</th>
                        <th>FTIR</th>
                        <th>Ghost</th>
                    </tr>
                    <tbody id="modelparaview">
                    </tbody>
                    {{-- <tr>
                        <td>1</td>
                        <td><a href="{{asset($filelpc)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif <font color='{{$lpc}}'><i class='{{$lpcicon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                        <td>@if($st->sem == 1 && $st->id_model_p == 1)<a href="{{asset($filesem)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif <font color='{{$sem}}'><i class='{{$semicon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                        <td>@if($st->ic == 1 && $st->id_model_p == 1)<a href="{{asset($fileic)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif <font color='{{$ic}}'><i class='{{$icicon}}'style="font-size:20px"  aria-hidden='true'></i></font></td>
                        <td>@if($st->gcms == 1 && $st->id_model_p == 1)<a href="{{asset($filegcms)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif  <font color='{{$gcms}}'><i class='{{$gcmsicon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                        <td>@if($st->ftir == 1 && $st->id_model_p == 1)<a href="{{asset($fileftir)}}" class="fa fa-file-archive-o" style="font-size:20px"></a> @endif  <font color='{{$ftir}}'><i class='{{$ftiricon}}' style="font-size:20px" aria-hidden='true'></i></font></td>
                    </tr> --}}
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" value="" id="ccpallchk">
@endsection
@section('scripts')
<script>
     $(document).ready(function () {
        var oTable = $('#dt').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            ajax: {
                "url": "{{url('Results/datatables')}}",
                "data": function (d) {
                    d.date_start = $("#start_date").val();
                    d.date_end = $("#end_date").val();
                    d.textmodel =  $("#textmodel").val();
                    d.textlot =  $("#textlot").val();
                }
            },
            columns: [{
                    className: "text-center",
                    data: 'name_model',
                    name: 'name_model'
                },
                {
                    className: "text-center",
                    data: 'lotnumber_lab',
                    name: 'lotnumber_lab'
                },
                {
                    className: "text-center",
                    data: 'part',
                    name: 'part'
                },
                {
                    className: "text-center",
                    data: 'type_lab',
                    name: 'type_lab'
                },
                {
                    className: "text-center",
                    data: 'name_lab',
                    name: 'name_lab'
                },
                {
                    className: "text-center",
                    data: 'code_lab',
                    name: 'code_lab'
                },
                {
                    className: "text-center",
                    data: 'lotnumber_lab',
                    name: 'lotnumber_lab'
                },
                {
                    className: "text-center",
                    data: 'id_createlabnew',
                    name: 'id_createlabnew'
                },   
                {
                    className: "text-center",
                    data: 'id_createlabnew',
                    name: 'id_createlabnew'
                },
            ],
            "order": [
                [8, 'desc']
            ],
            rowCallback: function (row, data, index) {
                
                var nodatalpc = '';
                var nodatasem = '';
                var nodataic = '';
                var nodatagcms = '';
                var nodataftir = '';
                var nodatasemtest = '';
                var lpc_confirm_st = data.lpc_confirm_st;
                var sem_confirm_st = data.sem_confirm_st;
                var ic_confirm_st = data.ic_confirm_st;
                var gcms_confirm_st = data.gcms_confirm_st;
                var ftir_confirm_st = data.ftir_confirm_st;
                var semtest_confirm_st = data.semtest_confirm_st;
                nodatalpc += '<p style="text-align:center"<i class="fa fa-minus fa-2x" style="color:gray;"></i> </p>';
                nodatasem += '<p style="text-align:center"<i class="fa fa-minus fa-2x" style="color:gray;"></i> </p>';
                nodataic += '<p style="text-align:center"<i class="fa fa-minus fa-2x" style="color:gray;"></i> </p>';
                nodatagcms += '<p style="text-align:center"<i class="fa fa-minus fa-2x" style="color:gray;"></i> </p>';
                nodataftir += '<p style="text-align:center"<i class="fa fa-minus fa-2x" style="color:gray;"></i> </p>';
                nodatasemtest += '<p style="text-align:center"<i class="fa fa-minus fa-2x" style="color:gray;"></i> </p>';

                nodatalpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL / LCL</button></p>';
                nodatalpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodatalpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodatalpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';

                nodatasem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodatasem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodatasem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodatasem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';

                nodataic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodataic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodataic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodataic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                
                nodatagcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodatagcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodatagcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodatagcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';

                nodataftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodataftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodataftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodataftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';

                nodatasemtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodatasemtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodatasemtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodatasemtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';

                $('td:eq(2)', row).html(nodatalpc);
                $('td:eq(3)', row).html(nodatasem);
                $('td:eq(4)', row).html(nodataic);
                $('td:eq(5)', row).html(nodatagcms);
                $('td:eq(6)', row).html(nodataftir);
                $('td:eq(7)', row).html(nodatasemtest);
                //console.log(data.id_createlabnew);
                var viewdata = '';
                $.ajax({
                    type: 'get',
                    url: "{{ url('outputgraph') }}",
                    data: {
                        id: data.id_model,
                        idcreatelab: data.id_createlabnew,
                        chkpdf:0,
                    },
                    success: function (data) {
                        var ccplpc = 0;
                var ccpsem = 0;
                var ccpic = 0;
                var ccpgcms = 0;
                var ccpftir = 0;
                var ccpsemtest = 0;
                var ccpallchk = 0;
                        if(data.oqalot.st_lpc == 1)
                        {
                            var nodatalpc = '';
                            nodatalpc += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x"></i> </p>';
                            nodatalpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL / LCL</button></p>';
                            nodatalpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodatalpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodatalpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            $('td:eq(2)', row).html(nodatalpc);
                        }
                        if(data.oqalot.st_sem == 1)
                        {
                            var nodatasem = '';
                            nodatasem += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodatasem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodatasem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodatasem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodatasem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            $('td:eq(3)', row).html(nodatasem);
                        }
                        if(data.oqalot.st_ic == 1)
                        {
                            var nodataic = '';
                            nodataic += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';      
                            nodataic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodataic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodataic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodataic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            $('td:eq(4)', row).html(nodataic);
                        }
                        if(data.oqalot.st_gcms == 1)
                        {
                            var nodatagcms ='';
                            nodatagcms += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodatagcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodatagcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodatagcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodatagcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            $('td:eq(5)', row).html(nodatagcms);
                        }
                        if(data.oqalot.st_ftir == 1)
                        {
                            var nodataftir = '';
                            nodataftir += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodataftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodataftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodataftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodataftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            $('td:eq(6)', row).html(nodataftir);
                        }
                        if(data.oqalot.st_semtest == 1)
                        {
                            var nodatasemtest = '';
                            nodatasemtest += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodatasemtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodatasemtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodatasemtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodatasemtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            $('td:eq(7)', row).html(nodatasemtest);
                        }
                        
            
                
                        if(lpc_confirm_st == 1)
                        {
                        //-------------------------------------lpc ------------------------------//
                        var itemdata = data.datalab.length - 1;
                        data.datalab.reverse();
                        var lpc = '';
                        var lpctxtallshow = '';
                        var lpctxt = '';
                        var lpctxtshow = '';
                        var lpcucl = '';
                        var lpcitn = '';
                        var errorlpc = 0;
                        var errorlpc_ucl = 0;
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.higher >= data.alarmtest2) {
                            errorlpc++;
                        } else if (data.below >= data.alarmtest2) {
                            errorlpc++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.highervs3 >= data.alarmtest3) {
                            errorlpc++;
                        } else if (data.belowvs3 >= data.alarmtest3) {
                            errorlpc++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.datalab[itemdata].lpc_data < data.lcl.alpc) {
                            if(data.lcl.alpc != "N/A")
                            {
                            lpcucl = 'btn-danger';
                            errorlpc++;
                            }

                        } else if (data.datalab[itemdata].lpc_data > data.ucl.alpc) {
                            if(data.ucl.alpc != "N/A")
                            {
                            lpcucl = 'btn-danger';
                            errorlpc++;
                            errorlpc_ucl++;
                            }

                        } else {
                            lpcucl = 'btn-secondary';
                        }

                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.datalab[itemdata].lpc_data > data.is.alpc) {
                            if(data.is.alpc != "N/A")
                            {
                            lpcitn = 'btn-danger';
                            errorlpc++;
                            }
                        } else {
                            lpcitn = 'btn-secondary';
                        }

                        if ((data.slopelpc >= 0.5 || data.slopelpc <= -0.5) || data.dfflpc >= 3.5) {
                            errorlpc++;
                        }
                        var btdisable = '';
                        
                        if (errorlpc > 0) {
                            lpctxtallshow = 'style="color:#E71D00;"';
                            lpctxt = 'btn-secondary';
                            lpctxtshow = 'CCP';
                            btdisable ='';
                        } else {
                            lpctxtallshow = 'style="color:#00CC33;"';
                            lpctxt = 'btn-primary';
                            ccplpc = 1;
                            btdisable = 'onclick="ccpexcel('+data.datalab[itemdata].id_createlab+');"';
                            ccpallchk++;
                            if(data.datalabdone.lpcstatus_done == 0)
                            {
                            lpctxtshow = 'Ready CCP';
                            lpctxt = 'btn-primary';
                            }
                            else
                            {
                                lpctxtshow = 'Done CCP';
                                lpctxt = 'btn-success';
                            }
                        }
                        lpc += '<p style="text-align:center"> <input type="hidden"  id="chkalllpc'+data.datalab[itemdata].id_createlab+'" name="chkalllpc'+data.datalab[itemdata].id_createlab+'"  value="'+ccplpc+'"><i class="fa fa-circle fa-2x" ' + lpctxtallshow + '></i></p>';
                        lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpcucl + ' btn-mini" >UCL / LCL</button></p>';
                        lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpcitn + ' btn-mini">Internal</button></p>';
                        lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatalpc(' +data.datalab[itemdata].id_createlab + ',' + errorlpc_ucl + ');">Edit</button></p>';
                        lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpctxt + ' btn-mini" '+btdisable+'>' + lpctxtshow + '</button></p>';
                        //lpc += ' <button id="chk" class="btn btn-success"><i class="fa fa-search"></i> Search</button>';
                        $('td:eq(2)', row).html(lpc);
                        //-------------------------------------end lpc ------------------------------//
                        }
                        if(sem_confirm_st == 1)
                        {
                        //-------------------------------------sem ------------------------------//
                        var itemdatasem = data.semdatalab.length - 1;
                        data.semdatalab.reverse();
                        var sem = '';
                        var semtxtallshow = '';
                        var semtxt = '';
                        var semtxtshow = '';
                        var semucl = '';
                        var semitn = '';
                        var errorsem = 0;
                        var errorsem_ucl = 0;
                        var errorsem_itn = 0;
                         // -------------------------  class1 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem1higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem1below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem1highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem1belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class1	 < data.lcl.class1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class1	 > data.ucl.class1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class1 > data.is.class1) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem1higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem1below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem1highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem1belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class1	 < data.lcl.class1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class1	 > data.ucl.class1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class1 > data.is.class1) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                        // -------------------------TEST5 -------------------------------------//
                        if (data.slopesem1 >= 0.5 || data.slopesem1 <= -0.5 || data.dffsem1 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class1 -------------------------------------//

                        //   -------------------------  class2 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem2higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem2below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem2highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem2belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class2	 < data.lcl.class2) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class2	 > data.ucl.class2) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class2 > data.is.class2) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem2higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem2below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem2highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem2belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class2	 < data.lcl.class2) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class2	 > data.ucl.class2) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class2 > data.is.class2) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                            // -------------------------TEST5 -------------------------------------//
                            if (data.slopesem2 >= 0.5 || data.slopesem2 <= -0.5 || data.dffsem2 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class2 -------------------------------------//

                        //   -------------------------  class3 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem3higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem3below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem3highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem3belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3	 < data.lcl.class3) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class3	 > data.ucl.class3) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3 > data.is.class3) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem3higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem3below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem3highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem3belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3	 < data.lcl.class3) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class3	 > data.ucl.class3) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3 > data.is.class3) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                            // -------------------------TEST5 -------------------------------------//
                            if (data.slopesem3 >= 0.5 || data.slopesem3 <= -0.5 || data.dffsem3 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class3 -------------------------------------//


                               

                        //   -------------------------  class3.1 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem31higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem31below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem31highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem31belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_1	 < data.lcl.class3_1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class3_1	 > data.ucl.class3_1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_1 > data.is.class3_1) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem31higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem31below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem31highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem31belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_1	 < data.lcl.class3_1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class3_1	 > data.ucl.class3_1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_1 > data.is.class3_1) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                                // -------------------------TEST5 -------------------------------------//
                                if (data.slopesem3_1 >= 0.5 || data.slopesem3_1 <= -0.5 || data.dffsem3_1 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class3.1 -------------------------------------//

                         //  -------------------------  class3.2 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem32higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem32below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem32highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem32belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_2	 < data.lcl.class3_2) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class3_2	 > data.ucl.class3_2) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_2 > data.is.class3_2) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem32higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem32below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem32highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem32belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_2	 < data.lcl.class3_2) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class3_2	 > data.ucl.class3_2) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_2 > data.is.class3_2) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                               // -------------------------TEST5 -------------------------------------//
                               if (data.slopesem3_2 >= 0.5 || data.slopesem3_2 <= -0.5 || data.dffsem3_2 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class3.2 -------------------------------------//


                         //   -------------------------  class3.3 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem33higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem33below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem33highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem33belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_3	 < data.lcl.class3_3) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class3_3	 > data.ucl.class3_3) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_3 > data.is.class3_3) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem33higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem33below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem33highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem33belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_3	 < data.lcl.class3_3) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class3_3	 > data.ucl.class3_3) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class3_3 > data.is.class3_3) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//

                        if (data.slopesem3_3 >= 0.5 || data.slopesem3_3 <= -0.5 || data.dffsem3_3 >= 3.5) {
                            errorsem++;
                        }
                               // -------------------------TEST5 -------------------------------------//
                               if (data.slopesem3_3 >= 0.5 || data.slopesem3_3 <= -0.5 || data.dffsem3_3 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class3.3 -------------------------------------//


                        //   -------------------------  class4 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem4higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem4below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem4highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem4belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class4	 < data.lcl.class4) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class4	 > data.ucl.class4) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class4 > data.is.class4) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem4higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem4below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem4highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem4belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class4	 < data.lcl.class4) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class4	 > data.ucl.class4) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class4 > data.is.class4) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                               // -------------------------TEST5 -------------------------------------//
                               if (data.slopesem4 >= 0.5 || data.slopesem4 <= -0.5 || data.dffsem4 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class4 -------------------------------------//


                         //   -------------------------  class5 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem5higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem5below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem5highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem5belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class5	 < data.lcl.class5) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class5	 > data.ucl.class5) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class5 > data.is.class5) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem5higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem5below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem5highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem5belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class5	 < data.lcl.class5) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class5	 > data.ucl.class5) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class5 > data.is.class5) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                          // -------------------------TEST5 -------------------------------------//
                          if (data.slopesem5 >= 0.5 || data.slopesem5 <= -0.5 || data.dffsem5 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class5 -------------------------------------//


                         //   -------------------------  class5.1 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem51higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem51below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem51highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem51belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class5_1	 < data.lcl.class5_1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class5_1	 > data.ucl.class5_1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class5_1 > data.is.class5_1) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem51higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem51below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem51highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem51belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class5_1	 < data.lcl.class5_1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class5_1	 > data.ucl.class5_1) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class5_1 > data.is.class5_1) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                          // -------------------------TEST5 -------------------------------------//
                          if (data.slopesem5_1 >= 0.5 || data.slopesem5_1 <= -0.5 || data.dffsem5_1 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class5.1 -------------------------------------//


                        // -------------------------  class6 -------------------------------------//
                        // -------------------------  TEST2 -------------------------------------//
                        if (data.sem6higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem6below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem6highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem6belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class6	 < data.lcl.class6) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class6	 > data.ucl.class6) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class6 > data.is.class6) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        if (data.sem6higher >= data.alarmtest2) {
                            errorsem++;
                        } else if (data.sem6below >= data.alarmtest2) {
                            errorsem++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem6highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem6belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class6	 < data.lcl.class6) {
                            errorsem_ucl++;
                            errorsem++;

                        } else if (data.semdatalab[itemdatasem].sem_class6	 > data.ucl.class6) {
                            errorsem_ucl++;
                            errorsem++;

                        } else {}
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.semdatalab[itemdatasem].sem_class6 > data.is.class6) {
                            errorsem_itn++;
                            errorsem++;
                        } else {}
                        // -------------------------end TEST4 -------------------------------------//
                           // -------------------------TEST5 -------------------------------------//
                           if (data.slopesem6 >= 0.5 || data.slopesem6 <= -0.5 || data.dffsem6 >= 3.5) {
                            errorsem++;
                        }
                        // -------------------------end TEST5 -------------------------------------//
                        // -------------------------  end class6 -------------------------------------//

                        if(errorsem_ucl > 0)
                        {
                            semucl = 'btn-danger';
                        }
                        else
                        {
                            semucl = 'btn-secondary';
                        }
                        if(errorsem_itn > 0)
                        {
                            semitn = 'btn-danger';
                        }
                        else
                        {
                            semitn = 'btn-secondary';
                        }
                         var btdisable ='';
                        if (errorsem > 0) {
                            semtxtallshow = 'style="color:#E71D00;"';
                            semtxt = 'btn-secondary';
                            semtxtshow = 'CCP';
                            btdisable ='';
                        } else {
                            ccpsem = 1;
                            semtxtallshow = 'style="color:#00CC33;"';
                            semtxt = 'btn-primary';
                            btdisable = 'onclick="ccpexcelsem('+data.semdatalab[itemdatasem].id_createlab+');"';
                            ccpallchk++;
                            if(data.datalabdone.semstatus_done == 0)
                            {
                                semtxtshow = 'Ready CCP';
                                semtxt = 'btn-primary';
                            }
                            else
                            {
                                semtxtshow = 'Done CCP';
                                semtxt = 'btn-success';
                            }
                        }
                        sem += '<p style="text-align:center"><input type="hidden"  id="chkallsem'+data.semdatalab[itemdatasem].id_createlab+'" name="chkallsem'+data.semdatalab[itemdatasem].id_createlab+'"  value="'+ccpsem+'"><i class="fa fa-circle fa-2x" ' + semtxtallshow + '></i></p>';
                        sem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + semucl + ' btn-mini" >UCL / LCL</button></p>';
                        sem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + semitn + ' btn-mini">Internal</button></p>';
                        sem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatasem(' + data.semdatalab[itemdatasem].id_createlab + ',' + errorsem + ');">Edit</button></p>';
                        sem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + semtxt + ' btn-mini" '+btdisable+'">' + semtxtshow + '</button></p>';
                        $('td:eq(3)', row).html(sem);
                        //-------------------------------------end sem ------------------------------//
                        }
                        if(ic_confirm_st == 1)
                        {
                        //-------------------------------------IC ------------------------------//
                        var icitemdata = data.icdatalab.length - 1;
                        data.icdatalab.reverse();
                       
                        var ic = '';
                        var ictxtallshow = '';
                        var ictxt = '';
                        var ictxtshow = '';
                        var icucl = '';
                        var icitn = '';
                        var erroric = 0;
                        var erroric_ucl = 0;
                        var erroric_itn =0;
                        // ------------------------- anion -------------------------------------//
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.icanionhigher >= data.alarmtest2) {
                            erroric++;
                        } else if (data.icanionbelow >= data.alarmtest2) {
                            erroric++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.icanionhighervs3 >= data.alarmtest3) {
                            erroric++;
                        } else if (data.icanionbelowvs3 >= data.alarmtest3) {
                            erroric++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.icdatalab[icitemdata].ic_anion_up < data.lcl.anion) {
                            
                            icucl = 'btn-danger';
                            erroric_ucl++;
                            erroric++;

                        } else if (data.icdatalab[icitemdata].ic_anion_up > data.ucl.anion) {

                            icucl = 'btn-danger';
                            erroric_ucl++;
                            erroric++;

                        } else {
                            icucl = 'btn-secondary';
                            // alert(0);
                        }
                        
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.icdatalab[icitemdata].ic_anion_up > data.is.anion) {
                            icitn = 'btn-danger';
                            erroric++;
                        } else {
                            icitn = 'btn-secondary';
                        }
                        // -------------------------END TEST4 -------------------------------------//
                        if (data.slopeic_anion >= 0.5 || data.slopeic_anion <= -0.5 || data.dffic_anion >= 3.5) {
                            erroric++;
                }
                         
                         // -------------------------end anion -------------------------------------//


                        // ------------------------- sulphate -------------------------------------//
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.sulphathigher >= data.alarmtest2) {
                            erroric++;
                        } else if (data.sulphatbelow >= data.alarmtest2) {
                            erroric++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sulphathighervs3 >= data.alarmtest3) {
                            erroric++;
                        } else if (data.sulphatbelowvs3 >= data.alarmtest3) {
                            erroric++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.icdatalab[icitemdata].ic_sulphate < data.lcl.sulphate) {
                            icucl = 'btn-danger';
                            erroric_ucl++;
                            erroric++;

                        } else if (data.icdatalab[icitemdata].ic_sulphate > data.ucl.sulphate) {

                            icucl = 'btn-danger';
                            erroric_ucl++;
                            erroric++;

                        } else {
                            icucl = 'btn-secondary';
                        }
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.icdatalab[icitemdata].ic_sulphate > data.is.sulphate) {
                            icitn = 'btn-danger';
                            erroric++;
                        } else {
                            icitn = 'btn-secondary';
                        }
                        // -------------------------END TEST4 -------------------------------------//
                        
                        if (data.slopeic_sulphate >= 0.5 || data.slopeic_sulphate <= -0.5 || data.dffic_sulphate >= 3.5) {
                            erroric++;
                }
                         // -------------------------end sulphate -------------------------------------//

                           // ------------------------- cation -------------------------------------//
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.cationhigher >= data.alarmtest2) {
                            erroric++;
                        } else if (data.cationbelow >= data.alarmtest2) {
                            erroric++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.cationhighervs3 >= data.alarmtest3) {
                            erroric++;
                        } else if (data.cationbelowvs3 >= data.alarmtest3) {
                            erroric++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.icdatalab[icitemdata].ic_cation_up < data.lcl.cation) {
                            icucl = 'btn-danger';
                            erroric_ucl++;
                            erroric++;

                        } else if (data.icdatalab[icitemdata].ic_cation_up > data.ucl.cation) {

                            icucl = 'btn-danger';
                            erroric_ucl++;
                            erroric++;

                        } else {
                            icucl = 'btn-secondary';
                            // alert(0);
                        }
                       
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.icdatalab[icitemdata].ic_cation_up > data.is.cation) {
                            icitn = 'btn-danger';
                            erroric++;
                        } else {
                            icitn = 'btn-secondary';
                        }
                        // -------------------------END TEST4 -------------------------------------//
                        if (data.slopeic_cation >= 0.5 || data.slopeic_cation <= -0.5 || data.dffic_cation >= 3.5) {
                            erroric++;
                }
                         // -------------------------end cation -------------------------------------//
                        if(erroric_ucl > 0)
                        {
                            icucl = 'btn-danger';
                        }
                        else
                        {
                            icucl = 'btn-secondary';
                        }
                        if(erroric_itn > 0)
                        {
                            icitn = 'btn-danger';
                        }
                        else
                        {
                            icitn = 'btn-secondary';
                        }
                        var btdisable = '';
                        if (erroric > 0) {
                            ictxtallshow = 'style="color:#E71D00;"';
                            ictxt = 'btn-secondary';
                            ictxtshow = 'CCP';
                            btdisable ='';
                        } else {
                            ccpic = 1;
                            ictxtallshow = 'style="color:#00CC33;"';
                            ictxt = 'btn-primary';
                            btdisable = 'onclick="ccpexcelic('+data.icdatalab[icitemdata].id_createlab+');"';
                            ccpallchk++;
                            if(data.datalabdone.icstatus_done == 0)
                            {
                                ictxtshow = 'Ready CCP';
                                ictxt = 'btn-primary';
                            }
                            else
                            {
                                ictxtshow = 'Done CCP';
                                ictxt = 'btn-success';
                            }
                        }
                        ic += '<p style="text-align:center"><input type="hidden"  id="chkallic'+data.icdatalab[icitemdata].id_createlab+'" name="chkallic'+data.icdatalab[icitemdata].id_createlab+'"  value="'+ccpic+'"><i class="fa fa-circle fa-2x" ' + ictxtallshow + '></i></p>';
                        ic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + icucl + ' btn-mini" >UCL / LCL</button></p>';
                        ic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + icitn + ' btn-mini">Internal</button></p>';
                        ic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDataic(' + data.icdatalab[icitemdata].id_createlab + ',' + erroric + ');">Edit</button></p>';
                        ic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + ictxt + ' btn-mini" '+btdisable+')">' + ictxtshow + '</button></p>';
                        $('td:eq(4)', row).html(ic);
                        //-------------------------------------end IC ------------------------------//
                        }
                        if(gcms_confirm_st == 1)
                        {
                        //-------------------------------------GCMS ------------------------------//
                        var gcmsitemdata = data.gcmsdatalab.length - 1;
                        data.gcmsdatalab.reverse();
                        var itemdata1 = data.newgcmsdatalab.length-1;
                        data.newgcmsdatalab.reverse();
                        var gcms = '';
                        var gcmstxtallshow = '';
                        var gcmstxt = '';
                        var gcmstxtshow = '';
                        var gcmsucl = '';
                        var gcmsitn = '';
                        var errorgcms = 0;
                        var errorgcms_ucl = 0;
                        var errorgcms_itn =0;
                        var datalcloutgassing =  data.newgcmsdatalab[itemdata1][2].outgassing;
                        var dataucloutgassing =  data.newgcmsdatalab[itemdata1][1].outgassing;
                        // ------------------------- gcms1 -------------------------------------//
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.gcms1higher >= data.alarmtest2) {
                            errorgcms++;
                        } else if (data.gcms1below >= data.alarmtest2) {
                            errorgcms++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.gcms1highervs3 >= data.alarmtest3) {
                            errorgcms++;
                        } else if (data.gcms1belowvs3 >= data.alarmtest3) {
                            errorgcms++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.gcmsdatalab[gcmsitemdata].gcms_outgassing < datalcloutgassing) {
                            gcmsucl = 'btn-danger';
                            errorgcms++;

                        } else if (data.gcmsdatalab[gcmsitemdata].gcms_outgassing > dataucloutgassing) {

                            gcmsucl = 'btn-danger';
                            errorgcms++;

                        } else {
                            gcmsucl = 'btn-secondary';
                            // alert(0);
                        }
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.gcmsdatalab[gcmsitemdata].gcms_outgassing > data.is.outgassing) {
                            gcmsitn = 'btn-danger';
                            errorgcms++;
                        } else {
                            gcmsitn = 'btn-secondary';
                        }
                        // -------------------------END TEST4 -------------------------------------//
                        if (data.slopegcms_out >= 0.5 || data.slopegcms_out <= -0.5 ||  data.dffgcms_out >= 3.5) {
                            errorgcms++;
                }

                var lowhigh = parseFloat(data.gcmsdatalab[gcmsitemdata].gcms_low_mw) + parseFloat(data.gcmsdatalab[gcmsitemdata].gcms_high_mw); 
                    var outgassingtest6 = parseFloat(data.gcmsdatalab[gcmsitemdata].gcms_outgassing) - parseFloat(lowhigh);
                   // alert(outgassingtest6);
                    if (outgassingtest6 > 20 || outgassingtest6 < -20) {
                        //sumalertall++:
                        errorgcms++;
                    }
                    else
                    {
                        gcms1_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                         // -------------------------end gcms1 -------------------------------------//

                           // ------------------------- gcms2 -------------------------------------//
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.gcms2higher >= data.alarmtest2) {
                            errorgcms++;
                        } else if (data.gcmsb2elow >= data.alarmtest2) {
                            errorgcms++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.gcms2highervs3 >= data.alarmtest3) {
                            errorgcms++;
                        } else if (data.gcms2belowvs3 >= data.alarmtest3) {
                            errorgcms++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        var datalclalkylacrylate =  data.newgcmsdatalab[itemdata1][2].alkylacrylate;
                        var datauclalkylacrylate =  data.newgcmsdatalab[itemdata1][1].alkylacrylate;
                        if (data.gcmsdatalab[gcmsitemdata].gcms_smear < datalclalkylacrylate) {
                            gcmsucl = 'btn-danger';
                            errorgcms++;

                        } else if (data.gcmsdatalab[gcmsitemdata].gcms_smear > datauclalkylacrylate) {

                            gcmsucl = 'btn-danger';
                            errorgcms++;

                        } else {
                            gcmsucl = 'btn-secondary';
                            // alert(0);
                        }
            
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.gcmsdatalab[gcmsitemdata].gcms_smear > data.is.alkylacrylate) {
                            gcmsitn = 'btn-danger';
                            errorgcms++;
                        } else {
                            gcmsitn = 'btn-secondary';
                        }
                        // -------------------------END TEST4 -------------------------------------//
                        if (data.slopegcms_smear >= 0.5 || data.slopegcms_smear <= -0.5 || data.dffgcms_smear >= 3.5) {
                            errorgcms++;
                }
                         // -------------------------end gcms2 -------------------------------------//


                        // ------------------------- gcms3 -------------------------------------//
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.gcms3higher >= data.alarmtest2) {
                            errorgcms++;
                        } else if (data.gcmsb3elow >= data.alarmtest2) {
                            errorgcms++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.gcms3highervs3 >= data.alarmtest3) {
                            errorgcms++;
                        } else if (data.gcms3belowvs3 >= data.alarmtest3) {
                            errorgcms++;
                        } else {}
                        
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        var datalcllow_MW =  data.newgcmsdatalab[itemdata1][2].low_MW;
                var dataucllow_MW =  data.newgcmsdatalab[itemdata1][1].low_MW;
                        if (data.gcmsdatalab[gcmsitemdata].gcms_low_mw < datalcllow_MW) {
                            gcmsucl = 'btn-danger';
                            errorgcms++;

                        } else if (data.gcmsdatalab[gcmsitemdata].gcms_low_mw > dataucllow_MW) {

                            gcmsucl = 'btn-danger';
                            errorgcms++;

                        } else {
                            gcmsucl = 'btn-secondary';
                            // alert(0);
                        }
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.gcmsdatalab[gcmsitemdata].gcms_low_mw > data.is.low_MW) {
                            gcmsitn = 'btn-danger';
                            errorgcms++;
                        } else {
                            gcmsitn = 'btn-secondary';
                        }
                        // -------------------------END TEST4 -------------------------------------//
                        if (data.slopegcms_low_mw >= 0.5 || data.slopegcms_low_mw <= -0.5 || data.dffgcms_low_mw >= 3.5) {
                            errorgcms++;
                }
                        // -------------------------end gcms3 -------------------------------------//

                        // ------------------------- gcms4 -------------------------------------//
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.gcms4higher >= data.alarmtest2) {
                            errorgcms++;
                        } else if (data.gcmsb4elow >= data.alarmtest2) {
                            errorgcms++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.gcms4highervs3 >= data.alarmtest3) {
                            errorgcms++;
                        } else if (data.gcms4belowvs3 >= data.alarmtest3) {
                            errorgcms++;
                        } else {}
                        
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        var datalclhigh_MW =  data.newgcmsdatalab[itemdata1][2].high_MW;
                var datauclhigh_MW =  data.newgcmsdatalab[itemdata1][1].high_MW;
                        if (data.gcmsdatalab[gcmsitemdata].gcms_high_mw < datalclhigh_MW) {
                            gcmsucl = 'btn-danger';
                            errorgcms++;

                        } else if (data.gcmsdatalab[gcmsitemdata].gcms_high_mw > datauclhigh_MW) {

                            gcmsucl = 'btn-danger';
                            errorgcms++;

                        } else {
                            gcmsucl = 'btn-secondary';
                            // alert(0);
                        }
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.gcmsdatalab[gcmsitemdata].gcms_high_mw > data.is.high_MW) {
                            gcmsitn = 'btn-danger';
                            errorgcms++;
                        } else {
                            gcmsitn = 'btn-secondary';
                        }
                        // -------------------------END TEST4 -------------------------------------//
                        if (data.slopegcms_high_mw >= 0.5 || data.slopegcms_high_mw <= -0.5 || data.dffgcms_high_mw >= 3.5) {
                            errorgcms++;
                }
                         // -------------------------end gcms4 -------------------------------------//


                        if(errorgcms_ucl > 0)
                        {
                            gcmsucl = 'btn-danger';
                        }
                        else
                        {
                            gcmsucl = 'btn-secondary';
                        }
                        if(errorgcms_itn > 0)
                        {
                            gcmsitn = 'btn-danger';
                        }
                        else
                        {
                            gcmsitn = 'btn-secondary';
                        }
                       var btdisable = '';
                        if (errorgcms > 0) {
                            gcmstxtallshow = 'style="color:#E71D00;"';
                            gcmstxt = 'btn-secondary';
                            gcmstxtshow = 'CCP';
                            btdisable = '';
                        } else {
                            gcmstxtallshow = 'style="color:#00CC33;"';
                            ccpgcms = 1;  
                            btdisable = 'onclick="ccpexcelgcms('+data.gcmsdatalab[gcmsitemdata].id_createlab+');"';
                            ccpallchk++;
                            if(data.datalabdone.gcmsstatus_done == 0)
                            {
                                gcmstxtshow = 'Ready CCP';
                                gcmstxt = 'btn-primary';
                            }
                            else
                            {
                                gcmstxtshow = 'Done CCP';
                                gcmstxt = 'btn-success';
                            }
                        }
                        gcms += '<p style="text-align:center"><input type="hidden"  id="chkallgcms'+data.gcmsdatalab[gcmsitemdata].id_createlab+'" name="chkallgcms'+data.gcmsdatalab[gcmsitemdata].id_createlab+'"  value="'+ccpgcms+'"><i class="fa fa-circle fa-2x" ' + gcmstxtallshow + '></i></p>';
                        gcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + gcmsucl + ' btn-mini" >UCL / LCL</button></p>';
                        gcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + gcmsitn + ' btn-mini">Internal</button></p>';
                        gcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatagcms(' + data.gcmsdatalab[gcmsitemdata].id_createlab + ',' + errorgcms + ');">Edit</button></p>';
                        gcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + gcmstxt + ' btn-mini"  '+btdisable+'>' + gcmstxtshow + '</button></p>';
                        $('td:eq(5)', row).html(gcms);
                        //-------------------------------------end GCMS ------------------------------//
                        }$  
                        if(ftir_confirm_st == 1)
                        {
                         //-------------------------------------FTIR ------------------------------//
                         var ftiritemdata = data.ftirdatalab.length - 1;
                        data.ftirdatalab.reverse();
                        var ftir = '';
                        var ftirtxtallshow = '';
                        var ftirtxt = '';
                        var ftirtxtshow = '';
                        var ftirucl = '';
                        var ftiritn = '';
                        var errorftir = 0;
                        var errorftir_ucl = 0;
                        // ------------------------- TEST2 -------------------------------------//
                        if (data.ftirhigher >= data.alarmtest2) {
                            errorftir++;
                        } else if (data.ftirbelow >= data.alarmtest2) {
                            errorftir++;
                        } else {}
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.ftirhighervs3 >= data.alarmtest3) {
                            errorftir++;
                        } else if (data.ftirbelowvs3 >= data.alarmtest3) {
                            errorftir++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if (data.ftirdatalab[ftiritemdata].ftir_silicone < data.lcl.ftir) {
                            ftirucl = 'btn-danger';
                            errorftir++;

                        } else if (data.ftirdatalab[ftiritemdata].ftir_silicone > data.ucl.ftir) {

                            ftirucl = 'btn-danger';
                            errorftir++;

                        } else {
                            ftirucl = 'btn-secondary';
                            // alert(0);
                        }
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if (data.ftirdatalab[ftiritemdata].ftir_silicone > data.is.ftir) {
                            ftiritn = 'btn-danger';
                            errorftir++;
                        } else {
                            ftiritn = 'btn-secondary';
                        }

                        if (data.slopeftir_sc >= 0.5 || data.slopeftir_sc <= -0.5 || data.dffftir_sc >= 3.5) {
                            errorftir++;
                }
                        var btdisable ='';
                        if (errorftir > 0) {
                            ftirtxtallshow = 'style="color:#E71D00;"';
                            ftirtxt = 'btn-secondary';
                            ftirtxtshow = 'CCP';
                            btdisable ='';
                        } else {
                            ftirtxtallshow = 'style="color:#00CC33;"';
                            ftirtxt = 'btn-primary';
                            ccpftir = 1;
                            btdisable = 'onclick="ccpexcelftir('+data.ftirdatalab[ftiritemdata].id_createlab+');"';
                            ccpallchk++;
                            if(data.datalabdone.ftirstatus_done == 0)
                            {
                                ftirtxtshow = 'Ready CCP';
                                ftirtxt = 'btn-primary';
                            }
                            else
                            {
                                ftirtxtshow = 'Done CCP';
                                ftirtxt = 'btn-success';
                            }
                        }
                        ftir += '<p style="text-align:center"><input type="hidden"  id="chkallftir'+data.ftirdatalab[ftiritemdata].id_createlab+'" name="chkallftir'+data.ftirdatalab[ftiritemdata].id_createlab+'"  value="'+ccpftir+'"><i class="fa fa-circle fa-2x" ' + ftirtxtallshow + '></i></p>';
                        ftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + ftirucl + ' btn-mini" >UCL / LCL</button></p>';
                        ftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + ftiritn + ' btn-mini">Internal</button></p>';
                        ftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDataftir(' + data.ftirdatalab[ftiritemdata].id_createlab + ',' + errorftir + ');">Edit</button></p>';
                        ftir += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + ftirtxt + ' btn-mini"  '+btdisable+'">' + ftirtxtshow + '</button></p>';
                        $('td:eq(6)', row).html(ftir);
                       
                        
                        //-------------------------------------end FTIR ------------------------------//
                        }

                        if(semtest_confirm_st == 1)
                        {
                            var semtestxtallshow = '';
                            var semtesttxt = '';
                            var semtesttxtshow = '';
                            var btdisable = '';
                            var errorsemtest =0;
                            if(data.semtestdatalab.semtest_data == 1 || data.semtestdatalab.semtest_data == 1.00)
                            {
                                semtestxtallshow = 'style="color:#00CC33;"';
                                ccpsemtest = 1;
                                btdisable = 'onclick="ccpexcelghost('+data.semtestdatalab.id_createlab+');"';
                                if(data.datalabdone.semteststatus_done == 0)
                                 {
                                semtesttxtshow = 'Ready CCP';
                                semtesttxt = 'btn-primary';
                                 }
                                else
                                {
                                semtesttxtshow = 'Done CCP';
                                semtesttxt = 'btn-success';
                                }
                            } 
                            else
                            {
                                
                                semtestxtallshow = 'style="color:#E71D00;"';
                                semtesttxt = 'btn-secondary';
                                semtesttxtshow = 'CCP';
                                btdisable ='';
                                errorsemtest++;
                            }





                           var semtest = '';
                            semtest += '<p style="text-align:center"><input type="hidden"  id="chkallsemtest'+data.semtestdatalab.id_createlab+'" name="chkallsemtest'+data.semtestdatalab.id_createlab+'"  value="'+ccpsemtest+'"><i class="fa fa-circle fa-2x" ' + semtestxtallshow + '></i></p>';
                            semtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini" >UCL / LCL</button></p>';
                            semtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-secondary btn-mini">Internal</button></p>';
                            semtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatasemtest(' + data.semtestdatalab.id_createlab + ',' + errorsemtest + ');">Edit</button></p>';
                            semtest += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + semtesttxt + ' btn-mini"  '+btdisable+'">' + semtesttxtshow + '</button></p>';
                        $('td:eq(7)', row).html(semtest);
                        }
                
                    }

                   
                });
                // viewdata += '<p style="text-align:center"><a onclick="viewpara('+data.id_createlabnew+')"> <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#large-Modal">&nbsp;<i class="fa fa-search"></i>&nbsp;VIEW&nbsp;&nbsp;</button></a></p>'+
                // '<p style="text-align:center"><a href="../output/viewdata/' + data.id_createlabnew + '"><button class="btn btn-danger btn-sm">&nbsp;<i class="fa fa-line-chart"></i>GRAPH</button></a></p>'+
                // '<p style="text-align:center"><a onclick="ccpexcelall('+data.id_createlabnew+','+ccplpc+','+ccpsem+','+ccpic+','+ccpgcms+','+ccpftir+')"><button type="button"class="btn btn-success btn-sm">&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;CCP&nbsp;&nbsp;&nbsp;</button></a></p>';

            //    var allbtdisable = '';
            //     if($('#ccpallchk').val() > 0)
            //     {
            //         allbtdisable = 'onclick="ccpexcelall('+data.id_createlabnew+');"';
            //     }
            //     else
            //     {
            //         allbtdisable ='';
            //     }
                allbtdisable = 'onclick="ccpexcelall('+data.id_createlabnew+');"';
                viewdata += '<p style="text-align:center"><a onclick="viewpara('+data.id_createlabnew+')"> <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#large-Modal">&nbsp;<i class="fa fa-search"></i>&nbsp;VIEW&nbsp;&nbsp;</button></a></p>'+
                '<p style="text-align:center"><a href="../output/viewdata/' + data.id_createlabnew + '"><button class="btn btn-danger btn-sm">&nbsp;<i class="fa fa-line-chart"></i>GRAPH</button></a></p>'+
                '<p style="text-align:center"><a '+allbtdisable+'><button type="button"class="btn btn-success btn-sm">&nbsp;&nbsp;<i class="fa fa-file-excel-o"></i>&nbsp;&nbsp;CCP&nbsp;&nbsp;&nbsp;</button></a></p>'+
                '<p style="text-align:center"><a onclick="ghostexcelall('+data.id_createlabnew+')"><button class="btn btn-primary btn-sm">&nbsp;<i class="fa fa-clipboard"></i> GHOST</button></a></p>';
                $('td:eq(8)', row).html(viewdata);
        
            }
        });
        $('.btn-success').click(function(){
                
            oTable.ajax.reload();
        });

    // $('.xxxtoto').click(function(){
    //            // alert(0);
    //            alert(0);
    //        // oTable.ajax.reload();
    // });

var inputmodel = document.getElementById("textmodel");
inputmodel.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        oTable.ajax.reload();
    }
});

var inputlot = document.getElementById("textlot");
inputlot.addEventListener("keyup", function (event) {
    if (event.keyCode === 13) {
        event.preventDefault();
        oTable.ajax.reload();
    }
});

    });
   
    function editDatalpc(id,showeditalam) {
        window.location.href = "../output/editdatalpc/" + id+'/1/'+showeditalam;
    }
    function editDatasem(id,showeditalam) {
        window.location.href = "../output/editdatasem/" + id+'/1/'+showeditalam;
    }
    function editDataic(id) {
        window.location.href = "../output/editdataic/"+ id+'/1/0';
    }
    function editDatagcms(id) {
        window.location.href = "../output/editdatagcms/"+ id+'/1/0';
    }
    function editDataftir(id) {
       
        window.location.href = "../output/editdataftir/"+ id+'/1/0';
    }
    function editDatasemtest(id) {
       
       window.location.href = "../output/editdatasemtest/"+ id;
   }
    function exportData() {
        swal({
			title: "Export CPP file",
			text: "",
			type: "input",
			showCancelButton: true,
			closeOnConfirm: false,
			inputPlaceholder: "Enter file name",
            confirmButtonText: "Export",
            cancelButtonText: "Cancel",
		}, function (inputValue) {
			if (inputValue === false) return false;
			if (inputValue === "") {
				swal.showInputError("Please enter file name");
				return false
			}
			swal("Success", "Export CCP file : " + inputValue, "success");
		});
    }
    function viewData() {
        window.location.href = "{{url('/output/view')}}";
    }
    function viewpara(id)
    {
                $.ajax({
                    type: 'get',
                    url: "{{ url('viewpara')}}",
                    data: {
                        'id' : id
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            var lpc = '';
                            var sem = '';
                            var ic = '';
                            var gcms = '';
                            var ftir = '';
                            var semtest = '';
                            if(data.model.lpc == 1)
                            {
                                lpc ='<font color="green"><i class="fa fa-check" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            else
                            {
                                lpc ='<font color="red"><i class="fa fa-times" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            if(data.model.sem == 1)
                            {
                                sem ='<font color="green"><i class="fa fa-check" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            else
                            {
                                sem ='<font color="red"><i class="fa fa-times" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            if(data.model.ic == 1)
                            {
                                ic ='<font color="green"><i class="fa fa-check" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            else
                            {
                                ic ='<font color="red"><i class="fa fa-times" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            if(data.model.gcms == 1)
                            {
                                gcms ='<font color="green"><i class="fa fa-check" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            else
                            {
                                gcms ='<font color="red"><i class="fa fa-times" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            if(data.model.ftir == 1)
                            {
                                ftir ='<font color="green"><i class="fa fa-check" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            else
                            {
                                ftir ='<font color="red"><i class="fa fa-times" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            if(data.model.semtest == 1)
                            {
                                semtest ='<font color="green"><i class="fa fa-check" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                            else
                            {
                                semtest ='<font color="red"><i class="fa fa-times" style="font-size:20px" aria-hidden="true"></i></font>';
                            }
                              var text ='';
                              text += '<tr><td>'+data.model.name_model+'</td>'+
                        '<td>'+lpc+'</td>'+
                        '<td>'+sem+'</td>'+
                        '<td>'+ic+'</td>'+
                        '<td>'+gcms+'</td>'+
                        '<td>'+ftir+'</td>'+
                        '<td>'+semtest+'</td></tr>';
                        $('#modelparaview').html(text);
                        } else {
                        }
                    }
                });
    }
    function ccpexcelall(id)
    {

        var ccplpc  = $('#chkalllpc'+id).val();
        var ccpsem  = $('#chkallsem'+id).val();
        var ccpic  = $('#chkallic'+id).val();
        var ccpgcms  = $('#chkallgcms'+id).val();
        var ccpftir  = $('#chkallftir'+id).val();
        var ccpsemtest  = $('#chkallsemtest'+id).val();
                 swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                $.ajax({
                    type: 'get',
                    url: "{{ url('ccpfileexcelall')}}",
                    data: {
                        'id' : id,
                       'ccplpc': ccplpc,
                       'ccpsem': ccpsem,
                       'ccpic': ccpic,
                       'ccpgcms': ccpgcms,
                       'ccpftir': ccpftir,
                       'ccpsemtest' : ccpsemtest,
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Export CCP File success',
                                '',
                                'success');
                           
                            window.location.href = "../local/storage/NHK-File/report/ccpfile/"+data.filename;
                            $('#dt').DataTable().ajax.reload();
                        } else {
                        }
                    }
                });
    }

    function ghostexcelall(id)
    {
                 swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                $.ajax({
                    type: 'get',
                    url: "{{ url('ghostfileexcelall')}}",
                    data: {
                        'id' : id,
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Export Ghost result file. Complete!',
                                '',
                                'success');
                            window.location.href = "../local/storage/NHK-File/report/ghostexport/"+data.filename;
                            //$('#dt').DataTable().ajax.reload();
                        } else {
                        }
                    }
                });
    }


    
    function ccpexcel(id)
    {
       
      
                 swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                $.ajax({
                    type: 'get',
                    url: "{{ url('ccpfileexcel')}}",
                    data: {
                        'id' : id
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Export CCP File success',
                                '',
                                'success');
                           // window.location.href = "../local/storage/NHK-File/report/ccpfile/"+data.filename;
                           window.location.href = "../local/storage/NHK-File/report/ccpfile/"+data.filename;
                           $('#dt').DataTable().ajax.reload();
                      
                        } else {
                        }
                    }
                });
                
    }
    function ccpexcelftir(id)
    {
        swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                $.ajax({
                    type: 'get',
                    url: "{{ url('ccpexcelftir')}}",
                    data: {
                        'id' : id
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Export CCP File success',
                                '',
                                'success');
                        
                            window.location.href = "../local/storage/NHK-File/report/ccpfile/"+data.filename;
                            $('#dt').DataTable().ajax.reload();
                              
                        } else {
                        }
                    }
                });
    }
    function ccpexcelgcms(id)
    {

        swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                $.ajax({
                    type: 'get',
                    url: "{{ url('ccpexcelgcms')}}",
                    data: {
                        'id' : id
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Export CCP File success',
                                '',
                                'success');
                        
                            window.location.href = "../local/storage/NHK-File/report/ccpfile/"+data.filename;  
                            $('#dt').DataTable().ajax.reload(); 

                        } else {
                        }
                    }
                });
    }
 

    function ccpexcelsem(id)
    {
        swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                $.ajax({
                    type: 'get',
                    url: "{{ url('ccpexcelsem')}}",
                    data: {
                        'id' : id
                    },
                    success: function (data) {
                        
                        if (data.success == 1) {
                            swal('Export CCP File success',
                                '',
                                'success');
                         
                            window.location.href = "../local/storage/NHK-File/report/ccpfile/"+data.filename; 
                            $('#dt').DataTable().ajax.reload();
                        } else {
                        }
                    }
                });
    }

    function ccpexcelic(id)
    {
        swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                $.ajax({
                    type: 'get',
                    url: "{{ url('ccpexcelic')}}",
                    data: {
                        'id' : id
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Export CCP File success',
                                '',
                                'success');
                           
                            window.location.href = "../local/storage/NHK-File/report/ccpfile/"+data.filename;
                            $('#dt').DataTable().ajax.reload();
                        } else {
                        }
                    }
                });
    }

    function ccpexcelghost(id)
    {
        swal({
                        title: "",
                        text: "Please wait..",
                        imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
                        showConfirmButton: false
                    });
                $.ajax({
                    type: 'get',
                    url: "{{ url('ccpfileexcelghost')}}",
                    data: {
                        'id' : id
                    },
                    success: function (data) {
                        
                        if (data.success == 1) {
                            swal('Export CCP File success',
                                '',
                                'success');
                         
                            window.location.href = "../local/storage/NHK-File/report/ccpfile/"+data.filename; 
                            $('#dt').DataTable().ajax.reload();
                        } else {
                        }
                    }
                });
    }

</script>
@endsection