

@extends('layouts.main')
@section('title', 'TGA date code (History)')
@section('breadcrumb-sub', 'TGA date code (History)')

<style>

    .bg-image {
        background-image: url("{{ asset('template/assets/images/item/calendar_icon.png')}}");
        background-repeat: no-repeat;
        background-position: center;
    }

</style>

@section('content')
<div class="row">
    <div class="col-md-12 col-xl-4">
        <div class="card" style=" min-height: 140px; ">
            <div class="card-header">
                <h4 class="d-inline-block">Model Name : <font color="red">ALL models</font></h4>
                <h4 class="d-inline-block">Part Number : <font color="red">ALL part number</font></h4>
                <br><br>
            </div>   
        </div>
    </div>
    <!-- 8.Edit icon OQA -->
    <div class="col-md-12 col-xl-8">
        <div class="card" style=" min-height: 140px; ">
            <div class="card-header">
                <table style=" width: 100%;">
                    <tr>
                        <td>
                            <h4 class="d-inline-block">ICON Status :</h4>
                        </td>
                        <td style=" text-align: center; width: 110px;">
                            <h4 class="d-inline-block"><img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; "></h4>
                        </td>
                        <td style="">
                            <h4 class="d-inline-block"> Download</h4>
                        </td>
                        <td style=" text-align: center; width: 110px;">
                        <h4 class="d-inline-block"><img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Confirm" style=" width: 100px; "></h4>
                        </td>
                        <td style="">
                            <h4 class="d-inline-block">Upload</h4>
                        </td>
                    </tr>
                </table>
            </div>   
        </div>
    </div>
    <!-- 8.Edit icon OQA -->
</div>

<div class="row"> 
    <div class="col-md-12 col-xl-4">
        <div class="card widget-statstic-card borderless-card" style="background-image: url('{{ asset('template/assets/images/item/calendar_icon2.png')}}'); 
                    background-size: cover; 
                    background-size: 40%;
                    background-position: center; 
                    background-repeat: no-repeat;
                    min-height: 200px;">
            <div class="card-header">
                <div class="text-center mt-3">
                    <h3 class="d-inline-block">TGA date code (History)</h3><br>
                    <h3 class="d-inline-block">All models</h3>
                </div>
                <div class="text-center mt-3">
                    <a href="#" onclick="downloads()">
                        <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style="width: 100px;">
                    </a>
                    <a href="#" onclick="upload()">
                        <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" style="width: 100px;">
                    </a>
                </div>
            </div>
        </div>
    </div> 
</div>

<form id="excelUploadForm" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="default-upload" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload TGA File</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <center><div class="row">
                        <div class="col-sm">
                            <input type="file" id="excel_file" name="excel_file" accept=".xlsx" />
                        </div>
                      </div>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect">Save</button>
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('scripts')
<script>

    function upload(id)
    {
        $('#default-upload').modal('show');
    }

    function downloads()
    {
        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });
        $.ajax({
            type: 'get',
            url: "{{ url('reporttgadate')}}",
            success: function (data) {
                if (data.success == 1) {
                    swal(
                        'Export TGA File success',
                        '',
                        'success'
                    );
                    window.location.href = "../local/storage/NHK-File/TGA/"+data.filename;
                } else {
                    swal("warning", "The TGA report cannot be generated because the data status is incorrect", "warning");
                }
            }
        });
    }

    $('#excelUploadForm').on('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        $('#default-upload').modal('hide'); // ปิด modal
        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });

        $.ajax({
            url: "{{ route('upload_tga') }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                
                // var data = response;
                // if(data.success == "1"){
                //     swal({
                //         title: 'Upload TGA File success',
                //         text: '',
                //         type: 'success'
                //     }, function() { 
                //         setTimeout(function() {
                //             location.reload(); 
                //         }, 100);
                //     });
                // }else{
                //     swal("warning", "There is a problem with the TGA file, so the data cannot be imported.", "warning");
                // } 

            },
            error: function (xhr) {
                swal.close()
                swal("warning", "There is a problem with the TGA file, so the data cannot be imported.", "warning");
            }
        });
    });
</script>
@endsection
