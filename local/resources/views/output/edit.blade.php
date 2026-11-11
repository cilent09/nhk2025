@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Edit Results')
@section('breadcrumb-title', 'Output section')
@section('breadcrumb-sub', 'Edit Results')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="120">LPC</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Part</th>
                                <th class="text-center">Lot</th>
                                <th class="text-center">Spec.</th>
                                <th class="text-center">UCL</th>
                                <th class="text-center">Raw</th>
                                <th class="text-center">Edit Data</th>
                                <th class="text-center">Submit Edit Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>ALPC</td>
                                <td>Pine 2.2.2_TNHK</td>
                                <td>UP</td>
                                <td>T4902R000</td>
                                <td>12000</td>
                                <td>4847</td>
                                <td>5010</td>
                                <td>3500</td>
                                <td>
                                    <button class="btn btn-primary" onclick="editData();"><i class="fa fa-save"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="dt-responsive table-responsive">
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="120">SEM</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Part</th>
                                <th class="text-center">Lot</th>
                                <th class="text-center">Spec.</th>
                                <th class="text-center">UCL</th>
                                <th class="text-center">Raw</th>
                                <th class="text-center">Edit Data</th>
                                <th class="text-center">Submit Edit Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>Class 1</td>
                                <td rowspan="10">Pine 2.2.2_TNHK</td>
                                <td rowspan="10">UP</td>
                                <td>T4902R000</td>
                                <td>120</td>
                                <td>5.0</td>
                                <td>1.3</td>
                                <td>1.3</td>
                                <td rowspan="10">
                                    <button class="btn btn-primary" onclick="editData();"><i class="fa fa-save"></i></button>
                                </td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 2</td>
                                <td>T4902R000</td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>0.0</td>
                                <td>0.0</td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 3</td>
                                <td>T4902R000</td>
                                <td>1500</td>
                                <td>283.4</td>
                                <td>92.6</td>
                                <td>92.6</td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 3.1</td>
                                <td>T4902R000</td>
                                <td>1300</td>
                                <td>245.4</td>
                                <td>67.2</td>
                                <td>67.2</td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 3.2</td>
                                <td>T4902R000</td>
                                <td>75</td>
                                <td>52.5</td>
                                <td>0.0</td>
                                <td>0.0</td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 3.3</td>
                                <td>T4902R000</td>
                                <td>200</td>
                                <td>37.6</td>
                                <td>22.8</td>
                                <td>22.8</td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 4</td>
                                <td>T4902R000</td>
                                <td>100</td>
                                <td>57.3</td>
                                <td>70.0</td>
                                <td>11.4</td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 5</td>
                                <td>T4902R000</td>
                                <td>100</td>
                                <td>16.4</td>
                                <td>6.3</td>
                                <td>6.3</td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 5.1</td>
                                <td>T4902R000</td>
                                <td>50</td>
                                <td>5.0</td>
                                <td>2.5</td>
                                <td>2.5</td>
                            </tr>
                            <tr class="text-center">
                                <td>Class 6</td>
                                <td>T4902R000</td>
                                <td>TBD</td>
                                <td>TBD</td>
                                <td>7.6</td>
                                <td>7.6</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="dt-responsive table-responsive">
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="120">IC</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Part</th>
                                <th class="text-center">Lot</th>
                                <th class="text-center">Spec.</th>
                                <th class="text-center">UCL</th>
                                <th class="text-center">Raw</th>
                                <th class="text-center">Edit Data</th>
                                <th class="text-center">Submit Edit Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>Anion</td>
                                <td rowspan="3">Pine 2.2.2_TNHK</td>
                                <td rowspan="3">UP</td>
                                <td rowspan="3">T4902R000</td>
                                <td>0.14</td>
                                <td>0.098</td>
                                <td>0.0012</td>
                                <td>0.0012</td>
                                <td rowspan="3"><button class="btn btn-primary" onclick="editData();"><i class="fa fa-save"></i></button></td>
                            </tr>
                            <tr class="text-center">
                                <td>Sulphate</td>
                                <td>0.5</td>
                                <td>0.35</td>
                                <td>0.0002</td>
                                <td>0.0002</td>
                            </tr>
                            <tr class="text-center">
                                <td>Cation</td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>0.0000</td>
                                <td>0.0000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="dt-responsive table-responsive">
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="120">GCMS</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Part</th>
                                <th class="text-center">Lot</th>
                                <th class="text-center">Spec.</th>
                                <th class="text-center">UCL</th>
                                <th class="text-center">Raw</th>
                                <th class="text-center">Edit Data</th>
                                <th class="text-center">Submit Edit Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>Outgassing</td>
                                <td rowspan="4">Pine 2.2.2_TNHK</td>
                                <td rowspan="4">UP</td>
                                <td rowspan="4">T4902R000</td>
                                <td>500</td>
                                <td>159</td>
                                <td>97</td>
                                <td>97</td>
                                <td rowspan="3"><button class="btn btn-primary" onclick="editData();"><i class="fa fa-save"></i></button></td>
                            </tr>
                            <tr class="text-center">
                                <td>Alkylacrylate</td>
                                <td>N/A</td>
                                <td>N/A</td>
                                <td>0</td>
                                <td>0</td>
                            </tr>
                            <tr class="text-center">
                                <td>Low MW</td>
                                <td>TBD</td>
                                <td>125</td>
                                <td>79</td>
                                <td>79</td>
                            </tr>
                            <tr class="text-center">
                                <td>High MW</td>
                                <td>TBD</td>
                                <td>26</td>
                                <td>30</td>
                                <td>10</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="dt-responsive table-responsive">
                    <table id="dt" class="table table-bordered nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th class="text-center" width="120">LPC</th>
                                <th class="text-center">Model</th>
                                <th class="text-center">Part</th>
                                <th class="text-center">Lot</th>
                                <th class="text-center">Spec.</th>
                                <th class="text-center">UCL</th>
                                <th class="text-center">Raw</th>
                                <th class="text-center">Edit Data</th>
                                <th class="text-center">Submit Edit Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-center">
                                <td>FTIR</td>
                                <td>Pine 2.2.2_TNHK</td>
                                <td>UP</td>
                                <td>T4902R000</td>
                                <td>20</td>
                                <td>14</td>
                                <td>0</td>
                                <td>0</td>
                                <td>
                                    <button class="btn btn-primary" onclick="editData();"><i class="fa fa-save"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <a href="{{ url('output/results') }}" class="btn btn-warning pull-right">Back</a>
    </div>
</div>
@endsection

@section('scripts')
<script>

    $(function () {
        
    });

    function editData() {
        swal({
            title: "Are you sure?",
            text: "Submit Edit Data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Confirm",
            closeOnConfirm: false
        },
        function(){
            swal("Successed", "Submit edit data successed", "success");
            setTimeout(function( ) {
                window.location.href = "{{ url('output/results') }}";
            }, 1000);
        });
    }

</script>
@endsection