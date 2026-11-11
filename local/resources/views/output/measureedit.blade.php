
@extends('layouts.main')
@section('title', 'Holiday for subtract the Measurement L/T')
@section('breadcrumb-title', 'Measurement L/T setting')
@section('breadcrumb-title', 'Holiday for subtract the Measurement L/T')


@section('content')
 
<link rel="stylesheet" type="text/css" href="{{ asset('template/assets/css/jquery-ui.multidatespicker.css')}}">

<style>

    .ui-datepicker-next,.ui-datepicker-prev{display:none;}

</style>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">

            </div>

            <form class="wizard-form" method="post" id="fm_edit">
            {{ csrf_field() }}
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div class="card-block">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="wizarda">

                                        <input type="hidden" id="id_tsetting" name="id_tsetting" value="{{$id_tsetting}}">
                                        <input type="hidden" id="post_year" name="post_year" value="{{$year}}">

                                        <table class="table">
                                            <tr>
                                                <td>
                                                    <div id="mdp-democalendar01"></div>
                                                    <textarea id="calendar01" name="calendar01"></textarea>
                                                </td>
                                                <td>
                                                    <div id="mdp-democalendar02"></div>
                                                    <textarea id="calendar02" name="calendar02"></textarea>
                                                </td>
                                                <td>
                                                    <div id="mdp-democalendar03"></div>
                                                    <textarea id="calendar03" name="calendar03"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div id="mdp-democalendar04"></div>
                                                    <textarea id="calendar04" name="calendar04"></textarea>
                                                </td>
                                                <td>
                                                    <div id="mdp-democalendar05"></div>
                                                    <textarea id="calendar05" name="calendar05"></textarea>
                                                </td>
                                                <td>
                                                    <div id="mdp-democalendar06"></div>
                                                    <textarea id="calendar06" name="calendar06"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div id="mdp-democalendar07"></div>
                                                    <textarea id="calendar07" name="calendar07"></textarea>
                                                </td>
                                                <td>
                                                    <div id="mdp-democalendar08"></div>
                                                    <textarea id="calendar08" name="calendar08"></textarea>
                                                </td>
                                                <td>
                                                    <div id="mdp-democalendar09"></div>
                                                    <textarea id="calendar09" name="calendar09"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div id="mdp-democalendar10"></div>
                                                    <textarea id="calendar10" name="calendar10"></textarea>
                                                </td>
                                                <td>
                                                    <div id="mdp-democalendar11"></div>
                                                    <textarea id="calendar11" name="calendar11"></textarea>
                                                </td>
                                                <td>
                                                    <div id="mdp-democalendar12"></div>
                                                    <textarea id="calendar12" name="calendar12"></textarea>
                                                </td>
                                            </tr>

                                        </table>

                                        <div class="form-group row">
                                            <div class="col-12 text-right">
                                                <a href="{{ url('output/measure') }}"><button type="button" class="btn btn-dark waves-effect">BACK</button></a>
                                                @if($status == 1)
                                                <button type="button" id="submitLT" class="btn btn-success waves-effect">SAVE</button>
                                                @endif
                                            </div>
                                        </div>

                                </div>  
                            </div>  
                        </div>  
                    </div>  
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

 

<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">
@endsection

@section('scripts')
 

<script src="{{ asset('template/assets/js/moment.min.js') }}"></script> 
<script src="{{ asset('template/assets/js/jquery-ui.multidatespicker.js') }}"></script> 


<script>

    var fix_year = $('#post_year').val();
    var arDate=[];
 
    $('#calendar01').hide();
    $('#calendar02').hide();
    $('#calendar03').hide();
    $('#calendar04').hide();
    $('#calendar05').hide();
    $('#calendar06').hide();
    $('#calendar07').hide();
    $('#calendar08').hide();
    $('#calendar09').hide();
    $('#calendar10').hide();
    $('#calendar11').hide();
    $('#calendar12').hide();
    

    var arDatewe1 = [];
    var arDatewe2 = [];
    var arDatewe3 = [];
    var arDatewe4 = [];
    var arDatewe5 = [];
    var arDatewe6 = [];
    var arDatewe7 = [];
    var arDatewe8 = [];
    var arDatewe9 = [];
    var arDatewe10 = [];
    var arDatewe11 = [];
    var arDatewe12 = [];

    var FDatewe1 = [];
    var FDatewe2 = [];
    var FDatewe3 = [];
    var FDatewe4 = [];
    var FDatewe5 = [];
    var FDatewe6 = [];
    var FDatewe7 = [];
    var FDatewe8 = [];
    var FDatewe9 = [];
    var FDatewe10 = [];
    var FDatewe11 = [];
    var FDatewe12 = [];

    @foreach($arrayweekend[1] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe1.push(mydate);  
        FDatewe1.push(JSON.stringify("{{ $weekend }}")); 
    @endforeach
    @foreach($arrayweekend[2] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe2.push(mydate);  
        FDatewe2.push(JSON.stringify("{{ $weekend }}")); 
    @endforeach
    @foreach($arrayweekend[3] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe3.push(mydate);  
        FDatewe3.push(JSON.stringify("{{ $weekend }}")); 
    @endforeach
    @foreach($arrayweekend[4] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe4.push(mydate);  
        FDatewe4.push(JSON.stringify("{{ $weekend }}")); 
    @endforeach
    @foreach($arrayweekend[5] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe5.push(mydate);  
        FDatewe5.push(JSON.stringify("{{ $weekend }}")); 
    @endforeach
    @foreach($arrayweekend[6] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe6.push(mydate);  
        FDatewe6.push(JSON.stringify("{{ $weekend }}")); 
    @endforeach
    @foreach($arrayweekend[7] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe7.push(mydate);  
        FDatewe7.push(JSON.stringify("{{ $weekend }}")); 
    @endforeach
    @foreach($arrayweekend[8] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe8.push(mydate); 
        FDatewe8.push(JSON.stringify("{{ $weekend }}"));  
    @endforeach
    @foreach($arrayweekend[9] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe9.push(mydate);  
        FDatewe9.push(JSON.stringify("{{ $weekend }}")); 
    @endforeach
    @foreach($arrayweekend[10] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe10.push(mydate); 
        FDatewe10.push(JSON.stringify("{{ $weekend }}"));  
    @endforeach
    @foreach($arrayweekend[11] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe11.push(mydate); 
        FDatewe11.push(JSON.stringify("{{ $weekend }}"));  
    @endforeach
    @foreach($arrayweekend[12] as $weekend)
        var mydate = new Date('{{ $weekend }}');
        arDatewe12.push(mydate);
        FDatewe12.push(JSON.stringify("{{ $weekend }}"));   
    @endforeach

    $("#calendar01").val('['+FDatewe1+']');
    $("#calendar02").val('['+FDatewe2+']');
    $("#calendar03").val('['+FDatewe3+']');
    $("#calendar04").val('['+FDatewe4+']');
    $("#calendar05").val('['+FDatewe5+']');
    $("#calendar06").val('['+FDatewe6+']');
    $("#calendar07").val('['+FDatewe7+']');
    $("#calendar08").val('['+FDatewe8+']');
    $("#calendar09").val('['+FDatewe9+']');
    $("#calendar10").val('['+FDatewe10+']');
    $("#calendar11").val('['+FDatewe11+']');
    $("#calendar12").val('['+FDatewe12+']');
   
    $('#mdp-democalendar01').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-01-01",
        addDates: arDatewe1,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar01").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar02').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-02-01",
        addDates: arDatewe2,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar02").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar03').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-03-01",
        addDates: arDatewe3,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar03").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar04').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-04-01",
        addDates: arDatewe4,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar04").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar05').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-05-01",
        addDates: arDatewe5,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar05").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar06').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-06-01",
        addDates: arDatewe6,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar06").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar07').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-07-01",
        addDates: arDatewe7,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar07").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar08').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-08-01",
        addDates: arDatewe8,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar08").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar09').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-09-01",
        addDates: arDatewe9,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar09").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar10').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-10-01",
        addDates: arDatewe10,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar10").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar11').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-11-01",
        addDates: arDatewe11,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar11").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })

    $('#mdp-democalendar12').multiDatesPicker({
        dateFormat: "yy-mm-dd",
        defaultDate:"{{$year}}-12-01",
        addDates: arDatewe12,
        firstDay: 6,
        onSelect: function() {
            console.log($(this).multiDatesPicker("getDates"))
            $("#calendar12").val(JSON.stringify($(this).multiDatesPicker("getDates")))
        }
    })


    // submit form
    $('#submitLT').click(function () {
        swal({
            title: "Are you sure?",
            text: "Confirm Submit",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm_edit')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('updateMeasuresedit') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    if (data.success == 1) {
                        swal("Success", "Submit save data success", "success");
                        setTimeout(function () {
                            window.location.href = "{{ url('output/measure') }}";
                        }, 1000);
                    }

                }
            });

        });
    });
</script>
@endsection