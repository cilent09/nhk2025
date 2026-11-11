@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'View CPP history')
@section('breadcrumb-title', 'Output section')
@section('breadcrumb-sub', 'View CPP history')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="card-block">
                <form id="formsearch">
                    <div class="row">
                        <div class="col-sm-12 col-xl-4">
                            <label>Start date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <label>End date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <label>&nbsp;</label><br>
                            <input type="submit" name="submit" id="submit" class="btn btn-success" value="Search">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table id="dt" class="table table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center">Date</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Lot</th>
                                <th class="text-center">Parameter</th>
                                <th class="text-center">Operator</th>
                                <th class="text-center">Export data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">01-06-19</td>
                                <td class="text-center">Maple 17.1</td>
                                <td class="text-center">T4195C507</td>
                                <td class="text-center">LPC</td>
                                <td class="text-center">Piyachat</td>
                                <td class="text-center">
                                    <button class="btn btn-primary export" onclick="exportData();"><i class="fa fa-external-link"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">15-06-19</td>
                                <td class="text-center">Iris 11.3.4</td>
                                <td class="text-center">T2093S054</td>
                                <td class="text-center">DHS</td>
                                <td class="text-center">Sarocha</td>
                                <td class="text-center">
                                    <button class="btn btn-primary export" onclick="exportData();"><i class="fa fa-external-link"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>

    $(function () {
        $("#dt").DataTable({
            columnDefs: [
                { 
                    targets: [5],
                    orderable: false
                }
            ],
        });
    });

    function exportData() {
        swal({
            title: "Are you sure?",
            text: "Export Data",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Confirm",
            closeOnConfirm: false
        },
        function(){
            swal("Successed", "Export data successed", "success");
        });
    }

</script>
@endsection