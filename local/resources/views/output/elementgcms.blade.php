
@extends('layouts.main')
@section('title', 'Compound GCMS')
@section('breadcrumb-title', 'Compound GCMS')


@section('content')

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">

                    <div class="row">
                        <div class="col-12" style="text-align: center; color: white; background-color: #7F7F7F; padding: 5px;">
                            <h4>Possible source</h4>
                        </div>
                    </div>
                    <br>
                    @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                    <div class="row">
                        <div class="col-6" style="text-align: right;">
                            <label>&nbsp;</label><br>
                            <button type="button" data-toggle="modal" data-target="#large_modal2" title="View" class="btn btn-primary"><i class="fa fa-plus"></i> Add Compound</button>
                        </div>
                        <div class="col-6" style="text-align: right;">
                            <label>&nbsp;</label><br>
                            <button type="button" data-toggle="modal" data-target="#large_modal3" class="btn btn-primary"><i class="fa fa-plus"></i> Add Possible source</button>
                        </div>
                    </div>
                    <br>
                    @endif
                    <div class="row">
                        <div class="col-sm-12 col-xl-6">
                            <label>Compound</label>
                            <select class="js-example-data-array col-sm-12" id="getcompound" name="getcompound">
                                <option value="" selected>Select Compound</option>
                                @foreach($unit as $item)
                                <option value="{{$item->id_cmp}}" >{{$item->compound_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-xl-6">
                            <label>Possible source</label>
                            <ul id="divlistshowpossible" class="list-group">
                                <li class="list-group-item"> >>Select Compound << </li>
                            </ul>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6" style="text-align: center;">
                            <label>&nbsp;</label><br>
                            <button id="resetsearch" type="button" class="btn btn-secondary">Reset</button>
                            <button type="button" id="searchpossible" class="btn btn-success">Search</button>
                        </div>
                    </div>

                    <br><br>

                    <div class="row">
                        <div class="col-12" style="text-align: center; color: white; background-color: #7F7F7F; padding: 5px;">
                            <h4>Top 5 compound</h4>
                        </div>
                    </div>
                    <br>
                    <form method="post" id="fm-view">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-sm-12 col-xl-6">
                            <label>Model</label>
                            <select class="js-example-data-array col-sm-12" id="chkmodel" name="chkmodel">
                                <option value="" selected>Select Model</option>
                                @foreach($datalist as $item)
                                <option value="{{$item->id_model}}" >{{$item->name_model}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-12 col-xl-6">
                            <label>Lot number</label>
                            <input type="text" name="textlot" id="textlot" class="form-control">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-sm-12 col-xl-6">
                            <label>Start date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-6">
                            <label>End date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div> 
                    </div>
                    <div class="row">
                        <div class="col-12" style="text-align: right;">
                            <label>&nbsp;</label><br>
                            <button id="chkResult" type="button" class="btn btn-success"><i class="fa fa-search"></i> Result</button>
                        </div>
                    </div>
                    </form>

                    
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="large_modal2" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Compound</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <input type="text" name="new_compound" id="new_compound" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                    <button type="button" id="submitcreate_compound" class="btn btn-success waves-effect">Save</button>
                </div>
            </div>
        </div>
</div>


<form class="wizard-form" method="post" tabindex="-1" id="fm_possible">
{{ csrf_field() }}
    <div class="modal fade" id="large_modal3" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">New Possible source</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Compound</label>
                        <div class="col-sm-12">
                            <select class="js-example-data-array-modal col-sm-12" id="select_compound" name="select_compound">
                                <option value="" selected>Select Compound</option>
                                @foreach($unit as $item)
                                <option value="{{$item->id_cmp}}" >{{$item->compound_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="staticEmail" class="col-sm-6 col-form-label">New Possible source</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" name="possible[]" id="possible_1"><br>
                            <input type="text" class="form-control" name="possible[]" id="possible_2"><br>
                            <input type="text" class="form-control" name="possible[]" id="possible_3"><br>
                            <input type="text" class="form-control" name="possible[]" id="possible_4"><br>
                            <input type="text" class="form-control" name="possible[]" id="possible_5"><br>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                    <button type="button" id="submitcreate_possible" class="btn btn-success waves-effect">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="large_modal4" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Top 5 compound</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                  <thead>
                                    <tr>
                                      <th scope="col">No</th>
                                      <th scope="col">Compound</th>
                                      <th scope="col">Quantity(ng/part)</th>
                                      <th scope="col">Possible source</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <th scope="row">1</th>
                                      <td id="cmp_1"></td>
                                      <td id="qty_1"></td>
                                      <td id="pos_1"></td>
                                    </tr>
                                    <tr>
                                      <th scope="row">2</th>
                                      <td id="cmp_2"></td>
                                      <td id="qty_2"></td>
                                      <td id="pos_2"></td>
                                    </tr>
                                    <tr>
                                      <th scope="row">3</th>
                                      <td id="cmp_3"></td>
                                      <td id="qty_3"></td>
                                      <td id="pos_3"></td>
                                    </tr>
                                    <tr>
                                      <th scope="row">4</th>
                                      <td id="cmp_4"></td>
                                      <td id="qty_4"></td>
                                      <td id="pos_4"></td>
                                    </tr>
                                    <tr>
                                      <th scope="row">5</th>
                                      <td id="cmp_5"></td>
                                      <td id="qty_5"></td>
                                      <td id="pos_5"></td>
                                    </tr>
                                  </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
</div>

<form class="wizard-form" method="post" tabindex="-1" id="fmedit_possible">
{{ csrf_field() }}
    <div class="modal fade" id="large_modal5" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Possible source</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <div class="col-sm-12">
                            <input type="hidden" name="edit_id_possible" id="edit_id_possible" />
                            <input type="text" name="edit_possible" id="edit_possible" class="form-control" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                    <button type="button" id="submitedit_possible" class="btn btn-success waves-effect">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>



<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">

@endsection

@section('scripts')
<script>
    // show select2 in Modal
    $(document).ready(function() {
        $(".js-example-data-array-modal").select2({
            dropdownParent: $("#large_modal3")
        });
    });     

    $("#searchpossible").on("click", function(){ 

        $('#divlistshowpossible li').remove();
        var list = $("#divlistshowpossible"); 
        var compound = $('#getcompound').val();

        $.ajax({
            type: 'post',
            url: "{{ url('gcmspossible') }}",
            data: {
                compound: compound,
            },
            success: function (data) {

                var count = data.data.length; 
                if(count > 0){
                    @if (Auth::user()->status == 1)   
                        $.each(data.data, function(i, item) {
                            list.append('<li class="list-group-item">'+item.possible_name+' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="btn-group" role="group" aria-label="manag-tab"><button onclick="editpossible('+item.id_cms+')" type="button" class="btn btn-warning"><i class="fa fa-pencil"></i></button><button type="button" onclick="deletepossible('+item.id_cms+')" class="btn btn-danger"><i class="fa fa-trash"></i></button></div></li>');
                        });
                    @else
                        $.each(data.data, function(i, item) {
                            list.append('<li class="list-group-item">'+item.possible_name+'</li>');
                        });
                    @endif

                }else{
                    list.append('<li class="list-group-item"> >> No data found << </li>');
                }

            }
                        
        });


    } );

    $("#resetsearch").on("click", function(){ 
        $('#divlistshowpossible li').remove();
        var list = $("#divlistshowpossible"); 
        list.append('<li class="list-group-item"> >> Select Compound << </li>');
    } );


    // submit form compound
    $('#submitcreate_compound').click(function () {
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

            var name_compound = $('#new_compound').val();

            $.ajax({
                url: "{{ url('createCompoundgcms') }}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "name_compound": name_compound,
                    "_token": '{{ csrf_token() }}',
                },
                success: function (data) {
                    if (data.success == 1) {
                        swal("Success", "Submit save data success", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        swal("warning", "Duplicate compound name", "warning");
                    }
                     
                }
            });
        });
    });

    // submit form possible
    $('#submitcreate_possible').click(function () {

            var forms = $('#fm_possible')[0];
            var data = new FormData(forms);

            var compound = $('#select_compound').val();

            if($.isNumeric(compound) == true){
                $.ajax({
                    type: 'POST',
                    url: "{{ url('createPossible') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {

                        $('#large_modal3').modal('toggle');

                        if (data.success == 1) {
                            swal("Success", "Submit save data success", "success");
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        }else{

                            $('#large_modal3').modal('toggle');
                            swal("warning", "Please fill in the information", "warning");
                            
                        }

                    }
                });
            }else{
                $('#large_modal3').modal('toggle');
                swal("warning", "Please fill in the information", "warning");
            }
 
    });    


    // submit form for show top 5
    $('#chkResult').click(function () {

        var forms = $('#fm-view')[0];
        var data = new FormData(forms);
        $.ajax({
            type: 'POST',
            url: "{{ url('viewcompoundgcms') }}",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {

                for (var j=1; j < 6; j++){
                    $('#cmp_'+j).text();
                    $('#qty_'+j).text();
                    $('#pos_'+j).text();
                }

                if (data.success == 1) {
                    var ndata = data.data.showtop; 
                    $('#large_modal4').modal('show');

                    for (var i=0; i < (ndata['name'].length); i++){
                        
                        var x = i+1;

                        $('#cmp_'+x).text(ndata['name'][i]);
                        $('#qty_'+x).text(ndata['qty'][i]);
                        $('#pos_'+x).text(ndata['possible'][i]);
                    }
                }else{
 
                    // swal("warning", "No data found", "warning");
                    $('#large_modal4').modal('show');
                    for (var i=0; i <= 5; i++){
                        var x = i+1;

                        $('#cmp_'+x).text('-');
                        $('#qty_'+x).text('-');
                        $('#pos_'+x).text('-');

                    }

                }
              
            }
        });
 
    });   

    // Edit data Possible
    function editpossible(id)
    {

            $.ajax({
                url: "{{ url('viewdatapossible') }}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "id": id,
                    "_token": '{{ csrf_token() }}',
                },
                success: function (data) {

                    $('#large_modal5').modal('show');

                    if (data.success == 1) {
                        $('#edit_id_possible').val(data.possible.id_cms);
                        $('#edit_possible').val(data.possible.possible_name);
                    } 
                     
                }
            });


    }

    // Delete data Possible
    function deletepossible(id)
    {
        swal({
            title: "Are you sure?",
            text: "Delete this Possible source",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            $.ajax({
                url: "{{ url('deletepossible') }}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "id": id,
                    "_token": '{{ csrf_token() }}',
                },
                success: function (data) {
                    if (data.success == 1) {
                        swal("Success", "Delete success", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                     
                }
            });
        });
    }

    // submit edit form possible
    $('#submitedit_possible').click(function () {

        var forms = $('#fmedit_possible')[0];
        var data = new FormData(forms);

            $.ajax({
                type: 'POST',
                url: "{{ url('updatePossible') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {

                    $('#large_modal5').modal('toggle');

                    if (data.success == 1) {
                        swal("Success", "Submit save data success", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }else{

                        $('#large_modal5').modal('toggle');
                        swal("warning", "Please fill in the information", "warning");
                        
                    }

                }
            });

    });
    

</script>
@endsection