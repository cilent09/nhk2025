@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'View Control limit')
@section('breadcrumb-title', 'View Control limit')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
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
                            <!-- <th class="text-center">Line Type</th>
                            <th class="text-center">VENDOR ID</th> -->
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
                url: "{{url('master/controllimittable')}}",
                data :
                {
                 id: '{{$id_chistory}}'
                }
                
            },
            columns: [
                {'className': "text-center", data: 'name_model', name: 'name_model'},
                {data: 'name_model', name: 'name_model'},
                {data: 'date_start', name: 'date_start'},
                {data: 'date_end', name: 'date_end'},
            ],
            "order": [[ 2, "desc" ]],
            rowCallback: function (row, data, index) {
                var button = '';
                button += '<a href="../viewmodeldata/' + data.sub_number +'/'+data.id_model+'"><button class="btn btn-info btn-sm" id="searchModel" title="View" ><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
              
                $('td:eq(0)', row).html(button);

                $.ajax({
                    url: "{{ url('getmodelControllimit') }}",
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        "id": data.id_model,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function(data) {

                       $('td:eq(2)', row).html(data.model.part);
                       $('td:eq(3)', row).html(data.model.drive_name);

                    }
                });
                 
            }
        });


    });

   

</script>

@endsection
