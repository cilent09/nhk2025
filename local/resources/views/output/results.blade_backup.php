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
    function checkDataWarning(data) { 
        var key_data = [];
        var sumalertall = 0;
        var test1all = 0;
        var test2all = 0;
        var test3all = 0;
        var test4all = 0;
        var test5all = 0;
        var test6all = 0;

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
            var lenvs = data.datalaball.length;
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
            var itemdata = data.datalab.length - 1;
            var itemdata1 = data.newlpcdatalab.length - 1;

            var showv6 = data.datalaball.length - data.alarmtest3y;
            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].alpc == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].alpc == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].alpc == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].alpc == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].alpc == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].alpc == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            // ------------------------- TEST2 -------------------------------------//

            var test7 = data.datalaball.length - data.alarmtest3;
            // var datauclalpc = data.newlpcdatalab[itemdata1][1].alpc; 
            // var datalclalpc = data.newlpcdatalab[itemdata1][2].alpc;
            if(data.newlpcdatalab[itemdata1]){
                var datauclalpc = data.newlpcdatalab[itemdata1][1].alpc; 
                var datalclalpc = data.newlpcdatalab[itemdata1][2].alpc;
            }else{
                var datauclalpc = null; 
                var datalclalpc = null;
            }
            var chklpcucl = 0;
            if (data.higher >= data.alarmtest2) {
                lpc1 = 'false';
                sumalertall++;
                test2all++;
                chklpcall++;

            } else if (data.below >= data.alarmtest2) {
                lpc1 = 'false';
                sumalertall++;
                test2all++;
                chklpcall++;
            } else {
                lpc1 = 'true';
            }
            // ------------------------- END TEST2 -------------------------------------//
            // ------------------------- TEST3 -------------------------------------//
            if (data.highervs3 >= data.alarmtest3) {
                lpc2 = 'false';
                sumalertall++;
                test3all++;
                chklpcall++;
            } else if (data.belowvs3 >= data.alarmtest3) {
                lpc2 = 'false';
                sumalertall++;
                test3all++;
                chklpcall++;
            } else {
                lpc2 = 'true';
            }
            // -------------------------END  TEST3 -------------------------------------//
            // ------------------------- TEST1 -------------------------------------//
            if(datalclalpc !=null && datauclalpc != null){
                if (parseFloat(data.datalab[itemdata].lpc_data) < parseFloat(datalclalpc)) {
                    if (nalcltest2 == 1) {
                        lpc3 = 'false';
                        sumalertall++;
                        test1all++;
                        chklpcall++;
                    } else {
                        lpc3 = 'true';
                    }

                    //alert(1);
                } else if (parseFloat(data.datalab[itemdata].lpc_data) > parseFloat(datauclalpc)) {
                    if (naucltest2 == 1) {
                        lpc3 = 'false';
                        sumalertall++;
                        test1all++;
                        chklpcall++;
                        chklpcucl++;

                    } else {
                        lpc3 = 'true';
                    }

                } else {
                    lpc3 = 'true';
                }
            }else{
                lpc3 = 'true';
            }
            // ------------------------- END TEST1 -------------------------------------//
            // -------------------------TEST4 -------------------------------------//
            if (data.datalab[itemdata].lpc_data > data.is.alpc) {
                if (naistest4 == 1) {
                    lpc4 = 'false';
                    sumalertall++;
                    test4all++;
                    chklpcall++;
                } else {
                    lpc4 = 'true';
                }
            } else {
                lpc4 = 'true';
            }
            $('#showeditalam').val(chklpcucl);
            // ------------------------- END TEST4 -------------------------------------//

            $.each(data.datalaball, function (k, v) {
                var uclalpc = data.newlpcdatalab[ckvs][1].alpc;
                var lclalpc = data.newlpcdatalab[ckvs][2].alpc;
                var textvalue = '';
                ckvs++;
                if (data.higher >= data.alarmtest2) {

                    if (ckvs > sumvs2) {

                        textvalue = {
                            y: v.lpc_data,
                            color: '#BF0B23'
                        };

                    } else {
                        textvalue = v.lpc_data;
                    }
                } else if (data.below >= data.alarmtest2) {
                    if (ckvs > sumvs2) {

                        textvalue = {
                            y: v.lpc_data,
                            color: '#BF0B23'
                        };

                    } else {
                        textvalue = v.lpc_data;
                    }
                } else {
                    if (data.highervs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.lpc_data;
                            } else {
                                textvalue = {
                                    y: v.lpc_data,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.lpc_data;
                        }

                    } else if (data.belowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.lpc_data;
                            } else {
                                textvalue = {
                                    y: v.lpc_data,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.lpc_data;
                        }
                    } else {
                        if (v.lpc_data < lclalpc) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.lpc_data,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.lpc_data;
                            }

                        } else if (v.lpc_data > uclalpc) {
                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.lpc_data,
                                    color: '#BF0B23',
                                };
                            } else {
                                textvalue = v.lpc_data;
                            }
                        } else {

                            if (v.lpc_data > data.is.alpc) {
                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.lpc_data,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.lpc_data;
                                }
                            } else {
                                textvalue = v.lpc_data;
                            }
                        }
                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_lpc);

                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });

            $.each(data.model, function (k, v) {
                i++;

                if (v.id_model_type == 1) {
                    for (i = 0; i < lenvs; i++) {
                        animals.push(parseFloat(v.alpc));
                    }
                }
                if (v.id_model_type == 2) {
                    $.each(data.newlpcdatalab, function (k, u) {
                        animals.push(parseFloat(u[0].alpc));
                    });
                }
                if (v.id_model_type == 3) {
                    $.each(data.newlpcdatalab, function (k, u) {
                        animals.push(parseFloat(u[1].alpc));
                    });
                }
                if (v.id_model_type == 4) {
                    $.each(data.newlpcdatalab, function (k, u) {
                        animals.push(parseFloat(u[2].alpc));
                    });
                }
                if (v.id_model_type == 5) {
                    $.each(data.newlpcdatalab, function (k, u) {
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
                    name: v.name,
                    data: animals,
                    color: v.color,
                    lineWidth: 2,
                    marker: {
                        enabled: false
                    },
                    // enableMouseTracking: false,
                    label: {
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
            series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
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


            var textslopelpc = '';
            var textdfflpc = '';
            // if ((data.slopelpc >= 0.5 || data.slopelpc <= -0.5) || data.dfflpc >= 3.5) {
            if ((data.slopediff_lpc_SLOPE >= 0.5 || data.slopediff_lpc_SLOPE <= -0.5) || data.slopediff_lpc_DIFF >= 3.5) {
                test5all++;
                chklpcall++;
                lpc5 = 'false';
            } else {
                lpc5 = 'true';
            }
            if (data.slopelpc != null) {
                if (data.slopelpc >= 0.5 || data.slopelpc <= -0.5) {
                    textslopelpc += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopelpc +
                        '</td>';
                } else {
                    textslopelpc += '<td>' + data.slopelpc + '</td>';
                }
            } else {
                textslopelpc += '<td>-</td>';
            }
            if (data.dfflpc != null) {
                if (data.dfflpc >= 3.5) {
                    textdfflpc += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dfflpc +
                        '</td>';
                } else {
                    textdfflpc += '<td>' + data.dfflpc + '</td>';
                }
            } else {
                textdfflpc += '<td>-</td>';
            }
            var textlpc = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countlpc + '</td>' +
                '<td>' + data.avgslope.toFixed(3) + '</td>' +
                '<td>' + data.stdlpc + '</td>' +
                '<td>' + Getdatetime(data.oqalot.save_date) + '</td>' +
                textslopelpc +
                textdfflpc +
                '</tr>';
            // $('#dtlpc tbody').html(textlpc);

            //--------------------------------end lpc --------------------------//
        } else {
            // document.getElementById("show_lpc").style.display = "none";
        }

        if (data.chksemshow == 1) {
            //-------------------------------- sem class1 --------------------------//
            var chksemclass1all = 0;
            var chkclass1all = 0;
            var semclass1_1 = '';
            var semclass1_2 = '';
            var semclass1_3 = '';
            var semclass1_4 = '';
            var itemdata = data.semdatalab.length - 1;
            var itemdata1 = data.newsemdatalab.length - 1;
            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class1 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class1 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class1 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class1 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class1 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class1 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }


            var showv6 = data.semdatalaball.length - data.alarmtest3y;
            var datauclclass1 = data.newsemdatalab[itemdata1][1].class1;
            var datalclclass1 = data.newsemdatalab[itemdata1][2].class1;
            if (data.sem1higher >= data.alarmtest2) {
                semclass1_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass1all++;
            } else if (data.sem1below >= data.alarmtest2) {
                semclass1_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass1all++;
            } else {
                semclass1_1 = 'true';
            }
            if (data.sem1highervs3 >= data.alarmtest3) {
                semclass1_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass1all++;
            } else if (data.sem1belowvs3 >= data.alarmtest3) {
                semclass1_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass1all++;
            } else {
                semclass1_2 = 'true';
            }
            if(data.semdatalab[itemdata].last_sem_class1 !== null){
                if (data.semdatalab[itemdata].last_sem_class1 < datalclclass1) {
                    if (nalcltest2 == 1) {
                        semclass1_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass1all++;
                    } else {
                        semclass1_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].last_sem_class1 > datauclclass1) {

                    if (naucltest2 == 1) {
                        semclass1_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass1all++;
                        chkclass1all++;
                    } else {
                        semclass1_3 = 'true';
                    }
                } else {
                    semclass1_3 = 'true';

                }
                if (data.semdatalab[itemdata].last_sem_class1 > data.is.class1) {

                    if (naistest4 == 1) {
                        semclass1_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass1all++;
                    } else {
                        semclass1_4 = 'true';
                    }
                } else {
                    semclass1_4 = 'true';
                }
            }else{
                if (data.semdatalab[itemdata].sem_class1 < datalclclass1) {
                    if (nalcltest2 == 1) {
                        semclass1_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass1all++;
                    } else {
                        semclass1_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].sem_class1 > datauclclass1) {

                    if (naucltest2 == 1) {
                        semclass1_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass1all++;
                        chkclass1all++;
                    } else {
                        semclass1_3 = 'true';
                    }
                } else {
                    semclass1_3 = 'true';

                }
                if (data.semdatalab[itemdata].sem_class1 > data.is.class1) {

                    if (naistest4 == 1) {
                        semclass1_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass1all++;
                    } else {
                        semclass1_4 = 'true';
                    }
                } else {
                    semclass1_4 = 'true';
                }
            }
            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                datest = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;
            var test7 = data.semdatalaball.length - data.alarmtest3;

            $.each(data.semdatalaball, function (k, v) {
                var uclclass1 = data.newsemdatalab[ckvs][1].class1;
                var lclclass1 = data.newsemdatalab[ckvs][2].class1;
                var textvalue = '';
                ckvs++;

                if(v.last_sem_class1 !== null){
                    if (data.sem1higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class1;
                        }
                    } else if (data.sem1below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class1;
                        }
                    } else {
                        if (data.sem1highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class1;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class1;
                            }

                        } else if (data.sem1belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class1;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class1;
                            }
                        } else {
                            if (v.last_sem_class1 < lclclass1) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class1;
                                }

                            } else if (v.last_sem_class1 > uclclass1) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class1;
                                }

                            } else {
                                if (v.last_sem_class1 > data.is.class1) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class1,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class1;
                                    }

                                } else {
                                    textvalue = v.last_sem_class1;
                                }
                            }

                        }
                    }
                }else{
                    if (data.sem1higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class1;
                        }
                    } else if (data.sem1below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class1;
                        }
                    } else {
                        if (data.sem1highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class1;
                                } else {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class1;
                            }

                        } else if (data.sem1belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class1;
                                } else {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class1;
                            }
                        } else {
                            if (v.sem_class1 < lclclass1) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class1;
                                }

                            } else if (v.sem_class1 > uclclass1) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class1;
                                }

                            } else {
                                if (v.sem_class1 > data.is.class1) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class1,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class1;
                                    }

                                } else {
                                    textvalue = v.sem_class1;
                                }
                            }

                        }
                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });

             
            var textslopel = '';
            var textdff = '';
            if (data.slopesem1 >= 0.5 || data.slopesem1 <= -0.5 || data.dffsem1 >= 3.5) {
                test5all++;
                chksemclass1all++;
                semclass1_5 = 'false';
            } else {
                semclass1_5 = 'true';
            }
            if (data.slopesem1 != null) {
                if (data.slopesem1 >= 0.5 || data.slopesem1 <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem1 +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopesem1 + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffsem1 != null) {
                if (data.dffsem1 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem1 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem1 + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textsem1 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem1 + '</td>' +
                '<td>' + data.avgsem1.toFixed(3) + '</td>' +
                '<td>' + data.stdsem1 + '</td>' +
                '<td>' + Getdatetime(data.oqalot.save_date) + '</td>' +
                textslopel + textdff +
                '</tr>';
            // $('#dtsem1 tbody').html(textsem1);
            //-------------------------------- end sem class1 --------------------------//
            //-------------------------------- sem class2 --------------------------//
            var chksemclass2all = 0;
            var semclass2_1 = '';
            var semclass2_2 = '';
            var semclass2_3 = '';
            var semclass2_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class2 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class2 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class2 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class2 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class2 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class2 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass2 = data.newsemdatalab[itemdata1][2].class2;
            var datauclclass2 = data.newsemdatalab[itemdata1][1].class2;
            if (data.sem2higher >= data.alarmtest2) {
                semclass2_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass2all++;
            } else if (data.sem2below >= data.alarmtest2) {
                semclass2_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass2all++;
            } else {
                semclass2_1 = 'true';
            }
            if (data.sem2highervs3 >= data.alarmtest3) {
                semclass2_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass2all++;
            } else if (data.sem2belowvs3 >= data.alarmtest3) {
                semclass2_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass2all++;
            } else {
                semclass2_2 = 'true';
            }

            if(data.semdatalab[itemdata].last_sem_class2 !== null){
                if (data.semdatalab[itemdata].last_sem_class2 < datalclclass2) {
                    if (nalcltest2 == 1) {
                        semclass2_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass2all++;
                    } else {
                        semclass2_3 = 'true';
                    }
                } else if (data.semdatalab[itemdata].last_sem_class2 > datauclclass2) {
                    if (naucltest2 == 1) {
                        semclass2_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass2all++;
                    } else {
                        semclass2_3 = 'true';
                    }
                } else {
                    semclass2_3 = 'true';
                }
                if (data.semdatalab[itemdata].last_sem_class2 > data.is.class2) {
                    if (naistest4 == 1) {
                        semclass2_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass2all++;
                    } else {
                        semclass2_4 = 'true';
                    }

                } else {
                    semclass2_4 = 'true';
                }
            }else{
                if (data.semdatalab[itemdata].sem_class2 < datalclclass2) {
                    if (nalcltest2 == 1) {
                        semclass2_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass2all++;
                    } else {
                        semclass2_3 = 'true';
                    }
                } else if (data.semdatalab[itemdata].sem_class2 > datauclclass2) {
                    if (naucltest2 == 1) {
                        semclass2_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass2all++;
                    } else {
                        semclass2_3 = 'true';
                    }
                } else {
                    semclass2_3 = 'true';
                }
                if (data.semdatalab[itemdata].sem_class2 > data.is.class2) {
                    if (naistest4 == 1) {
                        semclass2_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass2all++;
                    } else {
                        semclass2_4 = 'true';
                    }

                } else {
                    semclass2_4 = 'true';
                }
            }        
            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass2 = data.newsemdatalab[ckvs][2].class2;
                var uclclass2 = data.newsemdatalab[ckvs][1].class2;
                ckvs++;

                if(v.last_sem_class2 !== null){
                    if (data.sem2higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class2,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class2;
                        }
                    } else if (data.sem2below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class2,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class2;
                        }
                    } else {
                        if (data.sem2highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class2;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class2,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class2;
                            }

                        } else if (data.sem2belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class2;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class2,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class2;
                            }
                        } else {
                            if (v.last_sem_class2 < lclclass2) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class2;
                                }

                            } else if (v.last_sem_class2 > uclclass2) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class2;
                                }

                            } else {
                                if (v.last_sem_class2 > data.is.class2) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class2,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class2;
                                    }

                                } else {
                                    textvalue = v.last_sem_class2;
                                }
                            }
                        }
                    }
                }else{
                    if (data.sem2higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class2,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class2;
                        }
                    } else if (data.sem2below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class2,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class2;
                        }
                    } else {
                        if (data.sem2highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class2;
                                } else {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class2;
                            }

                        } else if (data.sem2belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class2;
                                } else {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class2;
                            }
                        } else {
                            if (v.sem_class2 < lclclass2) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class2;
                                }

                            } else if (v.sem_class2 > uclclass2) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class2;
                                }

                            } else {
                                if (v.sem_class2 > data.is.class2) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class2,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class2;
                                    }

                                } else {
                                    textvalue = v.sem_class2;
                                }
                            }
                        }
                    }
                }

                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopesem2 >= 0.5 || data.slopesem2 <= -0.5 || data.dffsem2 >= 3.5) {
                test5all++;
                chksemclass2all++;
                semclass2_5 = 'false';
            } else {
                semclass2_5 = 'true';
            }
            if (data.slopesem2 != null) {
                if (data.slopesem2 >= 0.5 || data.slopesem2 <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem2 +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopesem2 + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffsem2 != null) {
                if (data.dffsem2 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem2 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem2 + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textsem2 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem2 + '</td>' +
                '<td>' + data.avgsem2.toFixed(3) + '</td>' +
                '<td>' + data.stdsem2 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtsem2 tbody').html(textsem2);

            //-------------------------------- end sem class2 --------------------------//

            //-------------------------------- sem class3 --------------------------//
            var chksemclass3all = 0;
            var semclass3_1 = '';
            var semclass3_2 = '';
            var semclass3_3 = '';
            var semclass3_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class3 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class3 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class3 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class3 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class3 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class3 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass3 = data.newsemdatalab[itemdata1][2].class3;
            var datauclclass3 = data.newsemdatalab[itemdata1][1].class3;
            if (data.sem3higher >= data.alarmtest2) {
                semclass3_1 = 'false';
                sumalertall++;
                test2all++
                chksemclass3all++;
            } else if (data.sem3below >= data.alarmtest2) {
                semclass3_1 = 'false';
                sumalertall++;
                test2all++
                chksemclass3all++;
            } else {
                semclass3_1 = 'true';
            }

            if (data.sem3highervs3 >= data.alarmtest3) {
                semclass3_2 = 'false';
                sumalertall++;
                test3all++
                chksemclass3all++;
            } else if (data.sem3belowvs3 >= data.alarmtest3) {
                semclass3_2 = 'false';
                sumalertall++;
                test3all++
                chksemclass3all++;
            } else {
                semclass3_2 = 'true';
            }

            if(data.semdatalab[itemdata].last_sem_class3 !== null){
                if (data.semdatalab[itemdata].last_sem_class3 < datalclclass3) {
                    if (nalcltest2 == 1) {
                        semclass3_3 = 'false';
                        sumalertall++;
                        test1all++
                        chksemclass3all++;
                    } else {
                        semclass3_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].last_sem_class3 > datauclclass3) {
                    if (naucltest2 == 1) {
                        semclass3_3 = 'false';
                        sumalertall++;
                        test1all++
                        chksemclass3all++;
                    } else {
                        semclass3_3 = 'true';
                    }

                } else {
                    semclass3_3 = 'true';

                }
                if (data.semdatalab[itemdata].last_sem_class3 > data.is.class3) {
                    if (naistest4 == 1) {
                        semclass3_4 = 'false';
                        sumalertall++;
                        test4all++
                        chksemclass3all++;
                    } else {
                        semclass3_4 = 'true';
                    }
                } else {
                    semclass3_4 = 'true';
                }
            }else{
                if (data.semdatalab[itemdata].sem_class3 < datalclclass3) {
                    if (nalcltest2 == 1) {
                        semclass3_3 = 'false';
                        sumalertall++;
                        test1all++
                        chksemclass3all++;
                    } else {
                        semclass3_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].sem_class3 > datauclclass3) {
                    if (naucltest2 == 1) {
                        semclass3_3 = 'false';
                        sumalertall++;
                        test1all++
                        chksemclass3all++;
                    } else {
                        semclass3_3 = 'true';
                    }

                } else {
                    semclass3_3 = 'true';

                }
                if (data.semdatalab[itemdata].sem_class3 > data.is.class3) {
                    if (naistest4 == 1) {
                        semclass3_4 = 'false';
                        sumalertall++;
                        test4all++
                        chksemclass3all++;
                    } else {
                        semclass3_4 = 'true';
                    }
                } else {
                    semclass3_4 = 'true';
                }
            }    
            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass3 = data.newsemdatalab[ckvs][2].class3;
                var uclclass3 = data.newsemdatalab[ckvs][1].class3;
                ckvs++;

                if(v.last_sem_class3 !== null){
                    if (data.sem3higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class3,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class3;
                        }
                    } else if (data.sem3below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class3,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class3;
                        }
                    } else {
                        if (data.sem3highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class3;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class3,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class3;
                            }
                        } else if (data.sem3belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class3;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class3,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class3;
                            }
                        } else {
                            if (v.last_sem_class3 < lclclass3) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3;
                                }

                            } else if (v.last_sem_class3 > uclclass3) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3;
                                }
                            } else {
                                if (v.last_sem_class3 > data.is.class3) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class3,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class3;
                                    }

                                } else {
                                    textvalue = v.last_sem_class3;
                                }
                            }
                        }
                    }
                }else{
                    if (data.sem3higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class3,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class3;
                        }
                    } else if (data.sem3below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class3,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class3;
                        }
                    } else {
                        if (data.sem3highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class3;
                                } else {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class3;
                            }
                        } else if (data.sem3belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class3;
                                } else {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class3;
                            }
                        } else {
                            if (v.sem_class3 < lclclass3) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3;
                                }

                            } else if (v.sem_class3 > uclclass3) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3;
                                }
                            } else {
                                if (v.sem_class3 > data.is.class3) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class3,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class3;
                                    }

                                } else {
                                    textvalue = v.sem_class3;
                                }
                            }
                        }
                    }
                }

                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });

            var textslopel = '';
            var textdff = '';
            // if ((data.slopesem3 >= 0.5 || data.slopesem3 <= -0.5) || data.dffsem3 >= 3.5) {
            //     test5all++;
            //     chksemclass3all++;
            //     semclass3_5 = 'false';

            // } else {
            //     semclass3_5 = 'true';
            // }
            // if (data.slopesem3 != null) {
            //     if (data.slopesem3 >= 0.5 || data.slopesem3 <= -0.5) {
            //         textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem3 +
            //             '</td>';
            //     } else {
            //         textslopel += '<td>' + data.slopesem3 + '</td>';
            //     }
            // } else {
            //     textslopel += '<td>-</td>';
            // }
            // if (data.dffsem3 != null) {
            //     if (data.dffsem3 >= 3.5) {
            //         textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem3 +
            //             '</td>';
            //     } else {
            //         textdff += '<td>' + data.dffsem3 + '</td>';
            //     }
            // } else {
            //     textdff += '<td>-</td>';
            // }

            // **** Fixed an issue where notifications were incorrect. // 04-08-23
            if ((data.slopediff_sem3_SLOPE >= 0.5 || data.slopediff_sem3_SLOPE <= -0.5) || data.slopediff_sem3_DIFF >= 3.5) {
                test5all++;
                chksemclass3all++;
                semclass3_5 = 'false';

            } else {
                semclass3_5 = 'true';
            }
            if (data.slopediff_sem3_SLOPE != null) {
                if (data.slopediff_sem3_SLOPE >= 0.5 || data.slopediff_sem3_SLOPE <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopediff_sem3_SLOPE +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopediff_sem3_SLOPE + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.slopediff_sem3_DIFF != null) {
                if (data.slopediff_sem3_DIFF >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.slopediff_sem3_DIFF +
                        '</td>';
                } else {
                    textdff += '<td>' + data.slopediff_sem3_DIFF + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }

            var textsem3 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem3 + '</td>' +
                '<td>' + data.avgsem3.toFixed(3) + '</td>' +
                '<td>' + data.stdsem3 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtsem3 tbody').html(textsem3);
            //-------------------------------- end sem class3 --------------------------//

            //-------------------------------- sem class3_1 --------------------------//

            var chksemclass31all = 0;
            var semclass31_1 = '';
            var semclass31_2 = '';
            var semclass31_3 = '';
            var semclass31_4 = '';
            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class3_1 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class3_1 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class3_1 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class3_1 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class3_1 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class3_1 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass3_1 = data.newsemdatalab[itemdata1][2].class3_1;
            var datauclclass3_1 = data.newsemdatalab[itemdata1][1].class3_1;
            if (data.sem31higher >= data.alarmtest2) {
                semclass31_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass31all++;
            } else if (data.sem31below >= data.alarmtest2) {
                semclass31_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass31all++;
            } else {
                semclass31_1 = 'true';
            }

            if (data.sem31highervs3 >= data.alarmtest3) {
                semclass31_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass31all++;
            } else if (data.sem31belowvs3 >= data.alarmtest3) {
                semclass31_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass31all++;
            } else {
                semclass31_2 = 'true';
            }

            if(data.semdatalab[itemdata].last_sem_class3_1 !== null){
                if (data.semdatalab[itemdata].last_sem_class3_1 < datalclclass3_1) {
                    if (nalcltest2 == 1) {
                        semclass31_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass31all++;
                    } else {
                        semclass31_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].last_sem_class3_1 > datauclclass3_1) {

                    if (naucltest2 == 1) {
                        semclass31_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass31all++;
                    } else {
                        semclass31_3 = 'true';
                    }

                } else {
                    semclass31_3 = 'true';

                }
                if (data.semdatalab[itemdata].last_sem_class3_1 > data.is.class3_1) {
                    if (naistest4 == 1) {
                        semclass31_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass31all++;
                    } else {
                        semclass31_4 = 'true';
                    }
                } else {
                    semclass31_4 = 'true';
                }
            }else{
                if (data.semdatalab[itemdata].sem_class3_1 < datalclclass3_1) {
                    if (nalcltest2 == 1) {
                        semclass31_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass31all++;
                    } else {
                        semclass31_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].sem_class3_1 > datauclclass3_1) {

                    if (naucltest2 == 1) {
                        semclass31_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass31all++;
                    } else {
                        semclass31_3 = 'true';
                    }

                } else {
                    semclass31_3 = 'true';

                }
                if (data.semdatalab[itemdata].sem_class3_1 > data.is.class3_1) {
                    if (naistest4 == 1) {
                        semclass31_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass31all++;
                    } else {
                        semclass31_4 = 'true';
                    }
                } else {
                    semclass31_4 = 'true';
                }
            }


            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;
            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass3_1 = data.newsemdatalab[ckvs][2].class3_1;
                var uclclass3_1 = data.newsemdatalab[ckvs][1].class3_1;
                ckvs++;

                if(v.last_sem_class3_1 !== null){
                    if (data.sem31higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class3_1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class3_1;
                        }
                    } else if (data.sem31below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class3_1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class3_1;
                        }
                    } else {
                        if (data.sem31highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class3_1;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class3_1;
                            }

                        } else if (data.sem31belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class3_1;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class3_1;
                            }
                        } else {
                            if (v.last_sem_class3_1 < lclclass3_1) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_1;
                                }

                            } else if (v.last_sem_class3_1 > uclclass3_1) {

                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_1;
                                }

                            } else {
                                if (v.last_sem_class3_1 > data.is.class3_1) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class3_1,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class3_1;
                                    }

                                } else {
                                    textvalue = v.last_sem_class3_1;
                                }
                            }
                        }
                    }
                }else{
                    if (data.sem31higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class3_1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class3_1;
                        }
                    } else if (data.sem31below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class3_1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class3_1;
                        }
                    } else {
                        if (data.sem31highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class3_1;
                                } else {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class3_1;
                            }

                        } else if (data.sem31belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class3_1;
                                } else {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class3_1;
                            }
                        } else {
                            if (v.sem_class3_1 < lclclass3_1) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_1;
                                }

                            } else if (v.sem_class3_1 > uclclass3_1) {

                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_1;
                                }

                            } else {
                                if (v.sem_class3_1 > data.is.class3_1) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class3_1,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class3_1;
                                    }

                                } else {
                                    textvalue = v.sem_class3_1;
                                }
                            }
                        }
                    }
                }

                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            // if (data.slopesem3_1 >= 0.5 || data.slopesem3_1 <= -0.5 || data.dffsem3_1 >= 3.5) {
            //     test5all++;
            //     chksemclass31all++;
            //     semclass31_5 = 'false';
            // } else {
            //     semclass31_5 = 'true';
            // }
            // if (data.slopesem3_1 != null) {
            //     if (data.slopesem3_1 >= 0.5 || data.slopesem3_1 <= -0.5) {
            //         textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem3_1 +
            //             '</td>';
            //     } else {
            //         textslopel += '<td>' + data.slopesem3_1 + '</td>';
            //     }
            // } else {
            //     textslopel += '<td>-</td>';
            // }
            // if (data.dffsem3_1 != null) {
            //     if (data.dffsem3_1 >= 3.5) {
            //         textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem3_1 +
            //             '</td>';
            //     } else {
            //         textdff += '<td>' + data.dffsem3_1 + '</td>';
            //     }
            // } else {
            //     textdff += '<td>-</td>';
            // }
            if (data.slopediff_sem3_1_SLOPE >= 0.5 || data.slopediff_sem3_1_SLOPE <= -0.5 || data.slopediff_sem3_1_DIFF >= 3.5) {
                test5all++;
                chksemclass31all++;
                semclass31_5 = 'false';
            } else {
                semclass31_5 = 'true';
            }
            if (data.slopediff_sem3_1_SLOPE != null) {
                if (data.slopediff_sem3_1_SLOPE >= 0.5 || data.slopediff_sem3_1_SLOPE <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopediff_sem3_1_SLOPE +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopediff_sem3_1_SLOPE + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.slopediff_sem3_1_DIFF != null) {
                if (data.slopediff_sem3_1_DIFF >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF ">' + data.slopediff_sem3_1_DIFF +
                        '</td>';
                } else {
                    textdff += '<td>' + data.slopediff_sem3_1_DIFF + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textsem3_1 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem3_1 + '</td>' +
                '<td>' + data.avgsem3_1.toFixed(3) + '</td>' +
                '<td>' + data.stdsem3_1 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtsem3_1 tbody').html(textsem3_1);
            //-------------------------------- end sem class3_1 --------------------------//

            //-------------------------------- sem class3_2 --------------------------//
            var chksemclass32all = 0;
            var semclass32_1 = '';
            var semclass32_2 = '';
            var semclass32_3 = '';
            var semclass32_4 = '';
            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class3_2 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class3_2 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class3_2 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class3_2 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class3_2 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class3_2 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass3_2 = data.newsemdatalab[itemdata1][2].class3_2;
            var datauclclass3_2 = data.newsemdatalab[itemdata1][1].class3_2;
            if (data.sem32higher >= data.alarmtest2) {
                semclass32_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass32all++;
            } else if (data.sem32below >= data.alarmtest2) {
                semclass32_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass32all++;
            } else {
                semclass32_1 = 'true';
            }

            if (data.sem32highervs3 >= data.alarmtest3) {
                semclass32_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass32all++;
            } else if (data.sem32belowvs3 >= data.alarmtest3) {
                semclass32_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass32all++;
            } else {
                semclass32_2 = 'true';
            }
            
            if(data.semdatalab[itemdata].last_sem_class3_2 !== null){
                if (data.semdatalab[itemdata].last_sem_class3_2 < datalclclass3_2) {
                    if (nalcltest2 == 1) {
                        semclass32_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass32all++;
                    } else {
                        semclass32_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].last_sem_class3_2 > datauclclass3_2) {
                    if (naucltest2 == 1) {
                        semclass32_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass32all++;
                    } else {
                        semclass32_3 = 'true';
                    }

                } else {
                    semclass32_3 = 'true';
                }
                if (data.semdatalab[itemdata].last_sem_class3_2 > data.is.class3_2) {
                    if (naistest4 == 1) {
                        semclass32_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass32all++;
                    } else {
                        semclass32_4 = 'true';
                    }
                } else {
                    semclass32_4 = 'true';
                }
            }else{
                if (data.semdatalab[itemdata].sem_class3_2 < datalclclass3_2) {
                    if (nalcltest2 == 1) {
                        semclass32_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass32all++;
                    } else {
                        semclass32_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].sem_class3_2 > datauclclass3_2) {
                    if (naucltest2 == 1) {
                        semclass32_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass32all++;
                    } else {
                        semclass32_3 = 'true';
                    }

                } else {
                    semclass32_3 = 'true';
                }
                if (data.semdatalab[itemdata].sem_class3_2 > data.is.class3_2) {
                    if (naistest4 == 1) {
                        semclass32_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass32all++;
                    } else {
                        semclass32_4 = 'true';
                    }
                } else {
                    semclass32_4 = 'true';
                }
            }

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass3_2 = data.newsemdatalab[ckvs][2].class3_2;
                var uclclass3_2 = data.newsemdatalab[ckvs][1].class3_2;
                ckvs++;

                if(v.last_sem_class3_2 !== null){
                    if (data.sem32higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class3_2,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class3_2;
                        }
                    } else if (data.sem32below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class3_2,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class3_2;
                        }
                    } else {
                        if (data.sem32highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class3_2;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class3_2;
                            }

                        } else if (data.sem32belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class3_2;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class3_2;
                            }
                        } else {
                            if (v.last_sem_class3_2 < lclclass3_2) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_2;
                                }

                            } else if (v.last_sem_class3_2 > uclclass3_2) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_2;
                                }

                            } else {
                                if (v.last_sem_class3_2 > data.is.class3_2) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class3_2,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class3_2;
                                    }

                                } else {
                                    textvalue = v.last_sem_class3_2;
                                }
                            }
                        }
                    }
                }else{
                    if (data.sem32higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class3_2,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class3_2;
                        }
                    } else if (data.sem32below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class3_2,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class3_2;
                        }
                    } else {
                        if (data.sem32highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class3_2;
                                } else {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class3_2;
                            }

                        } else if (data.sem32belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class3_2;
                                } else {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class3_2;
                            }
                        } else {
                            if (v.sem_class3_2 < lclclass3_2) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_2;
                                }

                            } else if (v.sem_class3_2 > uclclass3_2) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_2;
                                }

                            } else {
                                if (v.sem_class3_2 > data.is.class3_2) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class3_2,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class3_2;
                                    }

                                } else {
                                    textvalue = v.sem_class3_2;
                                }
                            }
                        }
                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopesem3_2 >= 0.5 || data.slopesem3_2 <= -0.5 || data.dffsem3_2 >= 3.5) {
                test5all++;
                chksemclass32all++;
                semclass32_5 = 'false';
            } else {
                semclass32_5 = 'true';
            }
            if (data.slopesem3_2 != null) {
                if (data.slopesem3_2 >= 0.5 || data.slopesem3_2 <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem3_2 +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopesem3_2 + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffsem3_2 != null) {
                if (data.dffsem3_2 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem3_2 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem3_2 + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textsem3_2 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem3_2 + '</td>' +
                '<td>' + data.avgsem3_2.toFixed(2) + '</td>' +
                '<td>' + data.stdsem3_2 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtsem3_2 tbody').html(textsem3_2);
            //-------------------------------- end sem class3_2 --------------------------//


            //-------------------------------- end sem class3_3 --------------------------//
            var chksemclass33all = 0;
            var semclass33_1 = '';
            var semclass33_2 = '';
            var semclass33_3 = '';
            var semclass33_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class3_3 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class3_3 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class3_3 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class3_3 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class3_3 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class3_3 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass3_3 = data.newsemdatalab[itemdata1][2].class3_3;
            var datauclclass3_3 = data.newsemdatalab[itemdata1][1].class3_3;
            if (data.sem33higher >= data.alarmtest2) {
                semclass33_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass33all++;
            } else if (data.sem33below >= data.alarmtest2) {
                semclass33_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass33all++;
            } else {
                semclass33_1 = 'true';
            }

            if (data.sem33highervs3 >= data.alarmtest3) {
                semclass33_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass33all++;
            } else if (data.sem33belowvs3 >= data.alarmtest3) {
                semclass33_2 = 'false';
                sumalertall++;
                chksemclass33all++;
            } else {
                semclass33_2 = 'true';
            }

            if(data.semdatalab[itemdata].last_sem_class3_3 !== null){
                if (data.semdatalab[itemdata].last_sem_class3_3 < datalclclass3_3) {
                    if (nalcltest2 == 1) {
                        semclass33_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass33all++;
                    } else {
                        semclass33_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].last_sem_class3_3 > datauclclass3_3) {
                    if (naucltest2 == 1) {
                        semclass33_3 = 'false';
                        test1all++;
                        sumalertall++;
                        chksemclass33all++;
                    } else {
                        semclass33_3 = 'true';
                    }

                } else {
                    semclass33_3 = 'true';

                }
                if (data.semdatalab[itemdata].last_sem_class3_3 > data.is.class3_3) {
                    if (naistest4 == 1) {
                        semclass33_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass33all++;
                    } else {
                        semclass33_4 = 'true';
                    }
                } else {
                    semclass33_4 = 'true';
                }
            }else{
                if (data.semdatalab[itemdata].sem_class3_3 < datalclclass3_3) {
                    if (nalcltest2 == 1) {
                        semclass33_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass33all++;
                    } else {
                        semclass33_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].sem_class3_3 > datauclclass3_3) {
                    if (naucltest2 == 1) {
                        semclass33_3 = 'false';
                        test1all++;
                        sumalertall++;
                        chksemclass33all++;
                    } else {
                        semclass33_3 = 'true';
                    }

                } else {
                    semclass33_3 = 'true';

                }
                if (data.semdatalab[itemdata].sem_class3_3 > data.is.class3_3) {
                    if (naistest4 == 1) {
                        semclass33_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass33all++;
                    } else {
                        semclass33_4 = 'true';
                    }
                } else {
                    semclass33_4 = 'true';
                }
            }

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;
            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass3_3 = data.newsemdatalab[ckvs][2].class3_3;
                var uclclass3_3 = data.newsemdatalab[ckvs][1].class3_3;
                ckvs++;

                if(v.last_sem_class3_3 !== null){
                    if (data.sem33higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class3_3,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class3_3;
                        }
                    } else if (data.sem33below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class3_3,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class3_3;
                        }
                    } else {
                        if (data.sem33highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class3_3;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class3_3;
                            }

                        } else if (data.sem33belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class3_3;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class3_3;
                            }
                        } else {
                            if (v.last_sem_class3_3 < lclclass3_3) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_3;
                                }

                            } else if (v.last_sem_class3_3 > uclclass3_3) {

                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_3;
                                }

                            } else {
                                if (v.last_sem_class3_3 > data.is.class3_3) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class3_3,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class3_3;
                                    }

                                } else {
                                    textvalue = v.last_sem_class3_3;
                                }
                            }
                        }
                    }
                }else{
                    if (data.sem33higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class3_3,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class3_3;
                        }
                    } else if (data.sem33below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class3_3,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class3_3;
                        }
                    } else {
                        if (data.sem33highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class3_3;
                                } else {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class3_3;
                            }

                        } else if (data.sem33belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class3_3;
                                } else {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class3_3;
                            }
                        } else {
                            if (v.sem_class3_3 < lclclass3_3) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_3;
                                }

                            } else if (v.sem_class3_3 > uclclass3_3) {

                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_3;
                                }

                            } else {
                                if (v.sem_class3_3 > data.is.class3_3) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class3_3,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class3_3;
                                    }

                                } else {
                                    textvalue = v.sem_class3_3;
                                }
                            }
                        }
                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopediff_sem3_3_SLOPE >= 0.5 || data.slopediff_sem3_3_SLOPE <= -0.5 || data.slopediff_sem3_3_DIFF  >= 3.5) {
                test5all++;
                chksemclass33all++;
                semclass33_5 = 'false';
            } else {
                semclass33_5 = 'true';
            }
            if (data.slopediff_sem3_3_SLOPE != null) {
                if (data.slopediff_sem3_3_SLOPE >= 0.5 || data.slopediff_sem3_3_SLOPE <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopediff_sem3_3_SLOPE +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopediff_sem3_3_SLOPE + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.slopediff_sem3_3_DIFF  != null) {
                if (data.slopediff_sem3_3_DIFF  >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.slopediff_sem3_3_DIFF  +
                        '</td>';
                } else {
                    textdff += '<td>' + data.slopediff_sem3_3_DIFF  + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            // if (data.slopesem3_3 >= 0.5 || data.slopesem3_3 <= -0.5 || data.dffsem3_3 >= 3.5) {
            //     test5all++;
            //     chksemclass33all++;
            //     semclass33_5 = 'false';
            // } else {
            //     semclass33_5 = 'true';
            // }
            // if (data.slopesem3_3 != null) {
            //     if (data.slopesem3_3 >= 0.5 || data.slopesem3_3 <= -0.5) {
            //         textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem3_3 +
            //             '</td>';
            //     } else {
            //         textslopel += '<td>' + data.slopesem3_3 + '</td>';
            //     }
            // } else {
            //     textslopel += '<td>-</td>';
            // }
            // if (data.dffsem3_3 != null) {
            //     if (data.dffsem3_3 >= 3.5) {
            //         textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem3_3 +
            //             '</td>';
            //     } else {
            //         textdff += '<td>' + data.dffsem3_3 + '</td>';
            //     }
            // } else {
            //     textdff += '<td>-</td>';
            // }
            var textsem3_3 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem3_3 + '</td>' +
                '<td>' + data.avgsem3_3.toFixed(3) + '</td>' +
                '<td>' + data.stdsem3_3 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtsem3_3 tbody').html(textsem3_3);
            //-------------------------------- end sem class3_3 --------------------------//
            //--------------------------------  sem class4 --------------------------//
            var chksemclass4all = 0;
            var semclass4_1 = '';
            var semclass4_2 = '';
            var semclass4_3 = '';
            var semclass4_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class4 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class4 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class4 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class4 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class4 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class4 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass4 = data.newsemdatalab[itemdata1][2].class4;
            var datauclclass4 = data.newsemdatalab[itemdata1][1].class4;
            if (data.sem4higher >= data.alarmtest2) {
                semclass4_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass4all++;
            } else if (data.sem4below >= data.alarmtest2) {
                semclass4_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass4all++;
            } else {
                semclass4_1 = 'true';
            }

            if (data.sem4highervs3 >= data.alarmtest3) {
                semclass4_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass4all++;
            } else if (data.sem4belowvs3 >= data.alarmtest3) {
                semclass4_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass4all++;
            } else {
                semclass4_2 = 'true';
            }

            if(data.semdatalab[itemdata].last_sem_class4 !== null){
                if (data.semdatalab[itemdata].last_sem_class4 < datalclclass4) {
                    if (nalcltest2 == 1) {
                        semclass4_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass4all++;
                    } else {
                        semclass4_3 = 'true';
                    }
                } else if (data.semdatalab[itemdata].last_sem_class4 > datauclclass4) {
                    if (naucltest2 == 1) {
                        semclass4_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass4all++;
                    } else {
                        semclass4_3 = 'true';
                    }
                } else {
                    semclass4_3 = 'true';

                }
                if (data.semdatalab[itemdata].last_sem_class4 > data.is.class4) {
                    if (naistest4 == 1) {
                        semclass4_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass4all++;
                    } else {
                        semclass4_4 = 'true';
                    }

                } else {
                    semclass4_4 = 'true';
                }
            }else{
                if (data.semdatalab[itemdata].sem_class4 < datalclclass4) {
                    if (nalcltest2 == 1) {
                        semclass4_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass4all++;
                    } else {
                        semclass4_3 = 'true';
                    }
                } else if (data.semdatalab[itemdata].sem_class4 > datauclclass4) {
                    if (naucltest2 == 1) {
                        semclass4_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass4all++;
                    } else {
                        semclass4_3 = 'true';
                    }
                } else {
                    semclass4_3 = 'true';

                }
                if (data.semdatalab[itemdata].sem_class4 > data.is.class4) {
                    if (naistest4 == 1) {
                        semclass4_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass4all++;
                    } else {
                        semclass4_4 = 'true';
                    }

                } else {
                    semclass4_4 = 'true';
                }
            }

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;
            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass4 = data.newsemdatalab[ckvs][2].class4;
                var uclclass4 = data.newsemdatalab[ckvs][1].class4;
                ckvs++;

                if(v.last_sem_class4 !== null){
                    if (data.sem4higher >= data.alarmtest2) {
                        if (ckvs > sumvs3) {
                            textvalue = {
                                y: v.last_sem_class4,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class4;
                        }
                    } else if (data.sem4below >= data.alarmtest2) {
                        if (ckvs > sumvs3) {
                            textvalue = {
                                y: v.last_sem_class4,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class4;
                        }
                    } else {
                        if (data.sem4highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class4;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class4,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class4;
                            }

                        } else if (data.sem4belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class4;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class4,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class4;
                            }
                        } else {
                            if (v.last_sem_class4 < lclclass4) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class4,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class4;
                                }

                            } else if (v.last_sem_class4 > uclclass4) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class4,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class4;
                                }

                            } else {
                                if (v.last_sem_class4 > data.is.class4) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class4,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class4;
                                    }

                                } else {
                                    textvalue = v.last_sem_class4;
                                }
                            }
                        }
                    }
                }else{
                    if (data.sem4higher >= data.alarmtest2) {
                        if (ckvs > sumvs3) {
                            textvalue = {
                                y: v.sem_class4,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class4;
                        }
                    } else if (data.sem4below >= data.alarmtest2) {
                        if (ckvs > sumvs3) {
                            textvalue = {
                                y: v.sem_class4,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class4;
                        }
                    } else {
                        if (data.sem4highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class4;
                                } else {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class4;
                            }

                        } else if (data.sem4belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class4;
                                } else {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class4;
                            }
                        } else {
                            if (v.sem_class4 < lclclass4) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class4;
                                }

                            } else if (v.sem_class4 > uclclass4) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class4;
                                }

                            } else {
                                if (v.sem_class4 > data.is.class4) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class4,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class4;
                                    }

                                } else {
                                    textvalue = v.sem_class4;
                                }
                            }
                        }
                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            // if (data.slopesem4 >= 0.5 || data.slopesem4 <= -0.5 || data.dffsem4 >= 3.5) {
            //     test5all++;
            //     chksemclass4all++;
            //     semclass4_5 = 'false';
            // } else {
            //     semclass4_5 = 'true';
            // }
            // if (data.slopesem4 != null) {
            //     if (data.slopesem4 >= 0.5 || data.slopesem4 <= -0.5) {
            //         textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem4 +
            //             '</td>';
            //     } else {
            //         textslopel += '<td>' + data.slopesem4 + '</td>';
            //     }
            // } else {
            //     textslopel += '<td>-</td>';
            // }
            // if (data.dffsem4 != null) {
            //     if (data.dffsem4 >= 3.5) {
            //         textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem4 +
            //             '</td>';
            //     } else {
            //         textdff += '<td>' + data.dffsem4 + '</td>';
            //     }
            // } else {
            //     textdff += '<td>-</td>';
            // }

            if (data.slopediff_sem4_SLOPE >= 0.5 || data.slopediff_sem4_SLOPE <= -0.5 || data.slopediff_sem4_DIFF >= 3.5) {
                test5all++;
                chksemclass4all++;
                semclass4_5 = 'false';
            } else {
                semclass4_5 = 'true';
            }
            if (data.slopediff_sem4_SLOPE != null) {
                if (data.slopediff_sem4_SLOPE >= 0.5 || data.slopediff_sem4_SLOPE <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopediff_sem4_SLOPE +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopediff_sem4_SLOPE + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.slopediff_sem4_DIFF != null) {
                if (data.slopediff_sem4_DIFF >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF ">' + data.slopediff_sem4_DIFF +
                        '</td>';
                } else {
                    textdff += '<td>' + data.slopediff_sem4_DIFF + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textsem4 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem4 + '</td>' +
                '<td>' + data.avgsem4.toFixed(3) + '</td>' +
                '<td>' + data.stdsem4 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtsem4 tbody').html(textsem4);

            //-------------------------------- end sem class4 --------------------------//

            //--------------------------------  sem class5 --------------------------//
            var chksemclass5all = 0;
            var semclass5_1 = '';
            var semclass5_2 = '';
            var semclass5_3 = '';
            var semclass5_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class5 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class5 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class5 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class5 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class5 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class5 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass5 = data.newsemdatalab[itemdata1][2].class5;
            var datauclclass5 = data.newsemdatalab[itemdata1][1].class5;
            if (data.sem5higher >= data.alarmtest2) {
                semclass5_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass5all++;
            } else if (data.sem5below >= data.alarmtest2) {
                semclass5_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass5all++;
            } else {
                semclass5_1 = 'true';
            }

            if (data.sem5highervs3 >= data.alarmtest3) {
                semclass5_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass5all++;
            } else if (data.sem5belowvs3 >= data.alarmtest3) {
                semclass5_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass5all++;
            } else {
                semclass5_2 = 'true';
            }
            
            if(data.semdatalab[itemdata].last_sem_class5 !== null){
                if (data.semdatalab[itemdata].last_sem_class5 < datalclclass5) {
                    if (nalcltest2 == 1) {
                        semclass5_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass5all++;
                    } else {
                        semclass5_3 = 'true';
                    }
                } else if (data.semdatalab[itemdata].last_sem_class5 > datauclclass5) {
                    if (naucltest2 == 1) {
                        semclass5_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass5all++;
                    } else {
                        semclass5_3 = 'true';
                    }
                } else {
                    semclass5_3 = 'true';

                }
                if (data.semdatalab[itemdata].last_sem_class5 > data.is.class5) {
                    if (naistest4 == 1) {
                        semclass5_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass5all++;
                    } else {
                        semclass5_4 = 'true';
                    }
                } else {
                    semclass5_4 = 'true';
                }
            }else{
                if (data.semdatalab[itemdata].sem_class5 < datalclclass5) {
                    if (nalcltest2 == 1) {
                        semclass5_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass5all++;
                    } else {
                        semclass5_3 = 'true';
                    }
                } else if (data.semdatalab[itemdata].sem_class5 > datauclclass5) {
                    if (naucltest2 == 1) {
                        semclass5_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass5all++;
                    } else {
                        semclass5_3 = 'true';
                    }
                } else {
                    semclass5_3 = 'true';

                }
                if (data.semdatalab[itemdata].sem_class5 > data.is.class5) {
                    if (naistest4 == 1) {
                        semclass5_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass5all++;
                    } else {
                        semclass5_4 = 'true';
                    }
                } else {
                    semclass5_4 = 'true';
                }
            }

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass5 = data.newsemdatalab[ckvs][2].class5;
                var uclclass5 = data.newsemdatalab[ckvs][1].class5;
                ckvs++;

                if(v.last_sem_class5 !== null){
                    if (data.sem5higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class5,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class5;
                        }
                    } else if (data.sem5below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class5,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class5;
                        }
                    } else {
                        if (data.sem5highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class5;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class5,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class5;
                            }

                        } else if (data.sem5belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class5;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class5,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class5;
                            }
                        } else {
                            if (v.last_sem_class5 < lclclass5) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class5,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class5;
                                }
                            } else if (v.last_sem_class5 > uclclass5) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class5,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class5;
                                }
                            } else {
                                if (v.last_sem_class5 > data.is.class5) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class5,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class5;
                                    }

                                } else {
                                    textvalue = v.last_sem_class5;
                                }
                            }


                        }
                    }
                }else{
                    if (data.sem5higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class5,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class5;
                        }
                    } else if (data.sem5below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class5,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class5;
                        }
                    } else {
                        if (data.sem5highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class5;
                                } else {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class5;
                            }

                        } else if (data.sem5belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class5;
                                } else {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class5;
                            }
                        } else {
                            if (v.sem_class5 < lclclass5) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class5;
                                }
                            } else if (v.sem_class5 > uclclass5) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class5;
                                }
                            } else {
                                if (v.sem_class5 > data.is.class5) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class5,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class5;
                                    }

                                } else {
                                    textvalue = v.sem_class5;
                                }
                            }


                        }
                    }
                }

                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopediff_sem5_SLOPE >= 0.5 || data.slopediff_sem5_SLOPE <= -0.5 || data.slopediff_sem5_DIF >= 3.5) {
                test5all++;
                chksemclass5all++;
                semclass5_5 = 'false';
            } else {
                semclass5_5 = 'true';
            }
            if (data.slopediff_sem5_SLOPE != null) {
                if (data.slopediff_sem5_SLOPE >= 0.5 || data.slopediff_sem5_SLOPE <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopediff_sem5_SLOPE +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopediff_sem5_SLOPE + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.slopediff_sem5_DIF != null) {
                if (data.slopediff_sem5_DIF >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.slopediff_sem5_DIF +
                        '</td>';
                } else {
                    textdff += '<td>' + data.slopediff_sem5_DIF + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            // if (data.slopesem5 >= 0.5 || data.slopesem5 <= -0.5 || data.dffsem5 >= 3.5) {
            //     test5all++;
            //     chksemclass5all++;
            //     semclass5_5 = 'false';
            // } else {
            //     semclass5_5 = 'true';
            // }
            // if (data.slopesem5 != null) {
            //     if (data.slopesem5 >= 0.5 || data.slopesem5 <= -0.5) {
            //         textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem5 +
            //             '</td>';
            //     } else {
            //         textslopel += '<td>' + data.slopesem5 + '</td>';
            //     }
            // } else {
            //     textslopel += '<td>-</td>';
            // }
            // if (data.dffsem5 != null) {
            //     if (data.dffsem5 >= 3.5) {
            //         textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem5 +
            //             '</td>';
            //     } else {
            //         textdff += '<td>' + data.dffsem5 + '</td>';
            //     }
            // } else {
            //     textdff += '<td>-</td>';
            // }
            var textsem5 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem5 + '</td>' +
                '<td>' + data.avgsem5.toFixed(3) + '</td>' +
                '<td>' + data.stdsem5 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';
            // $('#dtsem5 tbody').html(textsem5);

            //-------------------------------- end sem class5 --------------------------//

            //-------------------------------- sem class5_1 --------------------------//
            var chksemclass51all = 0;
            var semclass51_1 = '';
            var semclass51_2 = '';
            var semclass51_3 = '';
            var semclass51_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class5_1 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class5_1 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class5_1 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class5_1 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class5_1 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class5_1 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass5_1 = data.newsemdatalab[itemdata1][2].class5_1;
            var datauclclass5_1 = data.newsemdatalab[itemdata1][1].class5_1;
            if (data.sem51higher >= data.alarmtest2) {
                semclass51_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass51all++;
            } else if (data.sem51below >= data.alarmtest2) {
                semclass51_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass51all++;
            } else {
                semclass51_1 = 'true';
            }
            if (data.sem51highervs3 >= data.alarmtest3) {
                semclass51_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass51all++;
            } else if (data.sem51belowvs3 >= data.alarmtest3) {
                semclass51_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass51all++;
            } else {
                semclass51_2 = 'true';
            }

            if(data.semdatalab[itemdata].last_sem_class5_1 !== null){    
                if (data.semdatalab[itemdata].last_sem_class5_1 < datalclclass5_1) {
                    if (nalcltest2 == 1) {
                        semclass51_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass51all++;
                    } else {
                        semclass51_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].last_sem_class5_1 > datauclclass5_1) {
                    if (naucltest2 == 1) {
                        semclass51_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass51all++;
                    } else {
                        semclass51_3 = 'true';
                    }
                } else {
                    semclass51_3 = 'true';
                }
                if (data.semdatalab[itemdata].last_sem_class5_1 > data.is.class5_1) {

                    if (naistest4 == 1) {
                        semclass51_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass51all++;
                    } else {
                        semclass51_4 = 'true';
                    }

                } else {
                    semclass51_4 = 'true';
                }
            }else{
                    
                if (data.semdatalab[itemdata].sem_class5_1 < datalclclass5_1) {
                    if (nalcltest2 == 1) {
                        semclass51_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass51all++;
                    } else {
                        semclass51_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].sem_class5_1 > datauclclass5_1) {
                    if (naucltest2 == 1) {
                        semclass51_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass51all++;
                    } else {
                        semclass51_3 = 'true';
                    }
                } else {
                    semclass51_3 = 'true';
                }
                if (data.semdatalab[itemdata].sem_class5_1 > data.is.class5_1) {

                    if (naistest4 == 1) {
                        semclass51_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass51all++;
                    } else {
                        semclass51_4 = 'true';
                    }

                } else {
                    semclass51_4 = 'true';
                }
            }
            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass5_1 = data.newsemdatalab[ckvs][2].class5_1;
                var uclclass5_1 = data.newsemdatalab[ckvs][1].class5_1;
                ckvs++;

                if(v.last_sem_class5_1 !== null){
                    if (data.sem51higher >= data.alarmtest2) {
                        if (ckvs > sumvs3) {
                            textvalue = {
                                y: v.last_sem_class5_1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class5_1;
                        }
                    } else if (data.sem51below >= data.alarmtest2) {
                        if (ckvs > sumvs3) {
                            textvalue = {
                                y: v.last_sem_class5_1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class5_1;
                        }
                    } else {
                        if (data.sem51highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class5_1;
                                } else {
                                    textvalue = {

                                        y: v.last_sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class5_1;
                            }

                        } else if (data.sem51belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.last_sem_class5_1;
                                } else {
                                    textvalue = {
                                        y: v.last_sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.last_sem_class5_1;
                            }
                        } else {
                            if (v.last_sem_class5_1 < lclclass5_1) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class5_1;
                                }

                            } else if (v.last_sem_class5_1 > uclclass5_1) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class5_1;
                                }

                            } else {
                                if (v.last_sem_class5_1 > data.sp.class5_1) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class5_1,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class5_1;
                                    }

                                } else {
                                    textvalue = v.last_sem_class5_1;
                                }
                            }


                        }
                    }
                }else{
                    if (data.sem51higher >= data.alarmtest2) {
                        if (ckvs > sumvs3) {
                            textvalue = {
                                y: v.sem_class5_1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class5_1;
                        }
                    } else if (data.sem51below >= data.alarmtest2) {
                        if (ckvs > sumvs3) {
                            textvalue = {
                                y: v.sem_class5_1,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class5_1;
                        }
                    } else {
                        if (data.sem51highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class5_1;
                                } else {
                                    textvalue = {

                                        y: v.sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class5_1;
                            }

                        } else if (data.sem51belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if (ckvs == test7) {
                                    textvalue = v.sem_class5_1;
                                } else {
                                    textvalue = {
                                        y: v.sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                }
                            } else {
                                textvalue = v.sem_class5_1;
                            }
                        } else {
                            if (v.sem_class5_1 < lclclass5_1) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class5_1;
                                }

                            } else if (v.sem_class5_1 > uclclass5_1) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class5_1;
                                }

                            } else {
                                if (v.sem_class5_1 > data.sp.class5_1) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class5_1,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class5_1;
                                    }

                                } else {
                                    textvalue = v.sem_class5_1;
                                }
                            }


                        }
                    }
                }

                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });

            var textslopel = '';
            var textdff = '';
            if (data.slopesem5_1 >= 0.5 || data.slopesem5_1 <= -0.5 || data.dffsem5_1 >= 3.5) {
                test5all++;
                chksemclass51all++;
                semclass51_5 = 'false';
            } else {
                semclass51_5 = 'true';
            }
            if (data.slopesem5_1 != null) {
                if (data.slopesem5_1 >= 0.5 || data.slopesem5_1 <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem5_1 +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopesem5_1 + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffsem5_1 != null) {
                if (data.dffsem5_1 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem5_1 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem5_1 + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textsem5_1 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem5_1 + '</td>' +
                '<td>' + data.avgsem5_1.toFixed(3) + '</td>' +
                '<td>' + data.stdsem5_1 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtsem5_1 tbody').html(textsem5_1);
            //-------------------------------- end sem class5_1 --------------------------//

            //-------------------------------- sem class6 --------------------------//
            var chksemclass6all = 0;
            var semclass6_1 = '';
            var semclass6_2 = '';
            var semclass6_3 = '';
            var semclass6_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].class6 == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].class6 == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].class6 == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].class6 == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].class6 == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].class6 == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclclass6 = data.newsemdatalab[itemdata1][2].class6;
            var datauclclass6 = data.newsemdatalab[itemdata1][1].class6;
            if (data.sem6higher >= data.alarmtest2) {
                semclass6_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass6all++;
            } else if (data.sem6below >= data.alarmtest2) {
                semclass6_1 = 'false';
                sumalertall++;
                test2all++;
                chksemclass6all++;
            } else {
                semclass6_1 = 'true';
            }

            if (data.sem6highervs3 >= data.alarmtest3) {
                semclass6_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass6all++;
            } else if (data.sem6belowvs3 >= data.alarmtest3) {
                semclass6_2 = 'false';
                sumalertall++;
                test3all++;
                chksemclass6all++;
            } else {
                semclass6_2 = 'true';
            }
            
            if(data.semdatalab[itemdata].last_sem_class6 !== null){
                if (data.semdatalab[itemdata].last_sem_class6 < datalclclass6) {
                    if (nalcltest2 == 1) {
                        semclass6_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass6all++;
                    } else {
                        semclass6_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].last_sem_class6 > datauclclass6) {
                    if (naucltest2 == 1) {
                        semclass6_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass6all++;
                    } else {
                        semclass6_3 = 'true';
                    }

                } else {
                    semclass6_3 = 'true';

                }
                if (data.semdatalab[itemdata].last_sem_class6 > data.is.class6) {
                    if (naistest4 == 1) {
                        semclass6_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass6all++;
                    } else {
                        semclass6_4 = 'true';
                    }
                } else {
                    semclass6_4 = 'true';
                }
            }else{
                    
                if (data.semdatalab[itemdata].sem_class6 < datalclclass6) {
                    if (nalcltest2 == 1) {
                        semclass6_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass6all++;
                    } else {
                        semclass6_3 = 'true';
                    }

                } else if (data.semdatalab[itemdata].sem_class6 > datauclclass6) {
                    if (naucltest2 == 1) {
                        semclass6_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chksemclass6all++;
                    } else {
                        semclass6_3 = 'true';
                    }

                } else {
                    semclass6_3 = 'true';

                }
                if (data.semdatalab[itemdata].sem_class6 > data.is.class6) {
                    if (naistest4 == 1) {
                        semclass6_4 = 'false';
                        sumalertall++;
                        test4all++;
                        chksemclass6all++;
                    } else {
                        semclass6_4 = 'true';
                    }
                } else {
                    semclass6_4 = 'true';
                }
            }

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.semdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.semdatalaball, function (k, v) {
                var textvalue = '';
                var lclclass6 = data.newsemdatalab[ckvs][2].class6;
                var uclclass6 = data.newsemdatalab[ckvs][1].class6;
                ckvs++;

                if(v.last_sem_class6 !== null){
                    if (data.sem6higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class6,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class6;
                        }
                    } else if (data.sem6below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.last_sem_class6,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.last_sem_class6;
                        }
                    } else {
                        if (data.sem6highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.last_sem_class6,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.last_sem_class6;
                            }

                        } else if (data.sem6belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.last_sem_class6,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.last_sem_class6;
                            }
                        } else {
                            if (v.last_sem_class6 < lclclass6) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class6,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class6;
                                }

                            } else if (v.last_sem_class6 > uclclass6) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.last_sem_class6,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class6;
                                }

                            } else {
                                if (v.last_sem_class6 > data.is.class6) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.last_sem_class6,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.last_sem_class6;
                                    }

                                } else {
                                    textvalue = v.last_sem_class6;
                                }
                            }


                        }
                    }
                }else{
                    if (data.sem6higher >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class6,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class6;
                        }
                    } else if (data.sem6below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                            textvalue = {
                                y: v.sem_class6,
                                color: '#BF0B23'
                            };
                        } else {
                            textvalue = v.sem_class6;
                        }
                    } else {
                        if (data.sem6highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.sem_class6,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.sem_class6;
                            }

                        } else if (data.sem6belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.sem_class6,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.sem_class6;
                            }
                        } else {
                            if (v.sem_class6 < lclclass6) {
                                if (nalcltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class6,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class6;
                                }

                            } else if (v.sem_class6 > uclclass6) {
                                if (naucltest2 == 1) {
                                    textvalue = {
                                        y: v.sem_class6,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class6;
                                }

                            } else {
                                if (v.sem_class6 > data.is.class6) {
                                    if (naistest4 == 1) {
                                        textvalue = {
                                            y: v.sem_class6,
                                            color: '#BF0B23',
                                        };
                                    } else {
                                        textvalue = v.sem_class6;
                                    }

                                } else {
                                    textvalue = v.sem_class6;
                                }
                            }


                        }
                    }
                }

                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_sem);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });
   
            var textslopel = '';
            var textdff = ''; 
            // if (data.slopesem6 >= 0.5 || data.slopesem6 <= -0.5 || data.dffsem6 >= 3.5) {
            //     test5all++;
            //     chksemclass6all++;
            //     semclass6_5 = 'false';
            // } else {
            //     semclass6_5 = 'true';
            // }
            // if (data.slopesem6 != null) {
            //     if (data.slopesem6 >= 0.5 || data.slopesem6 <= -0.5) {
            //         textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem6 +
            //             '</td>';
            //     } else {
            //         textslopel += '<td>' + data.slopesem6 + '</td>';
            //     }
            // } else {
            //     textslopel += '<td>-</td>';
            // }
            // if (data.dffsem6 != null) {
            //     if (data.dffsem6 >= 3.5) {
            //         textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem6 +
            //             '</td>';
            //     } else {
            //         textdff += '<td>' + data.dffsem6 + '</td>';
            //     }
            // } else {
            //     textdff += '<td>-</td>';
            // }

            // **** Fixed an issue where notifications were incorrect. // 04-08-23 
            if (data.slopediff_sem6_SLOPE >= 0.5 || data.slopediff_sem6_SLOPE <= -0.5 || data.slopediff_sem6_DIFF >= 3.5) {
                test5all++;
                chksemclass6all++;
                semclass6_5 = 'false';
            } else {
                semclass6_5 = 'true';
            }
            if (data.slopediff_sem6_SLOPE != null) {
                if (data.slopediff_sem6_SLOPE >= 0.5 || data.slopediff_sem6_SLOPE <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopediff_sem6_SLOPE +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopediff_sem6_SLOPE + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.slopediff_sem6_DIFF != null) {
                if (data.slopediff_sem6_DIFF >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.slopediff_sem6_DIFF +
                        '</td>';
                } else {
                    textdff += '<td>' + data.slopediff_sem6_DIFF + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }

           
            var textsem6 = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countsem6 + '</td>' +
                '<td>' + data.avgsem6.toFixed(3) + '</td>' +
                '<td>' + data.stdsem6 + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';
            $('#dtsem6 tbody').html(textsem6);

            //-------------------------------- end sem class6 --------------------------//
        } else {
            // document.getElementById("show_sem").style.display = "none";
        }

        //-------------------------------- ic anion --------------------------//
        if (data.chkicshow == 1) {
            var chkicanionall = 0;
            var icanion1 = '';
            var icanion2 = '';
            var icanion3 = '';
            var icanion4 = '';
            var itemdata = data.icdatalab.length - 1;
            var itemdata1 = data.newicdatalab.length - 1;


            var test7 = data.icdatalaball.length - data.alarmtest3;
            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].anion == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].anion == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].anion == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].anion == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].anion == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].anion == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }

            if(data.newicdatalab[itemdata1]){
                var datalclanion = data.newicdatalab[itemdata1][2].anion;
                var datauclanion = data.newicdatalab[itemdata1][1].anion;
            }else{
                var datalclanion = null;
                var datauclanion = null;
            }

            if (data.icanionhigher >= data.alarmtest2) {
                icanion1 = 'false';
                sumalertall++;
                test2all++;
                chkicanionall++;
            } else if (data.icanionbelow >= data.alarmtest2) {
                icanion1 = 'false';
                sumalertall++;
                test2all++;
                chkicanionall++;
            } else {
                icanion1 = 'true';
            }

            if (data.icanionhighervs3 >= data.alarmtest3) {
                icanion2 = 'false';
                sumalertall++;
                test3all++;
                chkicanionall++;
            } else if (data.icanionbelowvs3 >= data.alarmtest3) {
                icanion2 = 'false';
                sumalertall++;
                test3all++;
                chkicanionall++;
            } else {
                icanion2 = 'true';
            }

            if(datalclanion == 'N/A' || datalclanion == null){
                icanion3 = 'true';
            }else{
                if (parseFloat(data.icdatalab[itemdata].ic_anion_up) < parseFloat(datalclanion)) {
                    if (nalcltest2 == 1) {
                        icanion3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkicanionall++;
                    } else {
                        icanion3 = 'true';
                    }
                } else if (parseFloat(data.icdatalab[itemdata].ic_anion_up) > parseFloat(datauclanion)) {
                    if (naucltest2 == 1) {
                        icanion3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkicanionall++;
                    } else {
                        icanion3 = 'true';
                    }

                } else {
                    icanion3 = 'true';

                }
            }

            if (data.icdatalab[itemdata].ic_anion_up > data.is.anion) {
                if (naistest4 == 1) {
                    icanion4 = 'false';
                    sumalertall++;
                    test4all++;
                    chkicanionall++;
                } else {
                    icanion4 = 'true';
                }


            } else {
                icanion4 = 'true';
            }

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                datest = [],
                len = data.model.length,
                lenvs = data.icdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.icdatalaball, function (k, v) {
                var textvalue = '';
                var lclanion = data.newicdatalab[ckvs][2].anion;
                var uclanion = data.newicdatalab[ckvs][1].anion;
                ckvs++;

                if (data.icanionhigher >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.ic_anion_up,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.ic_anion_up;
                    }
                } else if (data.icanionbelow >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.ic_anion_up,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.ic_anion_up;
                    }
                } else {
                    if (data.icanionhighervs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.ic_anion_up;
                            } else {
                                textvalue = {
                                    y: v.ic_anion_up,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.ic_anion_up;
                        }

                    } else if (data.icanionbelowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.ic_anion_up;
                            } else {
                                textvalue = {
                                    y: v.ic_anion_up,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.ic_anion_up;
                        }
                    } else {
                        if (parseFloat(v.ic_anion_up) < parseFloat(lclanion)) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.ic_anion_up,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_anion_up;
                            }

                        } else if (parseFloat(v.ic_anion_up) > parseFloat(uclanion)) {
                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.ic_anion_up,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_anion_up;
                            }

                        } else {
                            if (parseFloat(v.ic_anion_up) > parseFloat(data.is.anion)) {
                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.ic_anion_up,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.ic_anion_up;
                                }

                            } else {
                                textvalue = v.ic_anion_up;
                            }
                        }


                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_ic);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopeic_anion >= 0.5 || data.slopeic_anion <= -0.5 || data.dffic_anion >= 3.5) {
                test5all++;
                chkicanionall++;
                icanion5 = 'false';
            } else {
                icanion5 = 'true';
            }
            if (data.slopeic_anion != null) {
                if (data.slopeic_anion >= 0.5 || data.slopeic_anion <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopeic_anion +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopeic_anion + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffic_anion != null) {
                if (data.dffic_anion >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffic_anion +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffic_anion + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textic_anion = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countic_anion + '</td>' +
                '<td>' + data.avgic_anion.toFixed(3) + '</td>' +
                '<td>' + data.stdic_anion + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtic_anion tbody').html(textic_anion);
            //-------------------------------- end ic anion --------------------------//

            //--------------------------------  ic sulphat --------------------------//
            var chkicsulphatall = 0;
            var icsulphat1 = '';
            var icsulphat2 = '';
            var icsulphat3 = '';
            var icsulphat4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].sulphate == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].sulphate == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].sulphate == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].sulphate == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].sulphate == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].sulphate == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            var datalclsulphate = data.newicdatalab[itemdata1][2].sulphate;
            var datauclsulphate = data.newicdatalab[itemdata1][1].sulphate;
            if (data.sulphathigher >= data.alarmtest2) {
                icsulphat1 = 'false';
                sumalertall++;
                test2all++;
                chkicsulphatall++;
            } else if (data.sulphatbelow >= data.alarmtest2) {
                icsulphat1 = 'false';
                sumalertall++;
                test2all++;
                chkicsulphatall++;
            } else {
                icsulphat1 = 'true';
            }

            if (data.sulphathighervs3 >= data.alarmtest3) {
                icsulphat2 = 'false';
                sumalertall++;
                test3all++;
                chkicsulphatall
            } else if (data.sulphathighervs3 >= data.alarmtest3) {
                icsulphat2 = 'false';
                sumalertall++;
                test3all++;
                chkicsulphatall++;
            } else {
                icsulphat2 = 'true';
            }
            if (parseFloat(data.icdatalab[itemdata].ic_sulphate) < parseFloat(datalclsulphate)) {
                if (nalcltest2 == 1) {
                    icsulphat3 = 'false';
                    sumalertall++;
                    test1all++;
                    chkicsulphatall++;

                } else {
                    icsulphat3 = 'true';
                }

            } else if (data.icdatalab[itemdata].ic_sulphate > datauclsulphate) {
                if (naucltest2 == 1) {
                    icsulphat3 = 'false';
                    sumalertall++;
                    test1all++;
                    chkicsulphatall++;

                } else {
                    icsulphat3 = 'true';
                }

            } else {
                icsulphat3 = 'true';
            }
            if (data.icdatalab[itemdata].ic_sulphate > data.is.sulphate) {
                if (naistest4 == 1) {
                    icsulphat4 = 'false';
                    sumalertall++;
                    test4all++;
                    chkicsulphatall++;
                } else {
                    icsulphat4 = 'true';
                }

            } else {
                icsulphat4 = 'true';
            }
            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.icdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;
            $.each(data.icdatalaball, function (k, v) {
                var textvalue = '';
                var lclsulphate = data.newicdatalab[ckvs][2].sulphate;
                var uclsulphate = data.newicdatalab[ckvs][1].sulphate;
                ckvs++;

                if (data.sulphathigher >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.ic_sulphate,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.ic_sulphate;
                    }
                } else if (data.sulphatbelow >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.ic_sulphate,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.ic_sulphate;
                    }
                } else {
                    if (data.sulphathighervs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.ic_sulphate;
                            } else {
                                textvalue = {
                                    y: v.ic_sulphate,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.ic_sulphate;
                        }

                    } else if (data.sulphatbelowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.ic_sulphate;
                            } else {
                                textvalue = {
                                    y: v.ic_sulphate,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.ic_sulphate;
                        }
                    } else {
                        if (v.ic_sulphate < data.lcl.sulphate) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.ic_sulphate,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_sulphate;
                            }

                        } else if (v.ic_sulphate > data.ucl.sulphate) {
                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.ic_sulphate,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_sulphate;
                            }
                        } else {
                            if (v.ic_sulphate > data.is.sulphate) {
                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.ic_sulphate,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.ic_sulphate;
                                }

                            } else {
                                textvalue = v.ic_sulphate;
                            }
                        }


                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_ic);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopeic_sulphate >= 0.5 || data.slopeic_sulphate <= -0.5 || data.dffic_sulphate >= 3.5) {
                test5all++;
                chkicsulphatall++;
                icsulphat5 = 'false';
            } else {
                icsulphat5 = 'true';
            }
            if (data.slopeic_sulphate != null) {
                if (data.slopeic_sulphate >= 0.5 || data.slopeic_sulphate <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopeic_sulphate +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopeic_sulphate + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffic_sulphate != null) {
                if (data.dffic_sulphate >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffic_sulphate +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffic_sulphate + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textic_sulphate = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countic_sulphate + '</td>' +
                '<td>' + data.avgic_sulphate.toFixed(3) + '</td>' +
                '<td>' + data.stdic_sulphate + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtic_sulphate tbody').html(textic_sulphate);

            //-------------------------------- end ic sulphat --------------------------//

            //-------------------------------- ic cation --------------------------//
            var chkiccationall = 0;
            var iccation1 = '';
            var iccation2 = '';
            var iccation3 = '';
            var iccation4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].cation == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].cation == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].cation == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].cation == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].cation == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].cation == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }

            var datalclcation = data.newicdatalab[itemdata1][2].cation;
            var datauclcation = data.newicdatalab[itemdata1][1].cation;

            if (data.cationhigher >= data.alarmtest2) {
                iccation1 = 'false';
                sumalertall++;
                test2all++;
                chkiccationall++;

            } else if (data.cationbelow >= data.alarmtest2) {
                iccation1 = 'false';
                sumalertall++;
                test2all++;
                chkiccationall++;

            } else {
                iccation1 = 'true';
            }

            if (data.cationhighervs3 >= data.alarmtest3) {
                iccation2 = 'false';
                sumalertall++;
                test3all++;
                chkiccationall++;
            } else if (data.cationbelowvs3 >= data.alarmtest3) {
                iccation2 = 'false';
                sumalertall++;
                test3all++;
                chkiccationall++;
            } else {
                iccation2 = 'true';
            }

            if (data.icdatalab[itemdata].ic_cation_up < datalclcation) {
                if (nalcltest2 == 1) {
                    iccation3 = 'false';
                    sumalertall++;
                    test1all++;
                    chkiccationall++;
                    //alert(0);
                } else {
                    iccation3 = 'true';
                    //alert(1);
                }

            } else if (data.icdatalab[itemdata].ic_cation_up > datauclcation) {
                if (naucltest2 == 1) {
                    iccation3 = 'false';
                    sumalertall++;
                    test1all++;
                    chkiccationall++;
                } else {
                    iccation3 = 'true';
                }

            } else {
                iccation3 = 'true';

            }
            if (data.icdatalab[itemdata].ic_cation_up > data.is.cation) {
                if (naistest4 == 1) {
                    iccation4 = 'false';
                    sumalertall++;
                    test4all++;
                    chkiccationall++;

                } else {
                    iccation4 = 'true';

                }

            } else {
                iccation4 = 'true';
            }
            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.icdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.icdatalaball, function (k, v) {
                var textvalue = '';
                var lclcation = data.newicdatalab[ckvs][2].cation;
                var uclcation = data.newicdatalab[ckvs][1].cation;
                ckvs++;

                if (data.cationhigher >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.ic_cation_up,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.ic_cation_up;
                    }
                } else if (data.cationbelow >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.ic_cation_up,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.ic_cation_up;
                    }
                } else {
                    if (data.cationhighervs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.ic_cation_up;
                            } else {
                                textvalue = {
                                    y: v.ic_cation_up,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.ic_cation_up;
                        }

                    } else if (data.cationbelowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.ic_cation_up;
                            } else {
                                textvalue = {
                                    y: v.ic_cation_up,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.ic_cation_up;
                        }
                    } else {
                        if (v.ic_cation_up < lclcation) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.ic_cation_up,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_cation_up;
                            }
                        } else if (v.ic_cation_up > uclcation) {
                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.ic_cation_up,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_cation_up;
                            }

                        } else {
                            if (v.ic_cation_up > data.is.cation) {

                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.ic_cation_up,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.ic_cation_up;
                                }

                            } else {
                                textvalue = v.ic_cation_up;
                            }
                        }


                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_ic);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });
            $.each(data.model, function (k, v) {
                i++;
                if (v.id_model_type == 1) {
                    for (i = 0; i < lenvs; i++) {
                        animals.push(parseFloat(v.cation));
                    }
                }
                if (v.id_model_type == 2) {
                    $.each(data.newicdatalab, function (k, u) {
                        animals.push(parseFloat(u[0].cation));
                    });
                }
                if (v.id_model_type == 3) {
                    $.each(data.newicdatalab, function (k, u) {
                        animals.push(parseFloat(u[1].cation));
                    });
                }
                if (v.id_model_type == 4) {
                    $.each(data.newicdatalab, function (k, u) {
                        animals.push(parseFloat(u[2].cation));
                    });
                }
                if (v.id_model_type == 5) {
                    $.each(data.newicdatalab, function (k, u) {
                        animals.push(parseFloat(u[3].cation));
                    });
                }
                if (v.id_model_type == 6) {

                    for (i = 0; i < lenvs; i++) {
                        animals.push(parseFloat(v.cation));
                    }

                }
                var st = null;
                if (v.id_model_type == 5) {

                    st = 'dot';
                }
                series.push({

                    name: v.name,
                    data: animals,
                    color: v.color,
                    lineWidth: 2,
                    marker: {
                        enabled: false
                    },
                    enableMouseTracking: false,
                    label: {
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
            series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
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

            var textslopel = '';
            var textdff = '';
            if (data.slopeic_cation >= 0.5 || data.slopeic_cation <= -0.5 || data.dffic_cation >= 3.5) {
                test5all++;
                chkiccationall++;
                iccation5 = 'false';
            } else {
                iccation5 = 'true';
            }
            if (data.slopeic_cation != null) {
                if (data.slopeic_cation >= 0.5 || data.slopeic_cation <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopeic_cation +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopeic_cation + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffic_cation != null) {
                if (data.dffic_cation >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffic_cation +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffic_cation + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }

            var textic_cation = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countic_cation + '</td>' +
                '<td>' + data.avgic_cation + '</td>' +
                '<td>' + data.stdic_cation + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtic_cation tbody').html(textic_cation);
            //-------------------------------- end ic cation --------------------------//
        } else {
            // document.getElementById("show_ic").style.display = "none";
        }

        //-------------------------------- gcms1 --------------------------//
        if (data.chkgcmsshow == 1) {
            var chkgcms1all = 0;
            var gcms1_1 = '';
            var gcms1_2 = '';
            var gcms1_3 = '';
            var gcms1_4 = '';
            var gcms1_6 = '';
            var itemdata = data.gcmsdatalab.length - 1;
            var itemdata1 = data.newgcmsdatalab.length - 1;


            var test7 = data.gcmsdatalaball.length - data.alarmtest3;
            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].outgassing == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].outgassing == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].outgassing == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].outgassing == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].outgassing == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].outgassing == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            // var datalcloutgassing = data.newgcmsdatalab[itemdata1][2].outgassing;
            // var dataucloutgassing = data.newgcmsdatalab[itemdata1][1].outgassing;
            if(data.newgcmsdatalab[itemdata1]){
                var datalcloutgassing = data.newgcmsdatalab[itemdata1][2].outgassing;
                var dataucloutgassing = data.newgcmsdatalab[itemdata1][1].outgassing;
            }else{
                var datalcloutgassing = null;
                var dataucloutgassing = null;
            }
            if (data.gcms1higher >= data.alarmtest2) {
                gcms1_1 = 'false';
                sumalertall++;
                test2all++;
                chkgcms1all++;
            } else if (data.gcms1below >= data.alarmtest2) {
                gcms1_1 = 'false';
                sumalertall++;
                test2all++;
                chkgcms1all++;
            } else {
                gcms1_1 = 'true';
            }

            if (data.gcms1highervs3 >= data.alarmtest3) {
                gcms1_2 = 'false';
                sumalertall++;
                test3all++;
                chkgcms1all++;
            } else if (data.gcms1belowvs3 >= data.alarmtest3) {
                gcms1_2 = 'false';
                sumalertall++;
                test3all++;
                chkgcms1all++;
            } else {
                gcms1_2 = 'true';
            }
            if(datalcloutgassing != null && dataucloutgassing != null){
                if (data.gcmsdatalab[itemdata].gcms_outgassing < datalcloutgassing) {
                    if (nalcltest2 == 1) {
                        gcms1_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkgcms1all++;
                    } else {
                        gcms1_3 = 'true';
                    }

                } else if (data.gcmsdatalab[itemdata].gcms_outgassing > dataucloutgassing) {
                    if (naucltest2 == 1) {
                        gcms1_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkgcms1all++;
                    } else {
                        gcms1_3 = 'true';
                    }

                } else {
                    gcms1_3 = 'true';
                }
            }else {
                gcms1_3 = 'true';
            }
            if (data.gcmsdatalab[itemdata].gcms_outgassing > data.is.outgassing) {
                if (naistest4 == 1) {
                    gcms1_4 = 'false';
                    sumalertall++;
                    test4all++;
                    chkgcms1all++;
                } else {
                    gcms1_4 = 'true';
                }


            } else {
                gcms1_4 = 'true';
            }
            var lowhigh = parseFloat(data.gcmsdatalab[itemdata].gcms_low_mw) + parseFloat(data.gcmsdatalab[itemdata].gcms_high_mw);

            var outgassingtest6 = parseFloat(data.gcmsdatalab[itemdata].gcms_outgassing) - parseFloat(lowhigh);
            // alert(outgassingtest6);
            if (outgassingtest6 > 20 || outgassingtest6 < -20) {
                //sumalertall++:
                test6all++;
                chkgcms1all++;
                gcms1_6 = 'false';
            } else {
                gcms1_6 = 'true';
            }

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                datest = [],
                len = data.model.length,
                lenvs = data.gcmsdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.gcmsdatalaball, function (k, v) {
                var textvalue = '';
                var lcloutgassing = data.newgcmsdatalab[ckvs][2].outgassing;
                var ucloutgassing = data.newgcmsdatalab[ckvs][1].outgassing;
                ckvs++;

                if (data.gcms1higher >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.gcms_outgassing,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.gcms_outgassing;
                    }
                } else if (data.gcms1below >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.gcms_outgassing,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.gcms_outgassing;
                    }
                } else {
                    if (data.gcms1highervs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.gcms_outgassing;
                            } else {
                                textvalue = {
                                    y: v.gcms_outgassing,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.gcms_outgassing;
                        }

                    } else if (data.gcms1belowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.gcms_outgassing;
                            } else {
                                textvalue = {
                                    y: v.gcms_outgassing,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.gcms_outgassing;
                        }
                    } else {
                        if (v.gcms_outgassing < lcloutgassing) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.gcms_outgassing,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_outgassing;
                            }

                        } else if (v.gcms_outgassing > ucloutgassing) {
                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.gcms_outgassing,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_outgassing;
                            }

                        } else {
                            if (v.gcms_outgassing > data.is.outgassing) {
                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.gcms_outgassing,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.gcms_outgassing;
                                }

                            } else {
                                textvalue = v.gcms_outgassing;
                            }
                        }


                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_gcms);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';

            // if (data.slopegcms_out >= 0.5 || data.slopegcms_out <= -0.5 || data.dffgcms_out >= 3.5) {
            //     test5all++;
            //     chkgcms1all++;

            //     gcms1_5 = 'false';
            // } else {
            //     gcms1_5 = 'true';
            // }
            // if (data.slopegcms_out != null) {
            //     if (data.slopegcms_out >= 0.5 || data.slopegcms_out <= -0.5) {
            //         textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopegcms_out +
            //             '</td>';
            //     } else {
            //         textslopel += '<td>' + data.slopegcms_out + '</td>';
            //     }
            // } else {
            //     textslopel += '<td>-</td>';
            // }
            // if (data.dffgcms_out != null) {
            //     if (data.dffgcms_out >= 3.5) {
            //         textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffgcms_out +
            //             '</td>';
            //     } else {
            //         textdff += '<td>' + data.dffgcms_out + '</td>';
            //     }
            // } else {
            //     textdff += '<td>-</td>';
            // }

            if (data.slopediff_Outgass_SLOPE >= 0.5 || data.slopediff_Outgass_SLOPE <= -0.5 || data.slopediff_Outgass_DIFF >= 3.5) {
                test5all++;
                chkgcms1all++;

                gcms1_5 = 'false';
            } else {
                gcms1_5 = 'true';
            }
            if (data.slopediff_Outgass_SLOPE != null) {
                if (data.slopediff_Outgass_SLOPE >= 0.5 || data.slopediff_Outgass_SLOPE <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopediff_Outgass_SLOPE +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopediff_Outgass_SLOPE + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.slopediff_Outgass_DIFF != null) {
                if (data.slopediff_Outgass_DIFF >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF ">' + data.slopediff_Outgass_DIFF +
                        '</td>';
                } else {
                    textdff += '<td>' + data.slopediff_Outgass_DIFF + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textgcms_out = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countgcms_out + '</td>' +
                '<td>' + data.avggcms_out.toFixed(3) + '</td>' +
                '<td>' + data.stdgcms_out + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            $('#dtgcms_out tbody').html(textgcms_out);
            //--------------------------------end gcms1 --------------------------//

            //-------------------------------- gcms2 --------------------------//
            var chkgcms2all = 0;
            var gcms2_1 = '';
            var gcms2_2 = '';
            var gcms2_3 = '';
            var gcms2_4 = '';
            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].alkylacrylate == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].alkylacrylate == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].alkylacrylate == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].alkylacrylate == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].alkylacrylate == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].alkylacrylate == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            // var datalclalkylacrylate = data.newgcmsdatalab[itemdata1][2].alkylacrylate;
            // var datauclalkylacrylate = data.newgcmsdatalab[itemdata1][1].alkylacrylate;
            if(data.newgcmsdatalab[itemdata1]){
                var datalclalkylacrylate = data.newgcmsdatalab[itemdata1][2].alkylacrylate;
                var datauclalkylacrylate = data.newgcmsdatalab[itemdata1][1].alkylacrylate;
            }else{
                var datalclalkylacrylate = null;
                var datauclalkylacrylate = null;
            }
            if (data.gcms2higher >= data.alarmtest2) {
                gcms2_1 = 'false';
                sumalertall++;
                test2all++;
                chkgcms2all++;
            } else if (data.gcms2below >= data.alarmtest2) {

                gcms2_1 = 'false';
                sumalertall++;
                test2all++;
                chkgcms2all++;
            } else {
                gcms2_1 = 'true';
            }

            if (data.gcms2highervs3 >= data.alarmtest3) {
                gcms2_2 = 'false';
                sumalertall++;
                test3all++;
                chkgcms2all++;
            } else if (data.gcms2belowvs3 >= data.alarmtest3) {
                gcms2_2 = 'false';
                sumalertall++;
                test3all++;
                chkgcms2all++;
            } else {
                gcms2_2 = 'true';
            }
            if(datalclalkylacrylate != null && datauclalkylacrylate != null){
                if (data.gcmsdatalab[itemdata].gcms_smear < datalclalkylacrylate) {
                    if (nalcltest2 == 1) {
                        gcms2_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkgcms2all++;
                    } else {
                        gcms2_3 = 'true';
                    }

                } else if (data.gcmsdatalab[itemdata].gcms_smear > datauclalkylacrylate) {
                    if (naucltest2 == 1) {
                        gcms2_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkgcms2all++;
                    } else {
                        gcms2_3 = 'true';
                    }

                } else {
                    gcms2_3 = 'true';

                }
            }else {
                gcms2_3 = 'true';
            }
            if (data.gcmsdatalab[itemdata].gcms_smear > data.is.alkylacrylate) {

                if (naistest4 == 1) {
                    gcms2_4 = 'false';
                    sumalertall++;
                    test4all++;
                    chkgcms2all++;
                } else {
                    gcms2_4 = 'true';
                }


            } else {
                gcms2_4 = 'true';
            }
            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.gcmsdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.gcmsdatalaball, function (k, v) {
                var textvalue = '';
                var lclalkylacrylate = data.newgcmsdatalab[ckvs][2].alkylacrylate;
                var uclalkylacrylate = data.newgcmsdatalab[ckvs][1].alkylacrylate;
                ckvs++;

                if (data.gcms2higher >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.gcms_smear,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.gcms_smear;
                    }
                } else if (data.gcms2below >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.gcms_smear,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.gcms_smear;
                    }
                } else {
                    if (data.gcms2highervs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.gcms_smear;
                            } else {
                                textvalue = {
                                    y: v.gcms_smear,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.gcms_smear;
                        }

                    } else if (data.gcms2belowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.gcms_smear;
                            } else {
                                textvalue = {
                                    y: v.gcms_smear,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.gcms_smear;
                        }
                    } else {
                        if (v.gcms_smear < lclalkylacrylate) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.gcms_smear,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_smear;
                            }

                        } else if (v.gcms_smear > uclalkylacrylate) {
                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.gcms_smear,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_smear;
                            }

                        } else {
                            if (v.gcms_smear > data.is.alkylacrylate) {
                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.gcms_smear,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.gcms_smear;
                                }

                            } else {
                                textvalue = v.gcms_smear;
                            }
                        }


                    }
                }
                datalab[vs] = textvalue;

                var fmdate = Getdate(v.date_gcms);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopegcms_smear >= 0.5 || data.slopegcms_smear <= -0.5 || data.dffgcms_smear >= 3.5) {
                test5all++;
                chkgcms2all++;
                gcms2_5 = 'false';
            } else {
                gcms2_5 = 'true';
            }
            if (data.slopegcms_smear != null) {
                if (data.slopegcms_smear >= 0.5 || data.slopegcms_smear <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopegcms_smear +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopegcms_smear + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffgcms_smear != null) {
                if (data.dffgcms_smear >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffgcms_smear +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffgcms_smear + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textgcms_smear = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countgcms_smear + '</td>' +
                '<td>' + data.avggcms_smear.toFixed(3) + '</td>' +
                '<td>' + data.stdgcms_smear + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            // $('#dtgcms_smear tbody').html(textgcms_smear);
            //--------------------------------end gcms2 --------------------------//

            //-------------------------------- gcms3 --------------------------//
            var chkgcms3all = 0;
            var gcms3_1 = '';
            var gcms3_2 = '';
            var gcms3_3 = '';
            var gcms3_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].low_MW == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].low_MW == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].low_MW == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].low_MW == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].low_MW == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].low_MW == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            // var datalcllow_MW = data.newgcmsdatalab[itemdata1][2].low_MW;
            // var dataucllow_MW = data.newgcmsdatalab[itemdata1][1].low_MW;
            if(data.newgcmsdatalab[itemdata1]){
                var datalcllow_MW = data.newgcmsdatalab[itemdata1][2].low_MW;
                var dataucllow_MW = data.newgcmsdatalab[itemdata1][1].low_MW;
            }else{
                var datalcllow_MW = null;
                var dataucllow_MW = null;
            }
            if (data.gcms3higher >= data.alarmtest2) {
                gcms3_1 = 'false';
                sumalertall++;
                test2all++;
                chkgcms3all++;
            } else if (data.gcms3below >= data.alarmtest2) {
                gcms3_1 = 'false';
                sumalertall++;
                test2all++;
                chkgcms3all++;
            } else {
                gcms3_1 = 'true';
            }

            if (data.gcms3highervs3 >= data.alarmtest3) {
                gcms3_2 = 'false';
                sumalertall++;
                test3all++;
                chkgcms3all++;
            } else if (data.gcms3belowvs3 >= data.alarmtest3) {
                gcms3_2 = 'false';
                sumalertall++;
                test3all++;
                chkgcms3all++;
            } else {
                gcms3_2 = 'true';
            }
            if( datalcllow_MW != null && dataucllow_MW != null){
                if (data.gcmsdatalab[itemdata].gcms_low_mw < datalcllow_MW) {
                    if (nalcltest2 == 1) {
                        gcms3_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkgcms3all++;
                    } else {
                        gcms3_3 = 'true';
                    }
                } else if (data.gcmsdatalab[itemdata].gcms_low_mw > dataucllow_MW) {

                    if (naucltest2 == 1) {
                        gcms3_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkgcms3all++;
                    } else {
                        gcms3_3 = 'true';
                    }

                } else {
                    gcms3_3 = 'true';

                }
            } else {
                gcms3_3 = 'true';
            }

            if (data.gcmsdatalab[itemdata].gcms_low_mw > data.is.low_MW) {
                if (naistest4 == 1) {
                    gcms3_4 = 'false';
                    sumalertall++;
                    test4all++;
                    chkgcms3all++;
                } else {
                    gcms3_4 = 'true';
                }


            } else {
                gcms3_4 = 'true';
            }
            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.gcmsdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.gcmsdatalaball, function (k, v) {
                var textvalue = '';
                var lcllow_MW = data.newgcmsdatalab[ckvs][2].low_MW;
                var ucllow_MW = data.newgcmsdatalab[ckvs][1].low_MW;
                ckvs++;

                if (data.gcms3higher >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.gcms_low_mw,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.gcms_low_mw;
                    }
                } else if (data.gcms3below >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.gcms_low_mw,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.gcms_low_mw;
                    }
                } else {
                    if (data.gcms3highervs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.gcms_low_mw;
                            } else {
                                textvalue = {
                                    y: v.gcms_low_mw,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.gcms_low_mw;
                        }

                    } else if (data.gcms3belowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.gcms_low_mw;
                            } else {
                                textvalue = {
                                    y: v.gcms_low_mw,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.gcms_low_mw;
                        }
                    } else {
                        if (v.gcms_low_mw < lcllow_MW) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.gcms_low_mw,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_low_mw;
                            }

                        } else if (v.gcms_low_mw > ucllow_MW) {

                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.gcms_low_mw,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_low_mw;
                            }

                        } else {
                            if (v.gcms_low_mw > data.is.low_MW) {
                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.gcms_low_mw,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.gcms_low_mw;
                                }

                            } else {
                                textvalue = v.gcms_low_mw;
                            }
                        }


                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_gcms);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopegcms_low_mw >= 0.5 || data.slopegcms_low_mw <= -0.5 || data.dffgcms_low_mw >= 3.5) {
                test5all++;
                chkgcms3all++;
                gcms3_5 = 'false';
            } else {
                gcms3_5 = 'true';
            }
            if (data.slopegcms_low_mw != null) {
                if (data.slopegcms_low_mw >= 0.5 || data.slopegcms_low_mw <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopegcms_low_mw +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopegcms_low_mw + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffgcms_low_mw != null) {
                if (data.dffgcms_low_mw >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffgcms_low_mw +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffgcms_low_mw + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textgcms_low_mw = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countgcms_low_mw + '</td>' +
                '<td>' + data.avggcms_low_mw.toFixed(3) + '</td>' +
                '<td>' + data.stdgcms_low_mw + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            $('#dtgcms_low_mw tbody').html(textgcms_low_mw);
            //--------------------------------end gcms3 --------------------------//

            //-------------------------------- gcms4 --------------------------//
            var chkgcms4all = 0;
            var gcms4_1 = '';
            var gcms4_2 = '';
            var gcms4_3 = '';
            var gcms4_4 = '';

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].high_MW == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].high_MW == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].high_MW == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].high_MW == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].high_MW == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].high_MW == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            }
            // var datalclhigh_MW = data.newgcmsdatalab[itemdata1][2].high_MW;
            // var datauclhigh_MW = data.newgcmsdatalab[itemdata1][1].high_MW;
            if(data.newgcmsdatalab[itemdata1]){
                var datalclhigh_MW = data.newgcmsdatalab[itemdata1][2].high_MW;
                var datauclhigh_MW = data.newgcmsdatalab[itemdata1][1].high_MW;
            }else{
                var datalclhigh_MW = null;
                var datauclhigh_MW = null;
            }

            if (data.gcms4higher >= data.alarmtest2) {
                gcms4_1 = 'false';
                sumalertall++;
                test2all++;
                chkgcms4all++;
            } else if (data.gcms4below >= data.alarmtest2) {
                gcms4_1 = 'false';
                sumalertall++;
                test2all++;
                chkgcms4all++;
            } else {
                gcms4_1 = 'true';
            }

            if (data.gcms4highervs3 >= data.alarmtest3) {
                gcms4_2 = 'false';
                sumalertall++;
                test3all++;
                chkgcms4all++;
            } else if (data.gcms4belowvs3 >= data.alarmtest3) {
                gcms4_2 = 'false';
                sumalertall++;
                test3all++;
                chkgcms4all++;
            } else {
                gcms4_2 = 'true';
            }

            // console.log('gcms4_1 >>'+ gcms4_1);
            // console.log('gcms4_2 >>'+ gcms4_2);
            if(parseInt(datalclhigh_MW) > 0){
                if (data.gcmsdatalab[itemdata].gcms_high_mw < datalclhigh_MW) {
                    if (nalcltest2 == 1) {
                        gcms4_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkgcms4all++;
                    } else {
                        gcms4_3 = 'true';
                    }

                } else if (data.gcmsdatalab[itemdata].gcms_high_mw > datauclhigh_MW) {
                    if (naucltest2 == 1) {
                        gcms4_3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkgcms4all++;
                    } else {
                        gcms4_3 = 'true';
                    }

                } else {
                    gcms4_3 = 'true';

                }
        }else{
            gcms4_3 = 'true';
        }

            if (data.gcmsdatalab[itemdata].gcms_high_mw > data.is.high_MW) {
                if (naistest4 == 1) {
                    gcms4_4 = 'false';
                    sumalertall++;
                    test4all++;
                    chkgcms4all++;
                } else {
                    gcms4_4 = 'true';
                }


            } else {
                gcms4_4 = 'true';
            }

            // console.log('gcms4_3 >>'+ gcms4_3);
            // console.log('gcms4_4 >>'+ gcms4_4);

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                len = data.model.length,
                lenvs = data.gcmsdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.gcmsdatalaball, function (k, v) {
                var textvalue = '';
                var lclhigh_MW = data.newgcmsdatalab[ckvs][2].high_MW;
                var uclhigh_MW = data.newgcmsdatalab[ckvs][1].high_MW;
                ckvs++;

                if (data.gcms4higher >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.gcms_high_mw,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.gcms_high_mw;
                    }
                } else if (data.gcms4below >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.gcms_high_mw,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.gcms_high_mw;
                    }
                } else {
                    if (data.gcms4highervs3 >= data.alarmtest3) {

                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.gcms_high_mw;
                            } else {
                                textvalue = {
                                    y: v.gcms_high_mw,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.gcms_high_mw;
                        }

                    } else if (data.gcms4belowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.gcms_high_mw;
                            } else {
                                textvalue = {
                                    y: v.gcms_high_mw,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.gcms_high_mw;
                        }
                    } else {
                        if (v.gcms_high_mw < lclhigh_MW) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.gcms_high_mw,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_high_mw;
                            }

                        } else if (v.gcms_high_mw > uclhigh_MW) {
                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.gcms_high_mw,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_high_mw;
                            }

                        } else {
                            if (v.gcms_high_mw > data.is.high_MW) {
                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.gcms_high_mw,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.gcms_high_mw;
                                }

                            } else {
                                textvalue = v.gcms_high_mw;
                            }
                        }


                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_gcms);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });


            var textslopel = '';
            var textdff = '';
            if (data.slopegcms_high_mw >= 0.5 || data.slopegcms_high_mw <= -0.5 || data.dffgcms_high_mw >= 3.5) {
                test5all++;
                chkgcms4all++;
                gcms4_5 = 'false';
            } else {
                gcms4_5 = 'true';
            }
            if (data.slopegcms_high_mw != null) {
                if (data.slopegcms_high_mw >= 0.5 || data.slopegcms_high_mw <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopegcms_high_mw +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopegcms_high_mw + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffgcms_high_mw != null) {
                if (data.dffgcms_high_mw >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffgcms_high_mw +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffgcms_high_mw + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textgcms_high_mw = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countgcms_high_mw + '</td>' +
                '<td>' + data.avggcms_high_mw.toFixed(3) + '</td>' +
                '<td>' + data.stdgcms_high_mw + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            $('#dtgcms_high_mw tbody').html(textgcms_high_mw);
            //--------------------------------end gcms4 --------------------------//
        } else {
            // document.getElementById("show_gcms").style.display = "none";
        }

        //-------------------------------- ftir --------------------------//
        if (data.chkftirshow == 1) {
            var chkftirall = 0;
            var ftir1 = '';
            var ftir2 = '';
            var ftir3 = '';
            var ftir4 = '';
            var itemdata = data.ftirdatalab.length - 1;
            var itemdata1 = data.newftirdatalab.length - 1;

            var naucltest2 = 0;
            var nalcltest2 = 0;
            var naistest4 = 0;
            if (data.model[2].ftir == "N/A") {
                naucltest2 = 0;
            } else if (data.model[2].ftir == "TBD") {
                naucltest2 = 0;
            } else {
                naucltest2 = 1;
            }
            if (data.model[3].ftir == "N/A") {
                nalcltest2 = 0;
            } else if (data.model[3].ftir == "TBD") {
                nalcltest2 = 0;
            } else {
                nalcltest2 = 1;
            }
            if (data.model[5].ftir == "N/A") {
                naistest4 = 0;
            } else if (data.model[5].ftir == "TBD") {
                naistest4 = 0;
            } else {
                naistest4 = 1;
            } 
            var test7 = data.ftirdatalaball.length - data.alarmtest3;

            // var datalclftir = data.newftirdatalab[itemdata1][2].ftir;
            // var datauclftir = data.newftirdatalab[itemdata1][1].ftir;
            if(data.newftirdatalab[itemdata1]){
                var datalclftir = data.newftirdatalab[itemdata1][2].ftir;
                var datauclftir = data.newftirdatalab[itemdata1][1].ftir;
            }else{
                var datalclftir = null;
                var datauclftir = null;
            }

            if (data.ftirhigher >= data.alarmtest2) {
                ftir1 = 'false';
                sumalertall++;
                test2all++;
                chkftirall++;
            } else if (data.ftirbelow >= data.alarmtest2) {
                ftir1 = 'false';
                sumalertall++;
                test2all++;
                chkftirall++;
            } else {
                ftir1 = 'true';
            }

            if (data.ftirhighervs3 >= data.alarmtest3) {
                ftir2 = 'false';
                sumalertall++;
                test3all++;
                chkftirall++;
            } else if (data.ftirbelowvs3 >= data.alarmtest3) {
                ftir2 = 'false';
                sumalertall++;
                test3all++;
                chkftirall++;
            } else {
                ftir2 = 'true';
            }
            if(datalclftir != null && datauclftir != null){
                if (data.ftirdatalab[itemdata].ftir_silicone < datalclftir) {

                    if (nalcltest2 == 1) {
                        ftir3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkftirall++;

                    } else {
                        ftir3 = 'true';
                    }

                } else if (data.ftirdatalab[itemdata].ftir_silicone > datauclftir) {
                    if (naucltest2 == 1) {
                        ftir3 = 'false';
                        sumalertall++;
                        test1all++;
                        chkftirall++;
                    } else {
                        ftir3 = 'true';
                    }
                } else {
                    ftir3 = 'true';
                }
            } else {
                ftir3 = 'true';
            }
            if (data.ftirdatalab[itemdata].ftir_silicone > data.is.ftir) {
                if (naistest4 == 1) {
                    ftir4 = 'false';
                    sumalertall++;
                    test4all++;
                    chkftirall++;
                } else {
                    ftir4 = 'true';
                }


            } else {
                ftir4 = 'true';
            }

            var array = [10, 20, 30, 40, 50];
            var series = [],
                animals = [],
                datalab = [],
                datest = [],
                len = data.model.length,
                lenvs = data.ftirdatalaball.length,
                i = 0;
            ckvs = 0;
            vs = 0;
            var sumvs2 = lenvs - data.alarmtest2;
            var sumvs3 = lenvs - data.alarmtest3y;

            $.each(data.ftirdatalaball, function (k, v) {
                var textvalue = '';
                var lclftir = data.newftirdatalab[ckvs][2].ftir;
                var uclftir = data.newftirdatalab[ckvs][1].ftir;
                ckvs++;

                if (data.ftirhigher >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.ftir_silicone,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.ftir_silicone;
                    }
                } else if (data.ftirbelow >= data.alarmtest2) {
                    if (ckvs > sumvs2) {
                        textvalue = {
                            y: v.ftir_silicone,
                            color: '#BF0B23'
                        };
                    } else {
                        textvalue = v.ftir_silicone;
                    }
                } else {
                    if (data.ftirhighervs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.ftir_silicone;
                            } else {
                                textvalue = {
                                    y: v.ftir_silicone,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.ftir_silicone;
                        }

                    } else if (data.ftirbelowvs3 >= data.alarmtest3) {
                        if (ckvs > sumvs3) {
                            if (ckvs == test7) {
                                textvalue = v.ftir_silicone;
                            } else {
                                textvalue = {
                                    y: v.ftir_silicone,
                                    color: '#BF0B23'
                                };
                            }
                        } else {
                            textvalue = v.ftir_silicone;
                        }
                    } else {
                        if (v.ftir_silicone < lclftir) {
                            if (nalcltest2 == 1) {
                                textvalue = {
                                    y: v.ftir_silicone,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ftir_silicone;
                            }

                        } else if (v.ftir_silicone > uclftir) {
                            if (naucltest2 == 1) {
                                textvalue = {
                                    y: v.ftir_silicone,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ftir_silicone;
                            }

                        } else {
                            if (v.ftir_silicone > data.is.ftir) {
                                if (naistest4 == 1) {
                                    textvalue = {
                                        y: v.ftir_silicone,
                                        color: '#BF0B23',
                                    };
                                } else {
                                    textvalue = v.ftir_silicone;
                                }

                            } else {
                                textvalue = v.ftir_silicone;
                            }
                        }
                    }
                }
                datalab[vs] = textvalue;
                var fmdate = Getdate(v.date_ftir);
                datest[vs] = fmdate + ' ' + v.lotnumber_lab;
                vs++;
            });

            var textslopel = '';
            var textdff = '';
            if (data.slopeftir_sc >= 0.5 || data.slopeftir_sc <= -0.5 || data.dffftir_sc >= 3.5) {
                test5all++;
                chkftirall++;
                ftir5 = 'false';
            } else {
                ftir5 = 'true';
            }
            if (data.slopeftir_sc != null) {
                if (data.slopeftir_sc >= 0.5 || data.slopeftir_sc <= -0.5) {
                    textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopeftir_sc +
                        '</td>';
                } else {
                    textslopel += '<td>' + data.slopeftir_sc + '</td>';
                }
            } else {
                textslopel += '<td>-</td>';
            }
            if (data.dffftir_sc != null) {
                if (data.dffftir_sc >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffftir_sc +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffftir_sc + '</td>';
                }
            } else {
                textdff += '<td>-</td>';
            }
            var textftir_sc = '<tr>' +
                '<td>' + data.modelname.name_model + '</td>' +
                '<td>' + data.modelname.part + '</td>' +
                '<td>' + data.modelname.part_number + '</td>' +
                '<td>' + data.countftir_sc + '</td>' +
                '<td>' + data.avgftir_sc.toFixed(3) + '</td>' +
                '<td>' + data.stdftir_sc + '</td>' +
                '<td>' + data.oqalot.save_date + '</td>' +
                textslopel + textdff +
                '</tr>';

            $('#dtftir_sc tbody').html(textftir_sc);
            //--------------------------------end ftir --------------------------//
        } else {
            // document.getElementById("show_ftir").style.display = "none";
        }


        if (chklpcall >= 1) {
            key_data.push({
                'LPC': [lpc3, lpc1, lpc2, lpc4, lpc5, 'true']
            })
        } else {
            var lpcshow = '';
        }
        //  alert(lpcshow);
        if (chksemclass1all >= 1) {
            key_data.push({
                'SEM Class 1': [semclass1_3, semclass1_1, semclass1_2, semclass1_4, semclass1_5, 'true']
            })
        } else {
            var semclass1show = '';
        }
        if (chksemclass2all >= 1) {
            key_data.push({
                'SEM Class 2': [semclass2_3, semclass2_1, semclass2_2, semclass2_4, semclass2_5, 'true']
            })

        } else {
            var semclass2show = '';
        }
        if (chksemclass3all >= 1) {
            key_data.push({
                'SEM Class 3': [semclass3_3, semclass3_1, semclass3_2, semclass3_4, semclass3_5, 'true']
            })

        } else {
            var semclass3show = '';
        }
        if (chksemclass31all >= 1) {
            key_data.push({
                'SEM Class 3.1': [semclass31_3, semclass31_1, semclass31_2, semclass31_4, semclass31_5, 'true']
            })
        } else {
            var semclass31show = '';
        }
        if (chksemclass32all >= 1) {
            key_data.push({
                'SEM Class 3.2': [semclass32_3, semclass32_1, semclass32_2, semclass32_4, semclass32_5, 'true']
            })
        } else {
            var semclass32show = '';
        }
        if (chksemclass33all >= 1) {
            key_data.push({
                'SEM Class 3.3': [semclass33_3, semclass33_1, semclass33_2, semclass33_4, semclass33_5, 'true']
            })

        } else {
            var semclass33show = '';
        }
        if (chksemclass4all >= 1) {
            key_data.push({
                'SEM Class 4': [semclass4_3, semclass4_1, semclass4_2, semclass4_4, semclass4_5, 'true']
            })

        } else {
            var semclass4show = '';
        }
        if (chksemclass5all >= 1) {
            key_data.push({
                'SEM Class 5': [semclass5_3, semclass5_1, semclass5_2, semclass5_4, semclass5_5, 'true']
            })
        } else {
            var semclass5show = '';
        }
        if (chksemclass51all >= 1) {
            key_data.push({
                'SEM Class 5.1': [semclass51_3, semclass51_1, semclass51_2, semclass51_4, semclass51_5, 'true']
            })

        } else {
            var semclass51show = '';
        }
        if (chksemclass6all >= 1) {
            key_data.push({
                'SEM Class 6': [semclass6_3, semclass6_1, semclass6_2, semclass6_4, semclass6_5, 'true']
            })
        } else {
            var semclass6show = '';
        } 
        if (chkicanionall >= 1) {
            // console.log('IC Anion =>');
            // console.log(icanion3, icanion1, icanion2, icanion4, icanion5);
            key_data.push({
                'IC Anion': [icanion3, icanion1, icanion2, icanion4, icanion5, 'true']
            })

        } else {
            var icanionshow = '';
        }
        if (chkicsulphatall >= 1) {
            // console.log('IC Sulphat =>');
            // console.log(icsulphat3, icsulphat1, icsulphat2, icsulphat4, icsulphat5);
            key_data.push({
                'IC Sulphat': [icsulphat3, icsulphat1, icsulphat2, icsulphat4, icsulphat5, 'true']
            })

        } else {
            var icsulphatshow = '';
        }
        if (chkiccationall >= 1) {
            // console.log('IC Cation =>');
            // console.log(iccation3, iccation1, iccation2, iccation4, iccation5);
            key_data.push({
                'IC Cation': [iccation3, iccation1, iccation2, iccation4, iccation5, 'true']
            })
        } else {
            var iccationshow = '';
        }
        if (chkgcms1all >= 1) {
            key_data.push({
                'GCMS Outgassing': [gcms1_3, gcms1_1, gcms1_2, gcms1_4, gcms1_5, gcms1_6]
            })

        } else {
            var gcms1show = '';
        }
        if (chkgcms2all >= 1) {
            key_data.push({
                'GCMS Smear': [gcms2_3, gcms2_1, gcms2_2, gcms2_4, gcms2_5, 'true']
            })
        } else {
            var gcms2show = '';
        }
        if (chkgcms3all >= 1) {
            key_data.push({
                'GCMS Low MW': [gcms3_3, gcms3_1, gcms3_2, gcms3_4, gcms3_5, 'true']
            })
        } else {
            var gcms3show = '';
        }
        if (chkgcms4all >= 1) {
            key_data.push({
                'GCMS High MW': [gcms4_3, gcms4_1, gcms4_2, gcms4_4, gcms4_5, 'true']
            })

        } else {
            var gcms4show = '';
        }
        if (chkftirall >= 1) {
            key_data.push({
                'FTIR': [ftir3, ftir1, ftir2, ftir4, ftir5, 'true']
            })
        } else {
            var ftirshow = '';
        }
        // LPC

        // SEM Class 1
        // SEM Class 2
        // SEM Class 3
        // SEM Class 3.1
        // SEM Class 3.2
        // SEM Class 3.3
        // SEM Class 4
        // SEM Class 5
        // SEM Class 5.1
        // SEM Class 6

        // IC Anion
        // IC Sulphat
        // IC Cation

        // GCMS Outgassing
        // GCMS Smear
        // GCMS Low MW
        // GCMS High MW
        // FTIR
        var warning_data = [];
        var LPC = '';
        var SEM = '';
        var IC = '';
        var GCMS = '';
        var FTIR = '';
        var a = '';
        $.each(key_data, function (i, v) {
            $.each(v, function (index, fetch) {
                if (index == 'LPC') {
                    for( a of fetch){
                        if(a == 'false'){
                            LPC = 'false';
                        }
                    }
                } else if (index == 'SEM Class 1') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 2') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 3') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 3.1') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 3.2') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 3.3') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 4') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 5') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 5.1') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'SEM Class 6') {
                    for( a of fetch){
                        if(a == 'false'){
                            SEM = 'false';
                        }
                    }
                } else if (index == 'IC Anion') {
                    for( a of fetch){
                        if(a == 'false'){
                            IC = 'false';
                        }
                    }
                } else if (index == 'IC Sulphat') {
                    for( a of fetch){
                        if(a == 'false'){
                            IC = 'false';
                        }
                    }
                } else if (index == 'IC Cation') {
                    for( a of fetch){
                        if(a == 'false'){
                            IC = 'false';
                        }
                    }
                } else if (index == 'GCMS Outgassing') {
                    for( a of fetch){
                        if(a == 'false'){
                            GCMS = 'false';
                        }
                    }
                } else if (index == 'GCMS Smear') {
                    for( a of fetch){
                        if(a == 'false'){
                            GCMS = 'false';
                        }
                    }
                } else if (index == 'GCMS Low MW') {
                    for( a of fetch){
                        if(a == 'false'){
                            GCMS = 'false';
                        }
                    }
                } else if (index == 'GCMS High MW') {
                    for( a of fetch){
                        if(a == 'false'){
                            GCMS = 'false';
                        }
                    }
                } else if (index == 'FTIR') {
                    for( a of fetch){
                        if(a == 'false'){
                            FTIR = 'false';
                        }
                    }
                } else {

                }


            })
        })
        warning_data = {
            'LPC':LPC,
            'SEM':SEM,
            'IC':IC,
            'GCMS':GCMS,
            'FTIR':FTIR
        }
        
        // console.log("IC -> "+ IC);
        // console.log("SEM -> "+ SEM);
        // console.log("data.slopesem1 -> "+ data.slopesem1);
        // console.log("data.slopesem2 -> "+ data.slopesem2);
        // console.log("data.slopesem3 -> "+ data.slopesem3);
        // console.log("data.slopesem3_1 -> "+ data.slopesem3_1);
        // console.log("data.slopesem3_2 -> "+ data.slopesem3_2);
        // console.log("data.slopesem4 -> "+ data.slopesem4);
        // console.log("data.slopesem5 -> "+ data.slopesem5);
        // console.log("data.slopesem5_1 -> "+ data.slopesem5_1);
        // console.log("data.slopesem6 -> "+ data.slopesem6);
  
        return warning_data;
    }

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

                nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL / LCL</button></p>';
                nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                // nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>'; 
                nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Return (AML)</button></p>'; 
                nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>'; 
                nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>'; 

                nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';

                nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';

                nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                // nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>'; 
                nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Return (AML)</button></p>'; 
                nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';

                nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';

                nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';

                $('td:eq(2)', row).html(nodatalpc);
                $('td:eq(3)', row).html(nodatasem);
                $('td:eq(4)', row).html(nodataic);
                $('td:eq(5)', row).html(nodatagcms);
                $('td:eq(6)', row).html(nodataftir);
                $('td:eq(7)', row).html(nodatasemtest);

                var viewdata = '';
                var id_create_lab = data.id_createlabnew;
                
                $.ajax({
                    type: 'get',
                    // url: "{{ url('outputgraph') }}",
                    url: "{{ url('outputgraphtest7') }}",
                    data: {
                        id: data.id_model,
                        idcreatelab: data.id_createlabnew,
                        chkpdf:0,
                    },
                    success: function (data) {
                        var data_warning = checkDataWarning(data); 

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
                            nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL / LCL</button></p>';
                            nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            // nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>'; 
                            nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Return (AML)</button></p>'; 
                            nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            nodatalpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';

                            $('td:eq(2)', row).html(nodatalpc);
                        }
                        if(data.oqalot.st_sem == 1)
                        {
                            var nodatasem = '';
                            nodatasem += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            nodatasem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';
                            $('td:eq(3)', row).html(nodatasem);
                        }
                        if(data.oqalot.st_ic == 1)
                        {
                            var nodataic = '';
                            nodataic += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            nodataic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';
                            $('td:eq(4)', row).html(nodataic);
                        }
                        if(data.oqalot.st_gcms == 1)
                        {
                            var nodatagcms ='';
                            nodatagcms += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            // nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>'; 
                            nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Return (AML)</button></p>'; 
                            nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            nodatagcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';
                            $('td:eq(5)', row).html(nodatagcms);
                        }
                        if(data.oqalot.st_ftir == 1)
                        {
                            var nodataftir = '';
                            nodataftir += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            nodataftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';

                            $('td:eq(6)', row).html(nodataftir);
                        }
                        if(data.oqalot.st_semtest == 1)
                        {
                            var nodatasemtest = '';
                            nodatasemtest += '<p style="text-align:center;color:#FFD700;"<i class="fa fa-exclamation-triangle fa-2x" ></i> </p>';
                            nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>UCL1 / LCL</button></p>';
                            nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Internal</button></p>';
                            nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled>Edit</button></p>';
                            nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> CCP</button></p>';
                            nodatasemtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';

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

                        // if ((data.slopelpc >= 0.5 || data.slopelpc <= -0.5) || data.dfflpc >= 3.5) {
                        if ((data.slopediff_lpc_SLOPE >= 0.5 || data.slopediff_lpc_SLOPE <= -0.5) || data.slopediff_lpc_DIFF >= 3.5) {
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
                            // btdisable = 'onclick="ccpexcel('+data.datalab[itemdata].id_createlab+');"';
                            //Ma Edit
                            btdisable = 'onclick="ccpexcel('+id_create_lab+');"';
                            ccpallchk++;
                            if(data.datalabdone.lpcstatus_done == 0)
                            {
                                ccplpc = 1;
                                lpctxtshow = 'Ready CCP';
                                lpctxt = 'btn-primary';
                            }
                            else
                            {
                                lpctxtshow = 'Done CCP';
                                lpctxt = 'btn-success';
                            }
                        }
                         

                        // console.log('res',data)
                        // console.log('lpc',data.datalab[itemdata].id_createlab)
                        // lpc += '<p style="text-align:center"> <input type="hidden" id="chkalllpc'+data.datalab[itemdata].id_createlab+'" name="chkalllpc'+data.datalab[itemdata].id_createlab+'"  value="'+ccplpc+'"><i class="fa fa-circle fa-2x" style="color:'+(data_warning.LPC == 'false'?'red':'#00CC33')+'"></i></p>';
                        // lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpcucl + ' btn-mini" >UCL / LCL</button></p>';
                        // lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpcitn + ' btn-mini">Internal</button></p>';
                        // lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatalpc(' +data.datalab[itemdata].id_createlab + ',' + errorlpc_ucl + ');">Edit</button></p>';
                        // lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpctxt + ' btn-mini" '+btdisable+'>' + lpctxtshow + '</button></p>';
                   
                        //Ma Edit
                        lpc += '<p style="text-align:center"> <input type="hidden" id="chkalllpc'+id_create_lab+'" name="chkalllpc'+id_create_lab+'"  value="'+ccplpc+'"><i class="fa fa-circle fa-2x" style="color:'+(data_warning.LPC == 'false'?'red':'#00CC33')+'"></i></p>';
                        lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpcucl + ' btn-mini" >UCL / LCL</button></p>';
                        lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpcitn + ' btn-mini">Internal</button></p>';
                        // lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatalpc(' +id_create_lab + ',' + errorlpc_ucl + ');">Edit</button></p>';
                        if(data.amlfile){
                            if(data.amlfile.file_lpc){
                                lpc += '<p style="text-align:center"><button style="width:60px;height:100;border-radius: 30px;  background-color: #128f12;" class="btn btn-success btn-mini" onclick="editDatalpc(' +id_create_lab + ',' + errorlpc_ucl + ');">Return<br>(AML)</button></p>';
                            }else{
                                lpc += '<p style="text-align:center"><button style="width:60px;height:100;border-radius: 30px;" class="btn btn-warning btn-mini" onclick="editDatalpc(' +id_create_lab + ',' + errorlpc_ucl + ');">Return<br>(AML)</button></p>';
                            }
                        }else{
                            lpc += '<p style="text-align:center"><button style="width:60px;height:100;border-radius: 30px;" class="btn btn-warning btn-mini" onclick="editDatalpc(' +id_create_lab + ',' + errorlpc_ucl + ');">Return<br>(AML)</button></p>';
                        }

                        lpc += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + lpctxt + ' btn-mini" '+btdisable+'>' + lpctxtshow + '</button></p>';
                        if(data.amlfile){
                            if(data.amlfile.file_jnhk_lpc){
                                lpc += '<p style="text-align:center"><button style="width:60px;height:100; border-radius: 30px; background-color: #128f12;" class="btn btn-success btn-mini" onclick="confirmJNHKLPC(' +id_create_lab + ')"> Ready JNHK</button></p>';
                            }else{
                                lpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100; border-radius: 30px;" class="btn btn-warning btn-mini" disabled> Ready JNHK</button></p>';
                            }
                        }else{
                            lpc += '<p style="text-align:center"><button type="button" style="width:60px;height:100; border-radius: 30px;" class="btn btn-warning btn-mini" disabled> Ready JNHK</button></p>';
                        }

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
                        // if (data.slopesem3 >= 0.5 || data.slopesem3 <= -0.5 || data.dffsem3 >= 3.5) {
                        //     errorsem++;
                        // }
                        // **** Fixed an issue where notifications were incorrect. // 04-08-23
                        // console.log("slopediff_sem3_SLOPE => "+ data.slopediff_sem3_SLOPE);
                        // console.log("slopediff_sem3_DIFF => "+ data.slopediff_sem3_DIFF);
                        if (data.slopediff_sem3_SLOPE >= 0.5 || data.slopediff_sem3_SLOPE <= -0.5 || data.slopediff_sem3_DIFF >= 3.5) {
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
                        // if (data.slopesem3_1 >= 0.5 || data.slopesem3_1 <= -0.5 || data.dffsem3_1 >= 3.5) {
                        //     errorsem++;
                        // }
                        // **** Fixed an issue where notifications were incorrect. // 07-09-23
                        // console.log("slopediff_sem3_1_SLOPE => "+ data.slopediff_sem3_1_SLOPE);
                        // console.log("slopediff_sem3_1_DIFF => "+ data.slopediff_sem3_1_DIFF);

                        if (data.slopediff_sem3_1_SLOPE >= 0.5 || data.slopediff_sem3_1_SLOPE <= -0.5 || data.slopediff_sem3_1_DIFF >= 3.5) {
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
 
                        // -------------------------TEST5 -------------------------------------//
                        // if (data.slopesem3_3 >= 0.5 || data.slopesem3_3 <= -0.5 || data.dffsem3_3 >= 3.5) {
                        if (data.slopediff_sem3_3_SLOPE >= 0.5 || data.slopediff_sem3_3_SLOPE <= -0.5 || data.slopediff_sem3_3_DIFF >= 3.5) {
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
                        // if (data.slopesem4 >= 0.5 || data.slopesem4 <= -0.5 || data.dffsem4 >= 3.5) {
                        //     errorsem++;
                        // }
                        // **** Fixed an issue where notifications were incorrect. // 07-09-23
                        // console.log("slopediff_sem4_SLOPE => "+ data.slopediff_sem4_SLOPE);
                        // console.log("slopediff_sem4_DIFF => "+ data.slopediff_sem4_DIFF);

                        if (data.slopediff_sem4_SLOPE >= 0.5 || data.slopediff_sem4_SLOPE <= -0.5 || data.slopediff_sem4_DIFF >= 3.5) {
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
                        //   if (data.slopesem5 >= 0.5 || data.slopesem5 <= -0.5 || data.dffsem5 >= 3.5) {
                        if (data.slopediff_sem5_SLOPE >= 0.5 || data.slopediff_sem5_SLOPE <= -0.5 || data.slopediff_sem5_DIFF >= 3.5) {
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
                        if(data.semdatalab[itemdatasem].last_sem_class6 !== null){
                            if (data.semdatalab[itemdatasem].last_sem_class6	 < data.lcl.class6) {
                                errorsem_ucl++;
                                errorsem++;

                            } else if (data.semdatalab[itemdatasem].last_sem_class6	 > data.ucl.class6) {
                                errorsem_ucl++;
                                errorsem++;

                            } else {}
                        }else{
                            if (data.semdatalab[itemdatasem].sem_class6	 < data.lcl.class6) {
                                errorsem_ucl++;
                                errorsem++;

                            } else if (data.semdatalab[itemdatasem].sem_class6	 > data.ucl.class6) {
                                errorsem_ucl++;
                                errorsem++;

                            } else {}
                        }    
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if(data.semdatalab[itemdatasem].last_sem_class6 !== null){    
                            if (data.semdatalab[itemdatasem].last_sem_class6 > data.is.class6) {
                                errorsem_itn++;
                                errorsem++;
                            } else {}
                            if (data.sem6higher >= data.alarmtest2) {
                                errorsem++;
                            } else if (data.sem6below >= data.alarmtest2) {
                                errorsem++;
                            } else {}
                        }else{
                            if (data.semdatalab[itemdatasem].sem_class6 > data.is.class6) {
                                errorsem_itn++;
                                errorsem++;
                            } else {}
                            if (data.sem6higher >= data.alarmtest2) {
                                errorsem++;
                            } else if (data.sem6below >= data.alarmtest2) {
                                errorsem++;
                            } else {}
                        }
                        // ------------------------- END TEST2 -------------------------------------//
                        // ------------------------- TEST3 -------------------------------------//
                        if (data.sem6highervs3 >= data.alarmtest3) {
                            errorsem++;
                        } else if (data.sem6belowvs3 >= data.alarmtest3) {
                            errorsem++;
                        } else {}
                        // -------------------------END  TEST3 -------------------------------------//
                        // ------------------------- TEST1 -------------------------------------//
                        if(data.semdatalab[itemdatasem].last_sem_class6 !== null){    
                            if (data.semdatalab[itemdatasem].last_sem_class6	 < data.lcl.class6) {
                                errorsem_ucl++;
                                errorsem++;

                            } else if (data.semdatalab[itemdatasem].last_sem_class6	 > data.ucl.class6) {
                                errorsem_ucl++;
                                errorsem++;

                            } else {}
                        }else{
                            if (data.semdatalab[itemdatasem].sem_class6	 < data.lcl.class6) {
                                errorsem_ucl++;
                                errorsem++;

                            } else if (data.semdatalab[itemdatasem].sem_class6	 > data.ucl.class6) {
                                errorsem_ucl++;
                                errorsem++;

                            } else {}
                        }
                        // ------------------------- END TEST1 -------------------------------------//
                        // -------------------------TEST4 -------------------------------------//
                        if(data.semdatalab[itemdatasem].last_sem_class6 !== null){  
                            if (data.semdatalab[itemdatasem].last_sem_class6 > data.is.class6) {
                                errorsem_itn++;
                                errorsem++;
                            } else {}
                        }else{
                            if (data.semdatalab[itemdatasem].sem_class6 > data.is.class6) {
                                errorsem_itn++;
                                errorsem++;
                            } else {}
                        }
                        // -------------------------end TEST4 -------------------------------------//
                        // -------------------------TEST5 -------------------------------------//
                        // if (data.slopesem6 >= 0.5 || data.slopesem6 <= -0.5 || data.dffsem6 >= 3.5) {
                        //     errorsem++;
                        // } 
                        // **** Fixed an issue where notifications were incorrect. // 04-08-23
                        // console.log("slopediff_sem6_SLOPE => "+ data.slopediff_sem6_SLOPE);
                        // console.log("slopediff_sem6_DIFF => "+ data.slopediff_sem6_DIFF);

                        if (data.slopediff_sem6_SLOPE >= 0.5 || data.slopediff_sem6_SLOPE <= -0.5 || data.slopediff_sem6_DIFF >= 3.5) {
                            errorsem++;
                        }
                        
                        // -------------------------- end TEST5 -------------------------------------//
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
                            btdisable = 'disabled';
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
                        // **** coding is  still have problem 
                        console.log("data_warning.SEM ->"+ data_warning.SEM);
                        sem += '<p style="text-align:center"><input type="hidden" id="chkallsem'+data.semdatalab[itemdatasem].id_createlab+'" name="chkallsem'+data.semdatalab[itemdatasem].id_createlab+'"  value="'+ccpsem+'"><i class="fa fa-circle fa-2x" style="color:'+(data_warning.SEM == 'false'?'red':'#00CC33')+'"></i></p>';
                        sem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + semucl + ' btn-mini" >UCL / LCL</button></p>';
                        sem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + semitn + ' btn-mini">Internal</button></p>';
                        sem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatasem(' + data.semdatalab[itemdatasem].id_createlab + ',' + errorsem + ');">Edit</button></p>';
                        sem += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + semtxt + ' btn-mini" '+btdisable+'">' + semtxtshow + '</button></p>';
                        sem += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';
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
                        // console.log("data_warning.IC ->"+ data_warning.IC);
                        ic += '<p style="text-align:center"><input type="hidden"  id="chkallic'+data.icdatalab[icitemdata].id_createlab+'" name="chkallic'+data.icdatalab[icitemdata].id_createlab+'"  value="'+ccpic+'"><i class="fa fa-circle fa-2x" style="color:'+(data_warning.IC == 'false'?'red':'#00CC33')+'"></i></p>';
                        ic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + icucl + ' btn-mini" >UCL / LCL</button></p>';
                        ic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + icitn + ' btn-mini">Internal</button></p>';
                        ic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDataic(' + data.icdatalab[icitemdata].id_createlab + ',' + erroric + ');">Edit</button></p>';
                        ic += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + ictxt + ' btn-mini" '+btdisable+')">' + ictxtshow + '</button></p>';
                        ic += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';
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
                        // var datalcloutgassing =  data.newgcmsdatalab[itemdata1][2].outgassing;
                        // var dataucloutgassing =  data.newgcmsdatalab[itemdata1][1].outgassing;
                        if(data.newgcmsdatalab[itemdata1]){
                            var datalcloutgassing =  data.newgcmsdatalab[itemdata1][2].outgassing;
                            var dataucloutgassing =  data.newgcmsdatalab[itemdata1][1].outgassing;
                        }else{
                            var datalcloutgassing = null;
                            var dataucloutgassing = null;
                        }
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
                        if(datalcloutgassing != null && dataucloutgassing != null){
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
                        // if (data.slopegcms_out >= 0.5 || data.slopegcms_out <= -0.5 ||  data.dffgcms_out >= 3.5) {
                        //     errorgcms++;
                        //     console.log(" slopegcms_out =>"+ errorgcms);
                        //     console.log(" data.slopegcms_out =>"+ data.slopegcms_out);

                        // }


                        if (data.slopediff_Outgass_SLOPE >= 0.5 || data.slopediff_Outgass_SLOPE <= -0.5 ||  data.slopediff_Outgass_DIFF >= 3.5) {
                            errorgcms++;
                            // console.log(" slopegcms_out =>"+ errorgcms);
                            // console.log(" data.slopegcms_out =>"+ data.slopediff_Outgass_SLOPE);
                        }


                        var lowhigh = parseFloat(data.gcmsdatalab[gcmsitemdata].gcms_low_mw) + parseFloat(data.gcmsdatalab[gcmsitemdata].gcms_high_mw);
                        var outgassingtest6 = parseFloat(data.gcmsdatalab[gcmsitemdata].gcms_outgassing) - parseFloat(lowhigh);
                        // alert(outgassingtest6);
                            if (outgassingtest6 > 20 || outgassingtest6 < -20) {
                                //sumalertall++:
                                errorgcms++;
                                // console.log(" outgassingtest6 =>"+ errorgcms);
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
                        // var datalclalkylacrylate =  data.newgcmsdatalab[itemdata1][2].alkylacrylate;
                        // var datauclalkylacrylate =  data.newgcmsdatalab[itemdata1][1].alkylacrylate;
                        if(data.newgcmsdatalab[itemdata1]){
                            var datalclalkylacrylate =  data.newgcmsdatalab[itemdata1][2].alkylacrylate;
                            var datauclalkylacrylate =  data.newgcmsdatalab[itemdata1][1].alkylacrylate;
                        }else{
                            var datalclalkylacrylate = null;
                            var datauclalkylacrylate = null;
                        }
                        if(datalclalkylacrylate != null && datauclalkylacrylate != null){
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
                            // console.log(" slopegcms_smear =>"+ errorgcms);
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
                        
                        // var datalcllow_MW =  data.newgcmsdatalab[itemdata1][2].low_MW;
                        // var dataucllow_MW =  data.newgcmsdatalab[itemdata1][1].low_MW;
                        if(data.newgcmsdatalab[itemdata1]){
                            var datalcllow_MW =  data.newgcmsdatalab[itemdata1][2].low_MW;
                            var dataucllow_MW =  data.newgcmsdatalab[itemdata1][1].low_MW;
                        }else{
                            var datalcllow_MW = null;
                            var dataucllow_MW = null;
                        }

                        if(datalcllow_MW != null && datalcllow_MW != null){
                            if (data.gcmsdatalab[gcmsitemdata].gcms_low_mw < datalcllow_MW) {
                                gcmsucl = 'btn-danger';
                                errorgcms++;

                            } else if (data.gcmsdatalab[gcmsitemdata].gcms_low_mw > dataucllow_MW) {

                                gcmsucl = 'btn-danger';
                                errorgcms++;

                            } else {
                                gcmsucl = 'btn-secondary';
                            }
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
                            // console.log(" slopegcms_low_mw =>"+ errorgcms);
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
                        // var datalclhigh_MW =  data.newgcmsdatalab[itemdata1][2].high_MW;
                        // var datauclhigh_MW =  data.newgcmsdatalab[itemdata1][1].high_MW;
                        if(data.newgcmsdatalab[itemdata1]){
                            var datalclhigh_MW =  data.newgcmsdatalab[itemdata1][2].high_MW;
                            var datauclhigh_MW =  data.newgcmsdatalab[itemdata1][1].high_MW;
                        }else{
                            var datalclhigh_MW = null;
                            var datauclhigh_MW = null;
                        }

                        if(datalclhigh_MW != null && datauclhigh_MW != null){
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
                            // console.log(" slopegcms_high_mw =>"+ errorgcms);
                        }
                        // -------------------------end gcms4 -------------------------------------//

                        // console.log(" errorgcms =>"+ errorgcms);

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

                        //console.log("errorgcms 17 -> " + errorgcms);
                        //console.log("alarmtest7 -> " +  data.alarmtest7);

                        var btdisable = '';
                        if (errorgcms > 0) {
                            gcmstxtallshow = 'style="color:#E71D00;"';
                            gcmstxt = 'btn-secondary';
                            gcmstxtshow = 'CCP';
                            btdisable = '';
                        } else {

                            var checkreviseTEST7 = 0;
                            if(data.reviseTEST7.sumalert_test7_acid == 3){ checkreviseTEST7++; }
                            if(data.reviseTEST7.sumalert_test7_aromatic == 3){ checkreviseTEST7++; }
                            if(data.reviseTEST7.sumalert_test7_disk == 3){ checkreviseTEST7++; }
                            if(data.reviseTEST7.sumalert_test7_elmental == 3){ checkreviseTEST7++; }
                            if(data.reviseTEST7.sumalert_test7_high == 3){ checkreviseTEST7++; }
                            if(data.reviseTEST7.sumalert_test7_low == 3){ checkreviseTEST7++; }
                            if(data.reviseTEST7.sumalert_test7_oxygen == 3){ checkreviseTEST7++; }
                            if(data.reviseTEST7.sumalert_test7_smear == 3){ checkreviseTEST7++; }

                            if (checkreviseTEST7 > 0) {
                                gcmstxtallshow = 'style="color:#E71D00;"';
                                gcmstxt = 'btn-secondary';
                                gcmstxtshow = 'CCP';
                                btdisable = '';
                            }else{
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

                        } 

                        if (checkreviseTEST7 > 0) {
                            gcms += '<p style="text-align:center"><input type="hidden"  id="chkallgcms'+data.gcmsdatalab[gcmsitemdata].id_createlab+'" name="chkallgcms'+data.gcmsdatalab[gcmsitemdata].id_createlab+'"  value="'+ccpgcms+'"><i class="fa fa-circle fa-2x" style="color:red"></i></p>';
                        }else{
                            gcms += '<p style="text-align:center"><input type="hidden"  id="chkallgcms'+data.gcmsdatalab[gcmsitemdata].id_createlab+'" name="chkallgcms'+data.gcmsdatalab[gcmsitemdata].id_createlab+'"  value="'+ccpgcms+'"><i class="fa fa-circle fa-2x" style="color:'+(data_warning.GCMS == 'false'?'red':'#00CC33')+'"></i></p>';
                        }

                        gcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + gcmsucl + ' btn-mini" >UCL / LCL</button></p>';
                        gcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + gcmsitn + ' btn-mini">Internal</button></p>';
                        // gcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatagcms(' + data.gcmsdatalab[gcmsitemdata].id_createlab + ',' + errorgcms + ');">Edit</button></p>'; 
                        if(data.amlfile){
                            if(data.amlfile.file_gcms){
                                gcms += '<p style="text-align:center"><button style="width:60px;height:100;border-radius: 30px;  background-color: #128f12;" class="btn btn-success btn-mini" onclick="editDatagcms(' + data.gcmsdatalab[gcmsitemdata].id_createlab + ',' + errorgcms + ');">Return<br>(AML)</button></p>'; 
                            }else{
                                gcms += '<p style="text-align:center"><button style="width:60px;height:100;border-radius: 30px;" class="btn btn-warning btn-mini" onclick="editDatagcms(' + data.gcmsdatalab[gcmsitemdata].id_createlab + ',' + errorgcms + ');">Return<br>(AML)</button></p>'; 
                            }
                        }else{
                            gcms += '<p style="text-align:center"><button style="width:60px;height:100;border-radius: 30px;" class="btn btn-warning btn-mini" onclick="editDatagcms(' + data.gcmsdatalab[gcmsitemdata].id_createlab + ',' + errorgcms + ');">Return<br>(AML)</button></p>'; 
                        }

                        gcms += '<p style="text-align:center"><button style="width:60px;height:100" class="btn ' + gcmstxt + ' btn-mini"  '+btdisable+'>' + gcmstxtshow + '</button></p>';
                        if(data.amlfile){
                            if(data.amlfile.file_jnhk_gcms){
                                gcms += '<p style="text-align:center"><button style="width:60px;height:100; border-radius: 30px; background-color: #128f12;" class="btn btn-success btn-mini" onclick="confirmJNHKGCMS(' + data.gcmsdatalab[gcmsitemdata].id_createlab + ')"> Ready JNHK</button></p>';
                            }else{
                                gcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100; border-radius: 30px;" class="btn btn-warning btn-mini" disabled> Ready JNHK</button></p>';
                            }
                        }else{
                            gcms += '<p style="text-align:center"><button type="button" style="width:60px;height:100; border-radius: 30px;" class="btn btn-warning btn-mini" disabled> Ready JNHK</button></p>';
                        }
                        $('td:eq(5)', row).html(gcms);
                        //-------------------------------------end GCMS ------------------------------//
                        }
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
                            btdisable ='disabled';
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
                        ftir += '<p style="text-align:center"><input type="hidden"  id="chkallftir'+data.ftirdatalab[ftiritemdata].id_createlab+'" name="chkallftir'+data.ftirdatalab[ftiritemdata].id_createlab+'"  value="'+ccpftir+'"><i class="fa fa-circle fa-2x" style="color:'+(data_warning.FTIR == 'false'?'red':'#00CC33')+'"></i></p>';
                        ftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn ' + ftirucl + ' btn-mini" >UCL / LCL</button></p>';
                        ftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn ' + ftiritn + ' btn-mini">Internal</button></p>';
                        ftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDataftir(' + data.ftirdatalab[ftiritemdata].id_createlab + ',' + errorftir + ');">Edit</button></p>';
                        ftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn ' + ftirtxt + ' btn-mini"  '+btdisable+'">' + ftirtxtshow + '</button></p>';
                        ftir += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';
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
                            semtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" >UCL / LCL</button></p>';
                            semtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini">Internal</button></p>';
                            semtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-primary btn-mini" onclick="editDatasemtest(' + data.semtestdatalab.id_createlab + ',' + errorsemtest + ');">Edit</button></p>';
                            semtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn ' + semtesttxt + ' btn-mini"  '+btdisable+'">' + semtesttxtshow + '</button></p>';
                            semtest += '<p style="text-align:center"><button type="button" style="width:60px;height:100" class="btn btn-secondary btn-mini" disabled> Ready JNHK</button></p>';
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
        // window.location.href = "../output/editdatalpc/" + id+'/1/'+showeditalam;
        window.location.href = "../output/editdataamllpc/" + id;
    }
    function editDatasem(id,showeditalam) {
        window.location.href = "../output/editdatasem/" + id+'/1/'+showeditalam;
    }
    function editDataic(id) {
        window.location.href = "../output/editdataic/"+ id+'/1/0';
    }
    function editDatagcms(id) {
        // window.location.href = "../output/editdatagcms/"+ id+'/1/0';
        window.location.href = "../output/editdataamlgcms/" + id;
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
                    // url: "{{ url('ccpfileexcelall')}}",
                    url: "{{ url('newccpfileexcelall')}}",
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
                            swal("warning", "The CCP report cannot be generated because the data status is incorrect", "warning");
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
                    // url: "{{ url('ccpfileexcel')}}",
                    url: "{{ url('newccpfileexcel')}}", // update 5/4/23
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

                        } else if (data.success == 2) {

                            swal("warning", "please check data again", "warning");

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
                    // url: "{{ url('ccpexcelftir')}}",
                    url: "{{ url('newccpexcelftir')}}",
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
                    // url: "{{ url('ccpexcelgcms')}}",
                    url: "{{ url('newccpexcelgcms')}}",
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
                    // url: "{{ url('ccpexcelsem')}}",
                    url: "{{ url('newccpexcelsem')}}",
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
                    // url: "{{ url('ccpexcelic')}}",
                    url: "{{ url('newccpexcelic')}}",
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
                    // url: "{{ url('ccpfileexcelghost')}}",
                    url: "{{ url('newccpfileexcelghost')}}",
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

    // 9.Submit AML to JNHK_r01
    function confirmJNHKLPC(id)
    {
        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });
        $.ajax({
            type: 'get',
            url: "{{ url('confirmjnhklpc')}}",
            data: {
                'id' : id
            },
            success: function (data) {
                console.log(data);
            }
        });
    }

    function confirmJNHKGCMS(id)
    {
        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });
        $.ajax({
            type: 'get',
            url: "{{ url('confirmjnhkgcms')}}",
            data: {
                'id' : id
            },
            success: function (data) {
                console.log(data);
            }
        });
    }
    // 9.Submit AML to JNHK_r01


</script>
@endsection
