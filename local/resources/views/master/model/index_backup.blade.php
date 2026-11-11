@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Model')
@section('breadcrumb-title', 'Model')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <a href="../master/addmodel"><button class="btn btn-primary"><i class="fa fa-plus"></i> Add model</button></a>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                        <thead>
                        <tr>
                            <th class="text-center" width="150">#</th>
                            <th class="text-center">Model</th>
                            <th class="text-center">Part UP/DN</th>
                            <th class="text-center">Drive Name</th>
                            <th class="text-center">Line Type</th>
                            <th class="text-center">VENDOR ID</th>
                        </tr>
                        </thead>
                    </table>
                </div>
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
                url: "{{url('master/datatables')}}",
            },
            columns: [
                {'className': "text-center", data: 'id_model', name: 'id_model'},
                {data: 'name_model', name: 'name_model'},
                {data: 'part', name: 'part'},
                {data: 'drive_name', name: 'drive_name'},
                {data: 'line_type', name: 'line_type'},
                {data: 'vendor_id', name: 'vendor_id'},
                
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
            }
        });


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
        // Swal.fire({
        //     title: 'Are you sure?',
        //     text: ' You wont be able to revert this!',
        //     icon: 'error',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes, delete it!'

            
        // }).then((result) => {
        //     if (result.value) {
        //         $.ajax({
        //             'dataType': 'json',
        //             'type': 'post',
        //             'url': "{{url('deletemodelSubmit')}}",
        //             'data': {
        //                 'id': id,
        //                 '_token': "{{ csrf_token() }}"
        //             },
        //             success: function (data) {
        //                 if (data.success == 1) {
        //                     Swal.fire(
        //                         'Deleted!',
        //                         'Your file has been deleted.',
        //                         'success'
        //                     )
        //                     setTimeout(function () {
        //                         window.location.href =
        //                             "{{ url('master/model') }}";
        //                     }, 1000);
        //                 } else {

        //                 }
        //             }
        //         });

        //     }
        // })
    }

</script>

@endsection
