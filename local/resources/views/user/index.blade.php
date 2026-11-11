@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'User')
@section('breadcrumb-title', 'User')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <a href="user/adduser"><button class="btn btn-primary"><i class="fa fa-plus"></i> Add User</button></a>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                        <thead>
                        <tr>
                            <th class="text-center" width="150">#</th>
                            <th class="text-center">name</th>
                            <th class="text-center">email</th>
                            <th class="text-center">phone</th>
                            <th class="text-center">status</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
    $(document).ready(function() {
        var oTable = $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            lengthChange: true,
            ajax: {
                url: "{{url('user/datatables')}}",
            },
            columns: [
                {'className': "text-center", data: 'id', name: 'id'},
                {data: 'name', name: 'name'},
                {data: 'email', name: 'email'},
                {data: 'phone', name: 'phone'},
                {data: 'status', name: 'status'},
                
            ],
            "order": [[ 0, "desc" ]],
            rowCallback: function (row, data, index) {
                var button = '';
                button += '<a href="{{asset("user/viewuser/")}}/' + data.id + '" target="_blank"><button class="btn btn-info btn-sm" id="searchModel" title="View" ><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                button += '<a href="{{asset("user/edituser/")}}/' + data.id + '"><button class="btn btn-warning btn-sm"  title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                
                button += '<button class="btn btn-danger btn-sm" title="Delete" onclick="deleteuser(' + data.id + ')"><i class="fa fa-trash" style="font-size:18px;"></i></button>&nbsp;';
                $('td:eq(0)', row).html(button);
                var status = '';
                if(data.status == 1)
                {
                status += '<span class="label label-danger">ADMIN</span>';
                }
                else if(data.status == 2)
                {
                status += '<span class="label label-primary">QA</span>';
                }
                else
                {
                    status += '<span class="label label-warning">MFG</span>';
                }
                $('td:eq(4)', row).html(status);
            }
        });


    });

    function deleteuser(id) {
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
                    'type': 'get',
                    'url': "{{url('user/userdelete')}}",
                    'data': {
                        'id': id,
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Deleted!',
                                'Your file has been deleted.',
                                'success');
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('user') }}";
                            }, 1000);
                        } else {

                        }
                    }
                });
            });
    }

</script>

@endsection
