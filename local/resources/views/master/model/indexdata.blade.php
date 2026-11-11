@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Control limit')
@section('breadcrumb-title', 'Control limit')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                {{-- <a href="../master/addmodel"><button class="btn btn-primary"><i class="fa fa-plus"></i> Add model</button></a> --}}
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
                button += '<a href="indexhistory/' + data.id_model + '"><button class="btn btn-info btn-sm" id="searchModel" title="View" ><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                $('td:eq(0)', row).html(button);
            }
        });


    });


</script>

@endsection
