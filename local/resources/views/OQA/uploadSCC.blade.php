@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Upload raw file (Custom)')
@section('breadcrumb-title-href', '../incoming')
@section('breadcrumb-title', 'Incoming LAB')
@section('breadcrumb-sub', 'Upload raw file (Custom)')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Upload raw file (Custom) </h5>
    </div>
    <div class="card-block">
        <form class="custom-validation"  method="post" id="my_form" >
            {{ csrf_field() }}
    <div class="sub-title">lot Number : {{$oqalab->lotnumber_lab}} </div>
        <input type="hidden" id="lot_Number"  value="{{$oqalab->lotnumber_lab}}">
        <p style="color: red"> * Maximum 9 MB (9000 KB)</p>
        <input type="hidden" value="{{$oqalab->id_createlab}}" name="idfile" id="idfile" >
        <input type="file" id="customFile" name="customFile" >
    </div>
    <div  class="card-block">
        <a href="{{ url('OQA/oqafilewd') }}/{{$oqalab->id_createlab}}">
            <button type="button" class="btn btn-secondary waves-effect">
                Cancel
            </button>
        </a>
        <button type="button" id="submitupload" class="btn btn-primary waves-effect waves-light mr-1">
            Submit
        </button>
       
    </div>
     </div>
   </form>
</div>

@endsection

@section('scripts')
<script>
 
$('#submitupload').click(function () {
    $(".sweet-alert").css('background-color', 'white');
    $(".sweet-alert h2").css('color', '#575757');
    $(".sweet-alert p").css('color', '#797979');
    var lot_Number = $('#lot_Number').val();
    fake_path=document.getElementById('customFile').value
    name = fake_path.split("\\").pop()
    var res = name.search(''+lot_Number+'');
    if(res == -1)
    {
        swal("Wrong Lot number", "Please check your file !", "warning");
        $(".sweet-alert").css('background-color', 'red');
        $(".sweet-alert h2").css('color', '#ffffff');
        $(".sweet-alert p").css('color', '#ffffff');
        return false;
    }
    swal({
            title: "Are you sure?",
            text: "Confirm Submit Incoming LAB!",
            background: 'red',
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {

            var forms = $('#my_form')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('uplaodSCCSubmit') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                            window.location.href =  urls;
                        }, 1000);
                    } else {
                        swal("warning", "An error occurred. Please check the uploaded file", "warning");
                    }
                }
            });
        });
});

</script>
@endsection
