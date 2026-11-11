@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Measurement L/T ')
@section('breadcrumb-title', 'Measurement L/T ')

<style>

    .errorcase {
        color: white;
        background-color: red;
    }

</style>

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
            <div class="card-header text-center">
                <label>Data Status :</label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-circle" style="color:limegreen;"></i> PASS</label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-circle" style="color:red;"></i> NOT PASS</label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-exclamation-triangle" style="color:gold;"></i> WAITING DATA</label>
                <label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-minus" style="color:gray;"></i> NO TESTING</label>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div style="text-align: right">
                    <a onclick="exportexcel()"><button type="button" class="btn btn-success btn-sm">&nbsp;&nbsp;Measurement L/T&nbsp;&nbsp;</button></a>
                    </div>
                    <br>
                    <table valign="bottom" id="dta" class="table table-striped table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" rowspan="2">Item</th>
                                <th class="text-center" colspan="6">Measurement L/T </th>
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
                        <!-- <tbody id="tablechild">
                            <tr>
                                <td class="text-center">Average Measurement L/T</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                            </tr>
                            <tr>
                                <td class="text-center">Amount of Lot number <p style=" font-size: 12px; ">Measurement L/T over UCL</p></td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                                <td class="text-center">-</td>
                            </tr>

                        </tbody> -->
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection
@section('scripts')
<script>

    $(document).ready(function () {
        var oTable = $('#dta').DataTable({
            processing: true,
            serverSide: true,
            searching: false,
            lengthChange: true,
            ajax: {
                "url": "{{url('measuredatatables')}}",
                "data": function (d) {
                    d.date_start = $("#start_date").val();
                    d.date_end = $("#end_date").val();
                    d.textmodel = $("#textmodel").val();
                    d.textlot = $("#textlot").val();
                }
            },
            columns: [{
                    className: "text-center",
                    data: 'id_tsetting',
                    name: 'id_tsetting'
                },
                {
                    className: "text-center",
                    data: 'id_tsetting',
                    name: 'id_tsetting'
                },
                {
                    className: "text-center",
                    data: 'id_tsetting',
                    name: 'id_tsetting'
                },
                {
                    className: "text-center",
                    data: 'id_tsetting',
                    name: 'id_tsetting'
                },
                {
                    className: "text-center",
                    data: 'id_tsetting',
                    name: 'id_tsetting'
                },
                {
                    className: "text-center",
                    data: 'id_tsetting',
                    name: 'id_tsetting'
                },
                {
                    className: "text-center",
                    data: 'id_tsetting',
                    name: 'id_tsetting'
                }
            ],
            rowCallback: function (row, data, index) {

                $('td:eq(0)', row).html(data.nameitem);
                console.log(data);
                
                if(index == 0){

                    $('td:eq(1)', row).html(data.avglpc);
                    $('td:eq(2)', row).html(data.avgsem);
                    $('td:eq(3)', row).html(data.avgic);
                    $('td:eq(4)', row).html(data.avggcms);
                    $('td:eq(5)', row).html(data.avgftir);
                    $('td:eq(6)', row).html(data.avgghost);

                    if(data.avglpc >= data.ucl_lpc){
                        $('td:eq(1)', row).addClass("errorcase");
                    }
                    if(data.avgsem >= data.ucl_sem){
                        $('td:eq(2)', row).addClass("errorcase");
                    }
                    if(data.avgic >= data.ucl_ic){
                        $('td:eq(3)', row).addClass("errorcase");
                    }
                    if(data.avggcms >= data.ucl_gcms){
                        $('td:eq(4)', row).addClass("errorcase");
                    }
                    if(data.avgftir >= data.ucl_ftir){
                        $('td:eq(5)', row).addClass("errorcase");
                    }
                    if(data.avgghost >= data.ucl_semtest){
                        $('td:eq(6)', row).addClass("errorcase");
                    }

                }else{

                    $('td:eq(1)', row).html(data.overlpc);
                    $('td:eq(2)', row).html(data.oversem);
                    $('td:eq(3)', row).html(data.overic);
                    $('td:eq(4)', row).html(data.overgcms);
                    $('td:eq(5)', row).html(data.overftir);
                    $('td:eq(6)', row).html(data.overghost);

                    if(data.overlpc > 0){
                        $('td:eq(1)', row).addClass("errorcase");
                    }
                    if(data.oversem > 0){
                        $('td:eq(2)', row).addClass("errorcase");
                    }
                    if(data.overic > 0){
                        $('td:eq(3)', row).addClass("errorcase");
                    }
                    if(data.overgcms > 0){
                        $('td:eq(4)', row).addClass("errorcase");
                    }
                    if(data.overftir > 0){
                        $('td:eq(5)', row).addClass("errorcase");
                    }
                    if(data.overghost > 0){
                        $('td:eq(6)', row).addClass("errorcase");
                    }

                }

            }
        });
        $('#chk').click(function(){
            oTable.ajax.reload();
        });

    });
  
    function exportexcel() {

        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });

        var datestart = $('#start_date').val();
        var dateend = $('#end_date').val();
        var lot = $('#textlot').val();
        var model = $('#textmodel').val();

        $.ajax({
                type: 'post',
                url: "{{ url('excel_measure') }}",
                data: 
            {
                'datestart' : datestart,
                'dateend' : dateend,
                'lot' : lot, 
                'model' : model
            },
            success: function (data) {
                if (data.success == 1) {
                swal.close();
                  window.location.href = "local/storage/NHK-File/report/Measurement/"+data.filename;  
                } 
            }
        });
    }

    
   

    // $( "#Searchtable" ).on( "click", function() {

    //     swal({
    //         title: "",
    //         text: "Please wait..",
    //         imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
    //         showConfirmButton: false
    //     });


    //     var date_start = $('#start_date').val();
    //     var date_end = $('#end_date').val();
    //     var textlot = $('#textlot').val();
    //     var textmodel = $('#textmodel').val();

    //     $.ajax({
    //             type: 'post',
    //             url: "{{ url('measuredatatables2') }}",
    //             data: 
    //         {
    //             'date_start' : date_start,
    //             'date_end' : date_end,
    //             'textlot' : textlot, 
    //             'textmodel' : textmodel
    //         },
    //         success: function (data) {
                 
    //             swal.close();
    //             $("#tablechild").empty();
    //             $.each(data, function(index, element) {
                    
    //                 var ucllpc = element.measure_day['lpc'];
    //                 var uclsem = element.measure_day['sem'];
    //                 var uclgcms = element.measure_day['gcms'];
    //                 var uclic = element.measure_day['ic'];
    //                 var uclftir = element.measure_day['ftir'];
    //                 var uclghost = element.measure_day['ghost'];

    //                 var table = '';
    //                 var errorlpc = '';
    //                 var errorsem = '';
    //                 var erroric = '';
    //                 var errorgcms = '';
    //                 var errorftir = '';
    //                 var errorghost = '';

    //                 for (var i=0; i < element.listdata.length; i++){
    //                     var x = i+1;

    //                     table += '<tr>';
    //                         if(x == 1){
    //                             table += '<td class="text-center">Average Measurement L/T</td>';
    //                         }else{
    //                             table += '<td class="text-center">Amount of Lot number <p style=" font-size: 12px; ">Measurement L/T over UCL</p></td>';
    //                         }

    //                         if(element.listdata[i]['LPC'] > ucllpc){ errorlpc = "errorcase"; }
    //                         if(element.listdata[i]['SEM'] > uclsem){ errorsem = "errorcase"; }
    //                         if(element.listdata[i]['GCMS'] > uclgcms){ errorgcms = "errorcase"; }
    //                         if(element.listdata[i]['IC'] > uclic){ erroric = "errorcase"; }
    //                         if(element.listdata[i]['FTIR'] > uclftir){ errorftir = "errorcase"; }
    //                         if(element.listdata[i]['GHOST'] > uclghost){ errorghost = "errorcase"; }

    //                         var elementLPC = element.listdata[i]['LPC'];
    //                         var elementSEM = element.listdata[i]['SEM'];
    //                         var elementIC = element.listdata[i]['IC'];
    //                         var elementGCMS = element.listdata[i]['GCMS'];
    //                         var elementFTIR = element.listdata[i]['FTIR'];
    //                         var elementGHOST = element.listdata[i]['GHOST'];

    //                         if(elementLPC == null || elementLPC == 0){ elementLPC = "-"; }
    //                         if(elementSEM == null || elementSEM == 0){ elementSEM = "-"; }
    //                         if(elementIC == null || elementIC == 0){ elementIC = "-"; }
    //                         if(elementGCMS == null || elementGCMS == 0){ elementGCMS = "-"; }
    //                         if(elementFTIR == null || elementFTIR == 0){ elementFTIR = "-"; }
    //                         if(elementGHOST == null || elementGHOST == 0){ elementGHOST = "-"; }

    //                         table += '<td class="text-center '+errorlpc+' ">'+elementLPC+'</td>';
    //                         table += '<td class="text-center '+errorsem+'">'+elementSEM+'</td>';
    //                         table += '<td class="text-center '+erroric+'">'+elementIC+'</td>';
    //                         table += '<td class="text-center '+errorgcms+'">'+elementGCMS+'</td>';
    //                         table += '<td class="text-center '+errorftir+'">'+elementFTIR+'</td>';
    //                         table += '<td class="text-center '+errorghost+'">'+elementGHOST+'</td>';

    //                     table += '</tr>';
    //                 }

    //                 jQuery("#dt tbody").append(table);

    //             });

    //         }
    //     });

    // });

</script>
@endsection
