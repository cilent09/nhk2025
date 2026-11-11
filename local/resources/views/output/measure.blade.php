
@extends('layouts.main')
@section('title', 'Measurement L/T setting')
@section('breadcrumb-title', 'Measurement L/T setting')


@section('content')

<style>

.ui-datepicker-calendar {
   display: none;
}

</style>


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div class="card-block">
                        
                            <div class="dt-responsive table-responsive">
                                <div style="text-align: right">
                                    
                                               
                                </div>
                                <br>
                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="150"># </th>
                                            <th class="text-center">ITEM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($measure as $key => $value)
                                        <tr>
                                            @if($value->id_tsetting == 1)
                                                <th class="text-center" width="150">
                                                   <button class="btn btn-info btn-sm" onclick="view({{$value->id_tsetting}},0)" data-toggle="modal" data-target="#large-Modal" title="View"><i class="fa fa-search" style="font-size:18px;"></i></button>&nbsp;
                                                    <button class="btn btn-warning btn-sm" onclick="view({{$value->id_tsetting}},1)" data-toggle="modal" data-target="#large-Modal"><i class="fa fa-pencil" title="edit" style="font-size:18px;"></i></button>&nbsp;
                                                </th>
                                            @else
                                                <th class="text-center" width="150">
                                                   <button class="btn btn-info btn-sm" onclick="view2({{$value->id_tsetting}},0)" data-toggle="modal" data-target="#large_modal2" title="View"><i class="fa fa-search" style="font-size:18px;"></i></button>&nbsp;
                                                    <button class="btn btn-warning btn-sm" onclick="view2({{$value->id_tsetting}},1)" data-toggle="modal" data-target="#large_modal2" title="edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button>&nbsp;
                                                </th>
                                            @endif

                                            <th class="text-left" style=" padding-left: 35px; ">{{ $value->name }}</th>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>

 
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">UCL of Measurement L/T (Day)</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="id_ucl" id="id_ucl" /> 
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="text-center">LPC</th>
                                <th class="text-center">SEM</th>
                                <th class="text-center">IC</th>
                                <th class="text-center">GCMS</th>
                                <th class="text-center">FTIR</th>
                                <th class="text-center">Ghost</th>
                            </tr>
                        </thead>
                        <tbody id="modelparaview">
                            <tr>
                                <td><input type="number" name="LPC" id="LPC" class="form-control" /></td>
                                <td><input type="number" name="SEM" id="SEM" class="form-control" /></td>
                                <td><input type="number" name="IC" id="IC" class="form-control" /></td>
                                <td><input type="number" name="GCMS" id="GCMS" class="form-control" /></td>
                                <td><input type="number" name="FTIR" id="FTIR" class="form-control" /></td>
                                <td><input type="number" name="Ghost" id="Ghost" class="form-control" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                    <button type="button" id="resettLT" class="btn btn-dark waves-effect">RESET</button>
                    <button type="button" id="submitLT" class="btn btn-success waves-effect">SAVE</button>
                </div>
            </div>
        </div>
</div>


<div class="modal fade" id="large_modal2" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Holiday for subtract the Measurement L/T</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <input type="hidden" name="id_ucl2" id="id_ucl2" /> 
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Year</label>
                        <div class="col-sm-10">
                            <input type="number" name="selectyear" id="selectyear" placeholder="YYYY" min="2022" max="2200" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                    <button type="button" id="submithd" class="btn btn-success waves-effect">Continue</button>
                </div>
            </div>
        </div>
</div>


<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">
<input type="hidden" id="st" name="st" />
@endsection

@section('scripts')
<script>

//------------------ UCL of Measurement L/T (Day) -----------------    
    function view(id,st)
    {
        $.ajax({
            type: 'get',
            url: "{{ url('viewmeasures')}}",
            data: {
                'id' : id
            },
            success: function (data) {
                $('#id_ucl').val(data.measure_day.id_ucl);
                $('#LPC').val(data.measure_day.lpc);
                $('#SEM').val(data.measure_day.sem);
                $('#IC').val(data.measure_day.ic);
                $('#GCMS').val(data.measure_day.gcms);
                $('#FTIR').val(data.measure_day.ftir);
                $('#Ghost').val(data.measure_day.ghost);
            }
        });

        $('#submitLT').show();
        $('#resettLT').show();
        if(st == 0){
            $('#submitLT').hide();
            $('#resettLT').hide();
        }
    }

    // Reset L/T (day)
    $('#resettLT').click(function () {
        $('#LPC').val(0);
        $('#SEM').val(0);
        $('#IC').val(0);
        $('#GCMS').val(0);
        $('#FTIR').val(0);
        $('#Ghost').val(0);
    });

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

            var id = $('#id_ucl').val();
            var lpc = $('#LPC').val();
            var sem = $('#SEM').val();
            var ic = $('#IC').val();
            var gcms = $('#GCMS').val();
            var ftir = $('#FTIR').val();
            var ghost = $('#Ghost').val();

            $.ajax({
                url: "{{ url('updateMeasures') }}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "id": id,
                    "lpc": lpc,
                    "sem": sem,
                    "ic": ic,
                    "gcms": gcms,
                    "ftir": ftir,
                    "ghost": ghost,
                    "_token": '{{ csrf_token() }}',
                },
                success: function (data) {
                    if (data.success == 1) {
                        swal("Success", "Submit save data success", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                    
                    }
                     
                }
            });
        });
    });


//---------- Holiday for subtract the Measurement L/T --------------    
    

    function view2(id,st)
    {
        $('#id_ucl2').val(id);
        $('#st').val(st);

    }

    // submit form
    $('#submithd').click(function () {

        var id_ucl = $('#id_ucl2').val();
        var selectyear = $('#selectyear').val();
        var st = $('#st').val();
        if(parseInt(selectyear) > 0){
            window.location.href = "{{ url('measureedit') }}/"+id_ucl+"/"+selectyear+"/"+st;
        }

    });


          
</script>
@endsection