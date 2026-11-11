@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Create control limit')
@section('breadcrumb-title', 'Create control limit')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            <a href="{{ url('/master/createdate') }}/{{$model_type}}"><button class="btn btn-primary"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Create date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </button></a>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                        <thead>
                        <tr>
                            <th class="text-center" width="150">#</th>
                            <th class="text-center">Date Start</th>
                            <th class="text-center">Date End</th>
                            <th class="text-center" width="150">Control limit</th>
                            <th class="text-center" width="150">Status</th>
                        </tr>
                        </thead>
                    </table>
                </div>


                <form class="custom-validation"  method="post" id="fm-control" enctype="multipart/form-data">
                {{ csrf_field() }}
                    <div class="modal fade" id="myModaldate">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div id="title"> <h4 class="modal-title">Control limit file</h4></div>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <br>
                                <br>
                                <div  id="datechk" class="col-sm-12">
                                    <div class="col-md-10 col-xl-12">
                                        <input type="hidden" id="id_chistory" name="id_chistory">
                                        <input class="form-control" type="file" id="customFile" name="customFile">
                                    </div>
                                </div>
                                <br>
                                <br>
                                <div class="modal-footer"> 
                                    <button type="button" id="submitupload" class="btn btn-primary">Update</button>
                                    <a href="#" data-dismiss="modal" class="btn btn-secondary">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>


                <form class="custom-validation"  method="post" id="fm-edit-controllimit" enctype="multipart/form-data">
                {{ csrf_field() }}
                    <div class="modal fade" id="myEditModaldate">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <div id="title"> <h4 class="modal-title">Edit Control limit file</h4></div>
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                </div>
                                <br>
                                <br>
                                <div  id="datechk" class="col-sm-12">
                                    <!-- <div class="col-md-10 col-xl-12">
                                        <input class="form-control" type="file" id="edit_customFile" name="edit_customFile">
                                    </div> -->

                                    <input type="hidden" id="edit_id_chistory" name="edit_id_chistory">
                                    <div class="col-md-10 col-xl-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <label> Start Date</label>
                                                <input type="date" class="form-control" name="date_start" id="date_start" />
                                            </div>
                                            <div class="col-sm-6">
                                                <label> End Date</label>
                                                <input type="date" class="form-control" name="date_end" id="date_end" />
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <br>
                                <br>
                                <div class="modal-footer"> 
                                    <button type="button" id="editsubmitupload" class="btn btn-primary">Update</button>
                                    <a href="#" data-dismiss="modal" class="btn btn-secondary">Close</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">
@endsection

@section('scripts')

<script>
    var userstatus = $('#userstatus').val();
    $(document).ready(function() {
        var oTable = $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: true,
            ajax: {
                url: "{{url('master/contollimittables')}}/{{$model_type}}",
            },
            columns: [
                {'className': "text-center", data: 'id_chistory', name: 'id_chistory'},
                {data: 'date_start', name: 'date_start'},
                {data: 'date_end', name: 'date_end'},
                {'className': "text-center", data: 'id_chistory', name: 'id_chistory'},
                {'className': "text-center", data: 'id_chistory', name: 'id_chistory'},
                
            ],
            "order": [[ 0, "desc" ]],
            rowCallback: function (row, data, index) {
                var button = '';
                if(userstatus == 1)
                {
                    button += '<button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#myEditModaldate" onclick="setValueinmodalEdit(' + data.id_chistory + ')" title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button>&nbsp;';

                    button += '<button class="btn btn-danger btn-sm" onclick="deleteid(' + data.id_chistory + ')" title="Delete"><i class="fa fa-trash-o" style="font-size:18px;"></i></button>&nbsp;';
                }
                $('td:eq(0)', row).html(button);


                if( data.date_end == "2060-12-31")
                {
                    $('td:eq(2)', row).html("ปัจจุบัน");
                }

                //---------------------------------


                var button2 = '';
                button2 += '<img data-toggle="modal" data-target="#myModaldate" onclick="setValueinmodal(' + data.id_chistory + ')" class="img-fluid" width="50px" src="../../template/assets/images/controllimit/1.png" alt="Upload" />&nbsp;&nbsp;';
                if(data.file){
                    button2 += '<a href="../../local/storage/app/public/controllimit/' + data.file + '"><img class="img-fluid" width="50px" src="../../template/assets/images/controllimit/2.png" alt="Download" /></a>&nbsp;&nbsp;';

                    button2 += '<a href="../viewcontrollimit/' + data.id_chistory + '"><img class="img-fluid" width="50px" src="../../template/assets/images/controllimit/3.png" alt="View" /></a>';

                }else{
                    button2 += '<img class="img-fluid" width="50px" src="../../template/assets/images/controllimit/2_2.png" alt="Download" />&nbsp;&nbsp;';
                    button2 += '<img class="img-fluid" width="50px" src="../../template/assets/images/controllimit/3_2.png" alt="View" />';

                }
                $('td:eq(3)', row).html(button2);

                //---------------------------------

                var button3 = '';

                if(data.chkstatus == 0){

                    button3 += '<div class="dropdown">';
                    button3 += '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    button3 += 'Not Active';
                    button3 += '</button>';
                    button3 += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    button3 += '<a class="dropdown-item" onclick="swichActive(' + data.id_chistory + ',1)" href="#">Active</a>';
                    button3 += '</div>';
                    button3 += '</div>';

                }else{

                    button3 += '<div class="dropdown">';
                    button3 += '<button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    button3 += 'Active';
                    button3 += '</button>';
                    button3 += '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                    button3 += '<a class="dropdown-item" onclick="swichActive(' + data.id_chistory + ',0)" href="#">Not Active</a>';
                    button3 += '</div>';
                    button3 += '</div>';

                }

                $('td:eq(4)', row).html(button3);



            }
        }); 

    });


    function setValueinmodal(id)
    {
        $('#id_chistory').val(id);
    }

    function setValueinmodalEdit(id)
    {
        $('#edit_id_chistory').val(id);

        $.ajax({
            url: "{{ url('getdateControllimit') }}",
            type: 'POST',
            dataType: "JSON",
            data: {
                "id": id,
                "_token": '{{ csrf_token() }}',
            },
            success: function(data) {

               $('#date_start').val(data.history.date_start);
               $('#date_end').val(data.history.date_end);

            }
        });
    }


    // submit form
    $('#submitupload').click(function () {
        swal({
            title: "Are you sure?",
            text: "Confirm Submit Upload",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-control')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('uplaodControlfile') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
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


    // submit form by edit btn
    $('#editsubmitupload').click(function () {
        swal({
            title: "Are you sure?",
            text: "Confirm Submit Upload",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#fm-edit-controllimit')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('updateControlfile') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    console.log(data);
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

    // Swich Active
    function swichActive(id,status)
    {
       
        swal({
            title: "Are you sure?",
            text: "Confirm Change Status Control limit",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            $.ajax({
                url: "{{ url('changestatuscontrol') }}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "id": id,
                    "status": status,
                    "_token": '{{ csrf_token() }}',
                },
                success: function(data) {
                    if (data.success == 1) {
                        swal("Success", "save data success", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        swal("Warning", "Please check the information again.", "warning");
                    }
                }
            });
            
        });

    }

    // Delete control limit
    function deleteid(id)
    {
        swal({
            title: "Are you sure?",
            text: "Confirm delete control limit",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            $.ajax({
                url: "{{ url('deleteControllimit') }}",
                type: 'POST',
                dataType: "JSON",
                data: {
                    "id": id,
                    "_token": '{{ csrf_token() }}',
                },
                success: function(data) {

                   if (data.success == 1) {
                        swal("Success", "delete data success", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        
                    }

                }
            });
        });

    }

</script>

@endsection
