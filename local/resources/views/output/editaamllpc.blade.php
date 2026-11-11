@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Upload SGT AML return file')
@section('breadcrumb-title', 'Output section')
@section('breadcrumb-sub', 'Upload SGT AML return file')

@section('content')
<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <div class="card-block">
                <h5>Model : <b style=" color: red; ">{{$oqa_lab->name_model}}</b></h5> <br> 
                <h5>Lot number : <b style=" color: red; ">{{$oqa_lab->lotnumber_lab}}</b></h5>
            </div>  
        </div>
    </div>
    <div class="col-sm-8">
        <div class="card">
            <div class="card-block">
                <h5>ICON Status :</h5><br>
                <div class="row">
                    <div class="col-sm-6">
                    <img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" style=" width: 100px; ">
                    &nbsp;&nbsp;&nbsp;&nbsp;Download
                    </div>  
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" style=" width: 100px; ">
                            </div>  
                            <div class="col-sm-9 mt-3">
                            <h4>Before Upload</h4>
                            </div>  
                        </div>  
                    </div>  
                </div>  
                <div class="row">
                    <div class="col-sm-6">
                    </div>  
                    <div class="col-sm-6">
                        <div class="row">
                            <div class="col-sm-3">
                                <img src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" style=" width: 100px; ">
                            </div>  
                            <div class="col-sm-9 mt-3">
                            <h4>After Upload</h4>
                            </div>  
                        </div>  
                    </div>  
                </div>  
            </div>  
        </div>
    </div>
</div>
<form method="post" id="fm-editlpc" >
{{ csrf_field() }}

<!-- ช่อง input file ที่ถูกซ่อน -->
<input type="file" id="fileInput" name="fileInput" style="display: none;"  accept=".xls,.xlsx,.xlsm">
<input type="hidden" id="aml_lpc" name="aml_lpc" value="{{ $oqa_lab->aml_lpc }}" >
<input type="hidden" id="id_createlab" name="id_createlab" value="{{ $oqa_lab->id_createlab }}" >
<input type="hidden" id="st_aml" name="st_aml" value="{{ ($aml_file)? '1' : '0' }}" >
<button type="submit" id="btnsave" name="btnsave" style="display: none;">SAVE</button>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <h5>LPC</h5> <br> 
                <div class="row">
                    <div class="col-sm-1"></div>  
                    <div class="col-sm-3">SAMPLE ID (File Name) :</div>  
                    <div class="col-sm-8">{{$oqa_lab->aml_lpc}}</div> 
                </div>  
                <div class="row mt-2">
                    <div class="col-sm-1"></div>  
                    <div class="col-sm-3">SGT AML return file :</div>  
                    <div class="col-sm-8">
                        @if($aml_file)
                            @if(!empty($aml_file->file_lpc))
                                <label id="cha">{{ $aml_file->file_lpc }}</label>
                            @else
                                <label id="cha"> No file chosen </label>
                            @endif
                        @else
                        <label id="cha"> No file chosen </label>
                        @endif
                    </div> 
                </div>  
                <div class="row mt-2">
                    <div class="col-sm-12">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<code>Maximum 9 MB (9000KB)</code></div>  
                </div>
                <div class="row mt-2">
                    @if(!$aml_file)
                    <div class="col-sm-12 text-center">
                        <img id="clickableImage" src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" title="Upload" style=" width: 100px; ">
                    </div> 
                    @else
                    @php
                        $fileaml ='local/storage/NHK-File/OQA/AML/LPC/'.$aml_file->file_lpc; 
                    @endphp
                    <div class="col-sm-12 text-center">
                        @if(!empty($aml_file->file_lpc))
                            <img id="clickableImage" src="{{ asset('template/assets/images/item/btn_upload_green.png')}}" alt="Upload" title="Upload" style=" width: 100px; ">
                        @else
                            <img id="clickableImage" src="{{ asset('template/assets/images/item/btn_upload_orange.png')}}" alt="Upload" title="Upload" style=" width: 100px; ">
                        @endif
                        <a href="{{asset($fileaml)}}"><img src="{{ asset('template/assets/images/item/btn_download.png')}}" alt="Download" title="Download" style=" width: 100px; "></a>
                    </div> 
                    @endif

                </div>
            </div>  
        </div>
    </div>
</div>
</form>
 
@endsection

@section('scripts')
<script>

    // ดักจับเหตุการณ์เมื่อคลิกรูปภาพ
    document.getElementById('clickableImage').addEventListener('click', function() {
        document.getElementById('fileInput').click(); // เปิด popup file input
    });

    // ดักจับเหตุการณ์เมื่อมีการเลือกไฟล์
    document.getElementById('fileInput').addEventListener('change', function(event) {
        // ดึงชื่อไฟล์จาก input
        var fileName = event.target.files[0] ? event.target.files[0].name : 'No file selected';
        // แสดงชื่อไฟล์ใน label
        document.getElementById('cha').textContent = fileName;
        checkfile();
    }); 

</script>
<script>

        function checkfile()
        {
            if ($('#fileInput').get(0).files.length > 0) {
                swal({
                    title: "Are you sure?",
                    text: "Confirm save file !",
                    background: 'red',
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true
                },
                function () {
                    $('#btnsave').click();
                });
            }
        }

        $('#fm-editlpc').submit(function(e) {
            e.preventDefault();
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: '{{ url('uploade_aml_lpc') }}',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if (data.success == 1) {
                        swal("Successed", "save data successed", "success");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        swal("Error", "The file is incorrect.", "error");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    }
                }
            });
        });
 
    </script>
@endsection