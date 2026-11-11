@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Cut off Control limit')
@section('breadcrumb-title', 'Cut off Control limit')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            <a href="../../master/addmodeldata/{{$modelid}}"><button class="btn btn-primary"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cut off&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br><span style="font-size: 10px"> Control limit </span></button></a>
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                        <thead>
                        <tr>
                            <th class="text-center" width="150">#</th>
                            <th class="text-center">Model</th>
                            <th class="text-center">Date_Start</th>
                            <th class="text-center">Date End</th>
                            <th class="text-center">Details</th>
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
                url: "{{url('master/historydatatable')}}",
                data :
                {
                 modelid: '{{$modelid}}'
                }
                
            },
            columns: [
                {'className': "text-center", data: 'name_model', name: 'name_model'},
                {data: 'name_model', name: 'name_model'},
                {data: 'date_start', name: 'date_start'},
                {data: 'date_end', name: 'date_end'},
                {data: 'note', name: 'note'},
                
            ],
            "order": [[ 2, "desc" ]],
            rowCallback: function (row, data, index) {
                var button = '';
                button += '<a href="../viewmodeldata/' + data.sub_number +'/'+data.id_model+'"><button class="btn btn-info btn-sm" id="searchModel" title="View" ><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                button += '<a href="../editmodeldata/'+ data.sub_number+'/'+data.id_model+'"><button class="btn btn-warning btn-sm"  title="Edit"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                if(userstatus == 1)
                     {
                button += '<button class="btn btn-danger btn-sm" onclick="deletemodelht('+data.sub_number+','+ data.id_model + ')" title="Delete"><i class="fa fa-trash" style="font-size:18px;"></i></button>&nbsp;';
                     }
                $('td:eq(0)', row).html(button);
                $('td:eq(2)', row).html(Getdate(data.date_start));
                if(data.date_end =='2060-12-31')
                {
                    $('td:eq(3)', row).html('ปัจจุบัน');
                }
                else
                {
                $('td:eq(3)', row).html(Getdate(data.date_end));
                }
            }
        });


    });

    function deletemodelht(subnumber,modelid) {
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
                    'url': "{{url('deletemodelhtSubmit')}}",
                    'data': {
                        'subnumber': subnumber,
                        'modelid': modelid,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function (data) {
                        if (data.success == 1) {
                            swal('Deleted!',
                                'Your file has been deleted.',
                                'success');
                            setTimeout(function () {
                                window.location.href =
                                    "{{ url('master/indexhistory') }}/"+modelid;
                            }, 1000);
                        } else {

                        }
                    }
                });
            });
    }

</script>

@endsection
