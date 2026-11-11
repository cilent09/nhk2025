
@extends('layouts.main')
@section('title', 'SCC')
@section('breadcrumb-title', 'SCC')


@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">
                    <div class="row">
                        <div class="col-sm-12 col-xl-4">
                            <label>Start date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-4">
                            <label>End date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="col-sm-12 col-xl-2">
                        <label>Days back</label>
                        <select  class="form-control" name="numbercount" id="numbercount">
                            <option value="0">Null</option>
                            <option value="90">90</option>
                            <option value="120">120</option>
                            <option value="150">150</option>
                            <option value="180">180</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-xl-2">
                            <label>&nbsp;</label><br>
                            {{-- <input type="submit" name="submit" id="submit" class="btn btn-success" value="Search"> --}}
                            <button id="chksearch" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
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
                    <div class="card-block">
                        <form class="custom-validation" method="post" id="my_form" action="../output/raw_exportpdf" target="_blank">
                            {{ csrf_field() }}
                            <div class="dt-responsive table-responsive">
                                <div style="text-align: right">
                                    <a onclick="viewoverlay()"><button type="button"
                                        class="btn btn-warning btn-sm">&nbsp;&nbsp;<i
                                            class="fa fa-area-chart"></i> SCC REPORT&nbsp;&nbsp;</button></a>
                                </div>
                                <br>
                                <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center" width="150"><input name="select_all"
                                                    value="1" id="selectAllAgents" type="checkbox"/></th>
                                            <th rowspan="2" class="text-center">Model</th>
                                            <th rowspan="2" class="text-center">Part UP/DN</th>
                                            <th rowspan="2" class="text-center">Part Number</th>
                                            <th rowspan="2" class="text-center">First date</th>
                                            <th rowspan="2" class="text-center">Last date</th>
                                            <th class="text-center">Count data</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">LPC</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                    </div>  
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
