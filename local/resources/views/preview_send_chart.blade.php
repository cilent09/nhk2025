@extends('layouts.main')
@section('title', 'send chart in email body')
@section('breadcrumb-title', 'Send Email')
@section('breadcrumb-sub', 'send chart in email body')

<script src="{{ asset('template/assets/js/chart.js')}}"></script>
<script src="{{ asset('template/assets/js/html2canvas.min.js')}}"></script>

<style>
    .btn-xl {
        padding: 10px 20px;
        font-size: 20px;
        border-radius: 10px;
    }

    #myBtn {
        /* display: none; */
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 99;
        font-size: 18px;
        font-weight: bold;
        border: none;
        outline: none;
        background-color: #FFA500;
        color: #000000;
        cursor: pointer;
        padding: 10px;
        border-radius: 4px;
        opacity: 0.6;
    }
    #myBtn:hover {
        background-color: #FFA500;
        opacity: 1.0;
    }

    #tbratio tr td {
        height: 10px;
    }

    #tbratio td, #table th {
        padding-top: 3px;
        padding-bottom: 0px;
    }
</style>
@section('content')

<input type="hidden" id="idcreatelab" value="{{$idcreatelab}}">
<input type="hidden" id="id" value="{{$id}}">
<input type="hidden" id="idshowbutton" value="0">
<input type="hidden" id="idhistory" name="idhistory">
<input type="hidden" id="showeditalam" name="showeditalam">


<input type="hidden" id="testter" value="{{@$testter}}">
<input type="hidden" id="model" value="{{@$model}}">
<input type="hidden" id="lot" value="{{@$lot}}">
<input type="hidden" id="date" value="{{@$date}}">
<input type="hidden" id="alarm" value="{{@$alarm}}">
<input type="hidden" id="idqla" value="{{@$idqla}}">
<input type="hidden" id="result" value="{{@$result}}">
<input type="hidden" id="UCL" value="{{@$UCL}}">

{{-- slop and diff --}}
<input type="hidden" id="slopesem3" name="slopesem3" />
<input type="hidden" id="dffsem3" name="dffsem3" />

<input type="hidden" id="slopesem3_1" name="slopesem3_1" />
<input type="hidden" id="dffsem3_1" name="dffsem3_1" />

<input type="hidden" id="slopesem3_3" name="slopesem3_3" />
<input type="hidden" id="dffsem3_3" name="dffsem3_3" />

<input type="hidden" id="slopesem4" name="slopesem4" />
<input type="hidden" id="dffsem4" name="dffsem4" />

<input type="hidden" id="slopesem5" name="slopesem5" />
<input type="hidden" id="dffsem5" name="dffsem5" />

<input type="hidden" id="slopesem6" name="slopesem6" />
<input type="hidden" id="dffsem6" name="dffsem6" />

<input type="hidden" id="slopesemOutgass" name="slopesemOutgass" />
<input type="hidden" id="dffsemOutgass" name="dffsemOutgass" />

{{-- about sigma ratio check --}}
<input type="hidden" id="sum_warning" name="sum_warning" value="0">
<input type="hidden" id="textname_warning" name="textname_warning" />
<input type="hidden" id="file_name_ratio" name="file_name_ratio" />

<div class="my-4 text-center">
</div>
<div class="row" id="show_lpc">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">
                <canvas id="lpcTrendChart" width="800" height="600"></canvas>
            </div>
            <br>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    
    $(document).ready(function (e) {
        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}", 
            showConfirmButton: false
        });
    });

    $(document).ready(function (e) {
        setTimeout(function() {
            var idcreatelab = $("#idcreatelab").val();
            var id = $("#id").val();
            $.ajax({
                type: 'get',
                url: "{{ url('outputgraphtest7') }}",
                data: {
                    id: id,
                    idcreatelab: idcreatelab,
                    chkpdf:0,
                },
                success: function (data) { 

                    var sumalertall = 0;
                    var test1all = 0;
                    var test2all = 0;
                    var test3all = 0;
                    var test4all = 0;
                    var test5all = 0;
                    var test6all = 0;

                    $('#textlot_number').html(data.oqalot.lotnumber_lab);
                    //-------------------------------- lpc --------------------------//
                    var chklpcall = 0;
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        datest = [],
                        len = data.model.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;

                    if (data.chklpcshow == 1) {
                        var  lenvs = data.datalaball.length;
                        var sumvs2 = lenvs - data.alarmtest2;
                        var sumvs3 = lenvs - data.alarmtest3y;
                        data.datalaball.reverse();
                        data.newlpcdatalab.reverse();
                        data.datalab.reverse();
                        var lpc1 = '';
                        var lpc2 = '';
                        var lpc3 = '';
                        var lpc4 = '';
                        var lpc5 = '';
                        var lpc6 = '';
                        var itemdata = data.datalab.length-1;
                        var itemdata1 = data.newlpcdatalab.length-1;
    
                        var showv6 = data.datalaball.length-data.alarmtest3y;
                        var naucltest2 = 0;
                        var nalcltest2 = 0;
                        var naistest4 = 0;
                        if(data.model[2].alpc =="N/A")
                        {
                            naucltest2 = 0;
                        }
                        else if( data.model[2].alpc == "TBD")
                        {
                            naucltest2 = 0;
                        }
                        else
                        {
                            naucltest2 = 1;
                        }
                        if(data.model[3].alpc =="N/A")
                        {
                            nalcltest2 = 0;
                        }
                        else if( data.model[3].alpc == "TBD")
                        {
                            nalcltest2 = 0;
                        }
                        else
                        {
                            nalcltest2 = 1;
                        }
                        if(data.model[5].alpc =="N/A")
                        {
                            naistest4 = 0;
                        }
                        else if( data.model[5].alpc == "TBD")
                        {
                            naistest4 = 0;
                        }
                        else
                        {
                            naistest4 = 1;
                        }
                        // ------------------------- TEST2 -------------------------------------//
                        $('#lpctxt1').html(Getdate(data.modelname.alpc_dc));
                        $('#lpctxt2').html(data.modelname.alpc_dr);
                        $('#lpctxt3').html(data.modelname.part_number);
                        $('#lpctxt4').html(data.modelname.name_model);
                        $('#lpctxt5').html(data.modelname.drive_name);
                        $('#lpctxt6').html(data.model[0].alpc);
                        if(data.newlpcdatalab[itemdata1]){
                            $('#lpctxt7').html(data.newlpcdatalab[itemdata1][0].alpc);
                            $('#lpctxt8').html(data.newlpcdatalab[itemdata1][1].alpc);
                            $('#lpctxt9').html(data.newlpcdatalab[itemdata1][2].alpc);

                            var datauclalpc =  data.newlpcdatalab[itemdata1][1].alpc;
                            var datalclalpc =  data.newlpcdatalab[itemdata1][2].alpc;
                        }else{
                            $('#lpctxt7').html();
                            $('#lpctxt8').html();
                            $('#lpctxt9').html();

                            var datauclalpc = null;
                            var datalclalpc = null;
                        }

                        if(datalclalpc != null && datauclalpc != null){
                            if (parseFloat(data.datalab[itemdata].lpc_data) < parseFloat(datalclalpc)) {
                                if(nalcltest2 == 1){
                                    lpc3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                    sumalertall++;
                                    test1all++;
                                    chklpcall++;
                                }
                                else
                                {
                                    lpc3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                                } 
                            } else if (parseFloat(data.datalab[itemdata].lpc_data) > parseFloat(datauclalpc)) {
                                if(naucltest2 == 1){
                                    lpc3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                    sumalertall++;
                                    test1all++;
                                    chklpcall++;
                                    chklpcucl++;

                                }
                                else
                                {
                                    lpc3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                                }

                            } else {
                                lpc3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }
                        } else {
                            lpc3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        // ------------------------- END TEST1 -------------------------------------//
                    
                        $.each(data.datalaball, function (k, v) {
                            var uclalpc =  data.newlpcdatalab[ckvs][1].alpc;
                            var lclalpc =  data.newlpcdatalab[ckvs][2].alpc;
                            var textvalue = '';
                            ckvs++;
                            if (data.higher >= data.alarmtest2) {

                                if (ckvs > sumvs2) {

                                    textvalue = {
                                        y: v.lpc_data,
                                        backgroundColor: '#BF0B23'
                                    };

                                } else {
                                    textvalue = {
                                        y: v.lpc_data,
                                        backgroundColor: '#2b8a16',
                                    };
                                }
                            } else if (data.below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {

                                    textvalue = {
                                        y: v.lpc_data,
                                        backgroundColor: '#BF0B23'
                                    };

                                } else {
                                    textvalue = {
                                        y: v.lpc_data,
                                        backgroundColor: '#2b8a16',
                                    };
                                }
                            } else {
                                if (data.highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7)
                                        {
                                            textvalue = {
                                                y: v.lpc_data,
                                                backgroundColor: '#2b8a16',
                                            };
                                        }
                                        else{
                                            textvalue = {
                                                y: v.lpc_data,
                                                backgroundColor: '#BF0B23'
                                            };
                                        }
                                    } else {
                                        textvalue = {
                                            y: v.lpc_data,
                                            backgroundColor: '#2b8a16',
                                        };
                                    }

                                } else if (data.belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7)
                                    {
                                        textvalue = {
                                            y: v.lpc_data,
                                            backgroundColor: '#2b8a16',
                                        };
                                    }
                                    else{
                                        textvalue = {
                                            y: v.lpc_data,
                                            backgroundColor: '#BF0B23'
                                        };
                                    }
                                    } else {
                                        textvalue = {
                                            y: v.lpc_data,
                                            backgroundColor: '#2b8a16',
                                        };
                                    }
                                } else {
                                    if (v.lpc_data < lclalpc) {
                                        if(nalcltest2 == 1)
                                        {
                                            textvalue = {
                                                y: v.lpc_data,
                                                backgroundColor: '#BF0B23'
                                            };
                                        }
                                        else
                                        {
                                            textvalue = {
                                                y: v.lpc_data,
                                                backgroundColor: '#2b8a16',
                                            };
                                        }

                                    } else if (v.lpc_data > uclalpc) {
                                        if(naucltest2 == 1)
                                        {
                                        textvalue = {
                                            y: v.lpc_data,
                                            backgroundColor: '#BF0B23',
                                        };
                                        }
                                        else
                                        {
                                            textvalue = {
                                                y: v.lpc_data,
                                                backgroundColor: '#2b8a16',
                                            };
                                        }
                                    } else {

                                        if (v.lpc_data > data.is.alpc) {
                                            if(naistest4 == 1)
                                            {
                                                textvalue = {
                                                    y: v.lpc_data,
                                                    backgroundColor: '#BF0B23',
                                                };
                                            }
                                            else
                                            {
                                                textvalue = {
                                                    y: v.lpc_data,
                                                    backgroundColor: '#2b8a16',
                                                };
                                            }
                                        }
                                        else {
                                            textvalue = {
                                                y: v.lpc_data,
                                                backgroundColor: '#2b8a16',
                                            };
                                        }
                                    }
                                }
                            }
                            // datalab[vs] = textvalue; 
                            datalab[vs] = v.lpc_data; 
                            var fmdate = Getdate(v.date_lpc);

                            datest[vs] = fmdate+' '+v.lotnumber_lab;
                            vs++;
                        });
                        console.log(datalab);
                        
                        $.each(data.model, function (k, v) {
                            i++;

                            if (v.id_model_type == 1) {
                                for (i = 0; i < lenvs; i++) {
                                    animals.push(parseFloat(v.alpc)); 
                                }
                            }
                            if (v.id_model_type == 2) {
                                $.each(data.newlpcdatalab, function (k, u)  {
                                animals.push(parseFloat(u[0].alpc)); 
                            });
                            }
                            if (v.id_model_type == 3) {
                                $.each(data.newlpcdatalab, function (k, u)  {
                                animals.push(parseFloat(u[1].alpc)); 
                            });
                            }
                            if (v.id_model_type == 4) {
                                $.each(data.newlpcdatalab, function (k, u)  {
                                animals.push(parseFloat(u[2].alpc)); 
                            });
                            }
                            if (v.id_model_type == 5) {
                                $.each(data.newlpcdatalab, function (k, u)  {
                                animals.push(parseFloat(u[3].alpc)); 
                            });
                            }
                            if (v.id_model_type == 6) {

                                for (i = 0; i < lenvs; i++) {
                                    animals.push(parseFloat(v.alpc));
                                }

                            }
                            var st = null;
                            if (v.id_model_type == 5) {
                                st = 'dot';
                            }
                            series.push({
                                label: v.name,
                                data: animals,
                                borderColor: v.color,
                                borderWidth: 1,
                                marker: {
                                    enabled: false
                                },
                                zones: [{
                                    value: 0
                                }, {
                                    dashStyle: st
                                }]
                            });
                            animals = [];
                        });

                        if (datalab.length === 0) {
                            console.error("datalab ไม่มีข้อมูล กรุณาตรวจสอบ data.datalaball");
                        } else {
                            console.log("datalab พร้อมใช้งาน:", datalab);
                        }

                        series.push({
                            label: 'Data',
                            data: datalab,
                            borderColor: "#2b8a16",
                            enableMouseTracking: true,
                            borderWidth: 4,
                            pointRadius: 4,
                            // pointBackgroundColor: datalab.map(point => point.backgroundColor), // ใช้สีตามแต่ละจุด
                            // pointBorderColor: datalab.map(point => point.backgroundColor), // ใช้สีขอบตามแต่ละจุด
                            tooltip: {
                                enabled: true
                            },
                            marker: {
                                symbol: "circle",
                                lineWidth: 6,
                                lineColor: null // inherit from series
                            },
                            style: {
                                fontWeight: 'bold'
                            },
                        });

                        var ctx = document.getElementById("lpcTrendChart").getContext("2d");
                        series = series.filter(ds => ds.data.length > 0 && ds.data.some(v => v !== null));

                        // สร้างกราฟ
                        var myChart = new Chart(ctx, {
                            type: "line",
                            data: { labels: datest, datasets: series },
                            options: {
                                responsive: false,
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: function (tooltipItem) {
                                                return `Data: ${tooltipItem.raw} Particle/cm2`;
                                            }
                                        }
                                    },
                                    title: {
                                        display: true,
                                        text: 'ALPC of {{ $laball->name_model }}'
                                    }
                                }
                            }
                        });
                        // เรียกใช้เมื่อโหลดเสร็จ
                        setTimeout(sendChartAsImage, 3000);
 
                        function sendChartAsImage() {
                            var canvas = document.getElementById("lpcTrendChart");
                            
                            html2canvas(canvas).then((canvas) => {
                                var imgData = canvas.toDataURL("image/svg+xml"); 
                                
                                var testter = $('#testter').val();
                                var model = $('#model').val();
                                var lot = $('#lot').val();
                                var date = $('#date').val();
                                var alarm = $('#alarm').val();
                                var idqla = $('#idqla').val();
                                var result = $('#result').val();
                                var UCL = $('#UCL').val();

                                $.ajax({
                                    url: "{{ url('save_chart') }}",
                                    type: "POST",
                                    data: {
                                        image: imgData,
                                        id: id,
                                        idcreatelab: idcreatelab,
                                        testter: testter,
                                        model: model,
                                        lot: lot,
                                        date: date,
                                        alarm: alarm,
                                        idqla: idqla,
                                        result: result,
                                        UCL: UCL,
                                    },
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    success: function (response) {
                                        console.log("Chart saved successfully", response);
                                        swal.close();
                                        window.location.href = "../../OQA/oqafile/"+ id;
                                    },
                                    error: function (xhr) {
                                        console.error("Error saving chart", xhr);
                                        swal.close();
                                        window.location.href = "../../OQA/oqafile/"+ id;
                                    }
                                });
                            });
                        }

  
                    //--------------------------------end lpc --------------------------//
                    } 
                }
            });
        }, 100);
    });
    

</script>
 

@endsection
