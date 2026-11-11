@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Model')
@section('breadcrumb-title', 'Model')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">

            <div class="card-block p-0">
                 
                    </ul>

                    @if(session('namemenu') == "oqa")
                    <!-- Tab panes -->
                    <div class="tab-content card-block" > 
                        <div class="tab-pane active" id="home3" role="tabpanel">

                            <div class="card-header">
                                <a href="../master/addmodel"><button class="btn btn-primary"><i class="fa fa-plus"></i> Add model</button></a>
                            </div>
                            
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-12">
                                             <div class="dt-responsive table-responsive">
                                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center" width="150">#</th>
                                                        <th class="text-center" width="180">Model</th>
                                                        <th class="text-center">Drive Name</th>
                                                        <th class="text-center">Part number</th>
                                                        <th class="text-center">Digit lot number</th>
                                                        <th class="text-center">Surface area</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div> 
                                        </div>
                                    </div>
                                </div>
 
                        </div>

                        @else

                        <div class="tab-pane" id="profile3" role="tabpanel">

                            <div class="card-header">
                                <a href="../master/addmodelWD"><button class="btn btn-primary"><i class="fa fa-plus"></i> Add model</button></a>
                                <a href="../local/storage/NHK-File/template/report/Template_SCC.xlsx"><button class="btn btn-primary">ตัวอย่างไฟล์ SCC</button></a>
                            </div>
                            
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="col-12">

                                            <div class="dt-responsive table-responsive">
                                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables_wd">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center" width="150">#</th>
                                                        <th class="text-center" width="180">Model</th>
                                                        <th class="text-center">Drive Name</th>
                                                        <th class="text-center">Part number</th>
                                                        <th class="text-center">Digit lot number</th>
                                                        <th class="text-center">Surface area</th>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                        </div>

                        @endif
                        
                    </div>

            </div> 

        </div>
        
    </div>
</div>

<!-- Upload SCC Tempalte file -->
<form id="form" method="post" enctype="multipart/form-data">
@csrf
    <div class="modal fade" id="UploadSCC" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalCenterTitle">SCC Report</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="id_model_scc" name="id_model_scc" />
            
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">File:</label>
                <input type="file" id="scc_file" name="scc_file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" />
                <br><code>Only .xlsx Excel files are supported.</code>
            </div>
            
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Upload</button>
            <a type="button" id="a_dnw" class="btn btn-success"><i class="fa fa-download"></i> Download file</a>
        </div>
        </div>
    </div>
    </div>
</form>


<input type="hidden" id="userstatus" value="{{Auth::user()->status}}">
@endsection

@section('scripts')

<script>
    var userstatus = $('#userstatus').val();
    $(document).ready(function() {

        @if(session('namemenu') == "oqa")
            //----------- SGT TABLE ----------

            var oTable = $('#datatables').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                lengthChange: true,
                ajax: {
                    url: "{{url('master/datatables')}}",
                },
                columns: [
                    {'className': "text-center", data: 'id_model', name: 'id_model'},
                    {data: 'name_model', name: 'name_model'},
                    {data: 'drive_name', name: 'drive_name'},
                    {data: 'part_number', name: 'part_number'},
                    {data: 'digit_lot_number', name: 'digit_lot_number'},
                    {data: 'surface_area', name: 'surface_area'},
                ],
                "order": [[ 0, "desc" ]],
                rowCallback: function (row, data, index) {
                    var button = '';
                    button += '<a href="viewmodel/' + data.id_model + '"><button class="btn btn-info btn-sm" id="searchModel" title="View" ><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                    button += '<a href="editmodel/' + data.id_model + '/1"><button class="btn btn-warning btn-sm"  title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    button += '<a href="editmodel/' + data.id_model + '/2"><button class="btn btn-success btn-sm"  title="Duplicate"><i class="fa fa-clipboard" style="font-size:18px;"></i></button></a>&nbsp;';
                    if(userstatus == 1)
                        {
                    button += '<button class="btn btn-danger btn-sm" onclick="deletemodel(' + data.id_model + ')" title="Delete"><i class="fa fa-trash" style="font-size:18px;"></i></button>&nbsp;';
                        }
                    $('td:eq(0)', row).html(button);

                    //---------------------------------

                    var new_surface_area = data.surface_area;
                    new_surface_area += ' cm2';

                    $('td:eq(5)', row).html(new_surface_area);

                    //---------------------------------

                    var new_digit_lot_number = '';
                    var array_digi = [];
                
                    array_digi.push(data.digit_lot_number);

                    if(data.digit_lot_number1 !== null && data.digit_lot_number1 !== '') {
                        array_digi.push(data.digit_lot_number1);
                    }
                    if(data.digit_lot_number2 !== null && data.digit_lot_number2 !== '') {
                        array_digi.push(data.digit_lot_number2);
                    }
                    $('td:eq(4)', row).html(array_digi.toString());


                }
            });

        @else     
            //----------- WD TABLE ----------

            var oTablewd = $('#datatables_wd').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                lengthChange: true,
                ajax: {
                    url: "{{url('master/datatableswd')}}",
                },
                columns: [
                    {'className': "text-center", data: 'id_model', name: 'id_model'},
                    {data: 'name_model', name: 'name_model'},
                    {data: 'drive_name', name: 'drive_name'},
                    {data: 'part_number', name: 'part_number'},
                    {data: 'digit_lot_number', name: 'digit_lot_number'},
                    {data: 'surface_area', name: 'surface_area'},
                ],
                "order": [[ 0, "desc" ]],
                rowCallback: function (row, data, index) {
                    var button = '';

                    var scc_file = data.scc_template_file;

                    button += '<a href="viewmodelWD/' + data.id_model + '"><button class="btn btn-info btn-sm" id="searchModel" title="View" ><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                    button += '<a href="editmodelWD/' + data.id_model + '/1"><button class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    button += '<a href="editmodelWD/' + data.id_model + '/2"><button class="btn btn-success btn-sm" title="Duplicate"><i class="fa fa-clipboard" style="font-size:18px;"></i></button></a>&nbsp;';

                    if(scc_file == null){
                        button += '<button class="btn btn-primary btn-sm" onclick="openscc(' + data.id_model + ')" title="Template SCC" data-toggle="modal" data-target="#UploadSCC"><i class="fa fa-upload mr-2" style="font-size:18px;"></i> SCC Report</button>&nbsp;';
                    }else{
                        button += '<button class="btn btn-success btn-sm" onclick="openscc(' + data.id_model + ')" title="Template SCC" data-toggle="modal" data-target="#UploadSCC"><i class="fa fa-upload mr-2" style="font-size:18px;"></i> SCC Report</button>&nbsp;';
                    }

                    if(userstatus == 1)
                        {
                    button += '<button class="btn btn-danger btn-sm" onclick="deletemodel(' + data.id_model + ')" title="Delete"><i class="fa fa-trash" style="font-size:18px;"></i></button>&nbsp;';
                        }
                    $('td:eq(0)', row).html(button);

                    //---------------------------------

                    var new_surface_area = data.surface_area;
                    new_surface_area += ' cm2';

                    $('td:eq(5)', row).html(new_surface_area);

                    //---------------------------------

                    var new_digit_lot_number = '';
                    var array_digi = [];
                
                    array_digi.push(data.digit_lot_number);

                    if(data.digit_lot_number1 !== null && data.digit_lot_number1 !== '') {
                        array_digi.push(data.digit_lot_number1);
                    }
                    if(data.digit_lot_number2 !== null && data.digit_lot_number2 !== '') {
                        array_digi.push(data.digit_lot_number2);
                    }
                    $('td:eq(4)', row).html(array_digi.toString());


                }
            });

        @endif     

    });

    function deletemodel(id) {
        swal({
                title: "Are you sure?",
                text: "You wont be able to revert this!",
                type: "error",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Delete",
                closeOnConfirm: false
            },
            function () {

                $.ajax({
                    'dataType': 'json',
                    'type': 'post',
                    'url': "{{url('deletemodelSubmit')}}",
                    'data': {
                        'id': id,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Deleted!',
                                'Your file has been deleted.',
                                'success');
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('master/model') }}";
                            }, 1000);
                        } else {

                        }
                    }
                });
            });
    }

    //---- SCC -----

    function openscc(id){
        $('#id_model_scc').val(id);

        $.ajax({
            'dataType': 'json',
            'type': 'post',
            'url': "{{ url('get_scc_file_template') }}",
            'data': {
                'id': id,
                '_token': "{{ csrf_token() }}"
            },
            success: function (data) {
                if(data.file){
                    var link = "../local/storage/app/public/SCC_template/"+data.file;
                    $('#a_dnw').attr('href', link);
                }else{
                    $('#a_dnw').remove();
                }
                

            }
        });
    }

    $('#form').submit(function(e) {
        e.preventDefault();

        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });

        $('#UploadSCC').hide();
        var formData = new FormData($(this)[0]);

        $.ajax({
            url: '{{ url("scc_add_file") }}',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {

                if (data.success == 1) {
                    swal({
                        title: "บันทึกข้อมูลเรียบร้อย",
                        type: "success",
                        confirmButtonColor: '#84CC18',
                        confirmButtonText: 'ยืนยัน',
                        timer: 2000
                    }, function() {
                        location.reload();
                    });
                } else {
                    swal({
                        title: "ผิดพลาด",
                        text: data.error,
                        type: "error",
                        confirmButtonColor: '#84CC18',
                        confirmButtonText: 'ปิด',
                    });
                }
            }
        });
    });


    // -- Start msg err ที่ถูกส่งมา --
    function printErrorMsg(msg) {
        // console.log(msg);
        $('._err').text('');
        $.each(msg, function(key, value) {
            $('.' + key + '_err').text(`*${value}*`);
            let TextKey = key.split(".", 2);
            console.log(TextKey);
            if (TextKey.length == 2) {
                $(`#storeModal .detailLot:eq(${TextKey[1]}) .${TextKey[0]}_err`).text(`*${value}*`);
                $(`#pickOut .box_pick_out:eq(${TextKey[1]}) .${TextKey[0]}_err`).text(`*${value}*`);
            }
        });
    }



</script>

@endsection
