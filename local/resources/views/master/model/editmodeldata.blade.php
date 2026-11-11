@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Edit Cut off Control limit')
@section('breadcrumb-title', 'Edit Cut off Control limit')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">
                <form method="post" id="fm-addmodel">             
                    {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-2">
                    </div>
                    <div style="text-align: center" class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-5">
                                <label> Start Date</label>
                                <input type="date" class="form-control" name="date_start" id="date_start" value="{{$model_spec[0]->date_start}}" onchange="ckdate1()">
                            </div>
                            @php
                            if ($model_spec[0]->chkstatus == 1)
                            {
                                $datechecked = 'checked';
                                $date_end ='';
                                $datedisabled = 'disabled';

                                $chkck = 1;

                            }
                            else
                            {
                                $datechecked = '';
                                 $date_end = $model_spec[0]->date_end;
                                 $datedisabled = '';
                                 $chkck = 0;
                            }
                            @endphp
                            <div class="col-sm-5">
                                <label> End Date</label>
                                <input type="date" class="form-control" name="date_end" id="date_end" value="{{$date_end}}" onchange="ckdate2()" {{$datedisabled}}>
                            </div>
                            <div class="col-sm-2" style="text-align: left">
                                <label>&nbsp;</label>
                                <div style="font-size: 18px" class="form-check">
                                    <input style="width: 18px; height: 18px;" class="form-check-input"  type="checkbox" value="0" id="checkchkdate" onchange="checkdate()" {{$datechecked}}>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        ปัจจุบัน
                                    </label>
                                    <input type="hidden" id="chkbox1" name="chkbox1" value="{{$chkck}}">
                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">

                    </div>
                    
                </div>
                <br>
                <br>
                <div class="dt-responsive table-responsive">
                        
                <input type="hidden" name="subnumber" value="{{$subnumber}}" />
                <input type="hidden" name="modelid" value="{{$model_spec[0]->id_model}}" />
                        <div class="dt-responsive table-responsive">
                            <table id="form-input-table"
                                   class="table table-striped table-bordered nowrap ">

                                <thead>
                                <tr>
                                    <th  class="text-center" rowspan="2">Name</th>
                                    <th bgcolor="#FFFF99" colspan="1" class="text-center">LPC</th>
                                    <th  bgcolor="#00FFFF" colspan="10" class="text-center">SEM</th>
                                    <th bgcolor="#00FF7F" colspan="3" class="text-center">IC</th>
                                    <th bgcolor="#FFB6C1"  colspan="4" class="text-center" >GCMS</th>
                                    <th bgcolor="#EE82EE" colspan="1" class="text-center">FTIR</th>
                                    <th  class="text-center" rowspan="2">Name</th>
                                
                                </tr>

                                <tr>

                                    <th bgcolor="#FFFF99">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class1&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class2&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class3&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&ensp;Class3.1&emsp;&ensp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&ensp;Class3.2&emsp;&ensp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&ensp;Class3.3&emsp;&ensp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class4&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class5&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&ensp;Class5.1&emsp;&ensp;&nbsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class6&emsp;&emsp;</th>
                                    <th bgcolor="#00FF7F">&emsp;&emsp;Anion&emsp;&emsp;</th>
                                    <th bgcolor="#00FF7F">&emsp;&ensp;Sulphate&emsp;&nbsp;</th>
                                    <th bgcolor="#00FF7F">&emsp;&emsp;Cation&emsp;&emsp;</th>
                                    <th bgcolor="#FFB6C1">&emsp;&nbsp;Outgassing&emsp;&nbsp;</th>
                                    <th bgcolor="#FFB6C1">&emsp;Smear&emsp;&nbsp;</th>
                                    <th bgcolor="#FFB6C1">&emsp;&nbsp;Low MW&emsp;&nbsp;</th>
                                    <th bgcolor="#FFB6C1">&emsp;&nbsp;High MW&emsp;&nbsp;</th>
                                    <th bgcolor="#EE82EE">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                </tr>

                                </thead>

                                <tbody>

                                @foreach($model_spec as $item )
                                @if($item->id_model_type == 2 || $item->id_model_type == 3 ||  $item->id_model_type == 4 ||  $item->id_model_type == 5) 
                                    <tr>
                                        <td>{{$item->name}} :</td>
                                        <input type="hidden" class="form-control" name="chkidspec{{$item->text}}"
                                               id="chkidspec{{$item->text}}" value="{{$item->id_model_history}}">
                                               <input type="hidden" class="form-control"
                                               name="typeID{{$item->text}}" id="typeID{{$item->text}}"
                                               value="{{$item->id_model_type}}">
                                        <td bgcolor="#FFFF99">
                                            <input type="text" class="form-control" name="alpc{{$item->text}}"  
                                                   id="alpc{{$item->text}}" value="{{$item->alpc}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class1{{$item->text}}" 
                                                   id="class1{{$item->text}}" value="{{$item->class1}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class2{{$item->text}}" 
                                                   id="class2{{$item->text}}" value="{{$item->class2}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class3{{$item->text}}" 
                                                   id="class3{{$item->text}}" value="{{$item->class3}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class3_1{{$item->text}}" 
                                                   id="class3_1{{$item->text}}" value="{{$item->class3_1}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class3_2{{$item->text}}" 
                                                   id="class3_2{{$item->text}}"  value="{{$item->class3_2}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class3_3{{$item->text}}" 
                                                   id="class3_3{{$item->text}}"  value="{{$item->class3_3}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class4{{$item->text}}" 
                                                   id="class4{{$item->text}}"  value="{{$item->class4}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class5{{$item->text}}" 
                                                   id="class5{{$item->text}}"  value="{{$item->class5}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class5_1{{$item->text}}" 
                                                   id="class5_1{{$item->text}}" value="{{$item->class5_1}}">
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            <input type="text" class="form-control" name="class6{{$item->text}}" 
                                                   id="class6{{$item->text}}" value="{{$item->class6}}">
                                        </td>
                                        <td bgcolor="#00FF7F">
                                            <input type="text" class="form-control" name="anion{{$item->text}}" 
                                                   id="anion{{$item->text}}" value="{{$item->anion}}">
                                        </td>
                                        <td bgcolor="#00FF7F">
                                            <input type="text" class="form-control" name="sulphate{{$item->text}}" 
                                                   id="sulphate{{$item->text}}" value="{{$item->sulphate}}">
                                        </td>
                                        <td bgcolor="#00FF7F">
                                            <input type="text" class="form-control" name="cation{{$item->text}}" 
                                                   id="cation{{$item->text}}" value="{{$item->cation}}">
                                        </td>
                                        <td bgcolor="#FFB6C1">
                                            <input type="text" class="form-control" name="outgassing{{$item->text}}" 
                                                   id="outgassing{{$item->text}}" value="{{$item->outgassing}}">
                                        </td>
                                        <td bgcolor="#FFB6C1">
                                            <input type="text" class="form-control" name="alkylacrylate{{$item->text}}" 
                                                   id="alkylacrylate{{$item->text}}" value="{{$item->alkylacrylate}}">
                                        </td>
                                        <td bgcolor="#FFB6C1">
                                            <input type="text" class="form-control" name="low_MW{{$item->text}}" 
                                                   id="low_MW{{$item->text}}" value="{{$item->low_MW}}">
                                        </td>
                                        <td bgcolor="#FFB6C1">
                                            <input type="text" class="form-control" name="high_MW{{$item->text}}" 
                                                   id="high_MW{{$item->text}}" value="{{$item->high_MW}}">
                                        </td>
                                        <td bgcolor="#EE82EE">
                                            <input type="text" class="form-control" name="ftir{{$item->text}}" 
                                                   id="ftir{{$item->text}}" value="{{$item->ftir}}">
                                        </td>
                                        <td>: {{$item->name}}</td>
                                    </tr>
                                    @endif 
                                @endforeach

                 
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <br>
                        <div>
                            <div style="text-align: center" class="col-sm-12">
                                <label for="w3review">Details</label>
                            <textarea class="form-control" id="textnote" name="textnote" rows="4" cols="50"> {{$model_spec[0]->note}}</textarea>
                            </div>
            
                        </div>
                        <br>
                        <br>
                        <div style="text-align: center">
                        <button type="button" onclick="buttonedit()" id="buttoncreate"
                        class="btn btn-success">SAVE</button>
                    <button type="reset" onclick="buttonreset()"
                        class="btn btn-secondary">RESET</button>
                    </div>
                        <br>
                        <br>
                        <br>

                   
                </div>
            </form>
            </div>
            
        </div>
        {{-- <a href="{{ url('output/results') }}" class="btn btn-warning pull-right">Back</a> --}}
    </div>
</div>
{{-- <form action="" id="fm-editlpc" >
    <button type="button" onclick="xx()" class="btn btn-primary" ><i class="fa fa-save"></i></button>
</form> --}}
@endsection

@section('scripts')
<script>
        function ckdate2()
    {
        
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_start != "")
        {
        if(date_end <= date_start)
        {
            swal("warning", "Warning date_start > date_end !", "warning");
            $('#date_end').val('');
            return false;
        }
        }
    }
    
    function ckdate1()
    {
        var date_start = $('#date_start').val();
        var date_end = $('#date_end').val();
        if(date_end != "")
        {
        if(date_start  >= date_end)
        {
            swal("warning", "Warning date_start > date_end !", "warning");
            $('#date_start').val('');
            return false;
        }
        }
    }
    function checkdate()
    {
    chk = $('#chkbox1').val();
       if(chk == 0)
       {
         $('#chkbox1').val(1);
         $("#date_end").val('');
         $("#date_end").prop('disabled', true);
       }
       else
       {
        $('#chkbox1').val(0);
        $("#date_end").prop('disabled', false);
       }
        
    }

    function buttonedit()
    {
        var date_start = $('#date_start').val();
        var chkbox1 = $('#chkbox1').val();
        if(chkbox1 == 1)
        {
            var date_end = "31-12-2060";
        }
        else
        {
            var date_end = $('#date_end').val();
        }
        const text = ["T", "U", "L","C"];
        var text1 = '';
        var text2 = '';
        var text3 = '';
        var text4 = '';
        var ck = 0;
      
        text.forEach(async function(text) {
            var ckr = 0;
            if ($('#alpc' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class1' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class2' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class3' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class3_1' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class3_2' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class3_3' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class4' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class5' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class5_1' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#class6' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#anion' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#sulphate' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#cation' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#outgassing' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#alkylacrylate' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#low_MW' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#high_MW' + text).val() == "") {
                ck++;
                ckr++;
            }
            if ($('#ftir' + text).val() == "") {
                ck++;
                ckr++;
            }
            if(ckr > 0)
            {
                if(text == "T")
                {
                     text1 = "Trigger limit : " + ckr + "<br>";
                    
                }
                else if(text == "U")
                {
                    text2 = "Upper control limit : " + ckr + "<br>";
                   
                }
                else if(text == "L")
                {
                    text3 = "Lower control limit : "  + ckr + "<br>";
                   
                }
                else
                {
                    text4 = "Center line : "  + ckr + "<br>";
                   
                }
            }

        });

        if(ck > 0 )
        {
            //swal("warning", "Please =  !!<br>"+text1+text2+text3+text4, "warning");
            swal({
                html: true,
                title: "warning",
                text: "This field is required. !!<br><br><spen>"+text1+text2+text3+text4+"</spen>",
                type: "warning",

            });
            return false;
        }
 
        if(date_start != "" && date_end != "")
        {
        swal({
                html: true,
                title: "Are you sure?",
                text: "Submit Edit Cut off Control limit! ",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                var forms = $('#fm-addmodel')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('editmodeldatasave') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                            swal("Success", "Submit Edit Cut off Control limit success", "success");
                                setTimeout(function () {
                                    window.location.href =
                                        "{{ url('master/indexhistory') }}/"+data.id;
                                }, 1000);
                        }
                   
                    }
                });
            });
        }
        else
        {
            
            swal("warning", "Please select startdate and enddate  !!", "warning");
            return false;
        }
    }


</script>
@endsection