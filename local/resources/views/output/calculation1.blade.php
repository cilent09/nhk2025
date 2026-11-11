@extends('layouts.main')
@section('title', 'Calculation')
@section('breadcrumb-title', 'Output section')
@section('breadcrumb-sub', 'Calculation')

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
                                <th class="text-center">Model</th>
                                <th class="text-center">Part</th>
                                <th class="text-center">Part number</th>
                                <th class="text-center">Parameter</th>
                                <th class="text-center">Count Back Data date</th>
                                <th class="text-center">Data Qty</th>
                                <th class="text-center">n Actual</th>
                                <th class="text-center">Mean</th>
                                <th class="text-center">Max</th>
                                <th class="text-center">Min</th>
                                <th class="text-center">STD</th>
                                <th class="text-center">Mean+3Q</th>
                                <th class="text-center">Mean+4Q</th>
                                <th class="text-center">Mean+4.5Q</th>
                                <th class="text-center">Mean+6Q</th>
                                <th class="text-center">Export data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">Iris 20.5.2</td>
                                <td class="text-center">UP</td>
                                <td class="text-center">726610706</td>
                                <td class="text-center">LPC</td>
                                <td class="text-center">30-06-19</td>
                                <td class="text-center">50</td>
                                <td class="text-center">50</td>
                                <td class="text-center">3593</td>
                                <td class="text-center">4162</td>
                                <td class="text-center">2295</td>
                                <td class="text-center">167</td>
                                <td class="text-center">4094</td>
                                <td class="text-center">4261</td>
                                <td class="text-center">4344.5</td>
                                <td class="text-center">4595</td>
                                <td class="text-center">
                                    <button class="btn btn-primary export" onclick="exportData();"><i class="fa fa-external-link"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">Maple 17.1</td>
                                <td class="text-center">DN</td>
                                <td class="text-center">725441701</td>
                                <td class="text-center">LPC</td>
                                <td class="text-center">30-06-19</td>
                                <td class="text-center">50</td>
                                <td class="text-center">45</td>
                                <td class="text-center">3593</td>
                                <td class="text-center">4162</td>
                                <td class="text-center">2295</td>
                                <td class="text-center">167</td>
                                <td class="text-center">4094</td>
                                <td class="text-center">4261</td>
                                <td class="text-center">4344.5</td>
                                <td class="text-center">4595</td>
                                <td class="text-center">
                                    <button class="btn btn-primary export" onclick="exportData();"><i class="fa fa-external-link"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center">Iris 11.3.4</td>
                                <td class="text-center">UP</td>
                                <td class="text-center">718332200</td>
                                <td class="text-center">DHS</td>
                                <td class="text-center">30-06-19</td>
                                <td class="text-center">15</td>
                                <td class="text-center">13</td>
                                <td class="text-center">3593</td>
                                <td class="text-center">4162</td>
                                <td class="text-center">2295</td>
                                <td class="text-center">167</td>
                                <td class="text-center">4094</td>
                                <td class="text-center">4261</td>
                                <td class="text-center">4344.5</td>
                                <td class="text-center">4595</td>
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
                    targets: [15],
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