@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'View Model data')
@section('breadcrumb-title-href', '../../../master/modeldata')
@section('breadcrumb-title', 'Control limit')
@section('breadcrumb-sub', 'View Model data')

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div style="text-align: center" class="card-header">
                    <h5 >VIEW DATA</h5>
                    {{--                <span>Add class of <code>.form-control</code> with <code>&lt;input&gt;</code> tag</span>--}}
                </div>
                <div class="card-block p-0">
                    <!-- Nav tabs -->
                   
                     <div style="margin-left: 15px; margin-right: 15px" >   
                    <fieldset>
                        <div class="row">
                            <div class="col-sm-2">
                            </div>
                            <div style="text-align: center" class="col-sm-8">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label> Start Date : {{$model_spec[0]->date_start}}</label>
                                    
                                    </div>
                                    <div class="col-sm-6">
                                        @if ($model_spec[0]->chkstatus == 1)
                                        End Date : ปัจจุบัน
                                        @else
                                        <label> End Date : {{$model_spec[0]->date_end}}</label>
                                        @endif
                                        
                                   
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
        
                            </div>
                        </div>
                        <br> 
                        <br> 
                        <div class="dt-responsive table-responsive">
                            <table id="form-input-table"
                                   class="table table-striped table-bordered nowrap">

                                <thead>
                                <tr>
                                    <th  rowspan="2">Name</th>
                                    <th bgcolor="#FFFF99" colspan="1" class="text-center">LPC</th>
                                    <th  bgcolor="#00FFFF" colspan="10" class="text-center">SEM</th>
                                    <th bgcolor="#00FF7F" colspan="3" class="text-center">IC</th>
                                    <th bgcolor="#FFB6C1"  colspan="4" class="text-center" >GCMS</th>
                                    <th bgcolor="#EE82EE" colspan="1" class="text-center">FTIR</th>
                                    <th  rowspan="2">Name</th>
                                </tr>

                                <tr>
                                    <th bgcolor="#FFFF99">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class1&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class2&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class3&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&ensp;Class3.1&emsp;&ensp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&ensp;Class3.2&emsp;&ensp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&ensp;Class3.3&emsp;&ensp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class4&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class5&emsp;&emsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&ensp;Class5.1&emsp;&ensp;&nbsp;</th>
                                    <th bgcolor="#00FFFF">&emsp;&emsp;Class6&emsp;&emsp;</th>
                                    <th bgcolor="#00FF7F">&emsp;&emsp;Anion&emsp;&emsp;</th>
                                    <th bgcolor="#00FF7F">&emsp;&ensp;Sulphate&emsp;&nbsp;</th>
                                    <th bgcolor="#00FF7F">&emsp;&emsp;Cation&emsp;&emsp;</th>
                                    <th bgcolor="#FFB6C1">&emsp;&nbsp;Outgassing&emsp;&nbsp;</th>
                                    <th bgcolor="#FFB6C1">&emsp;Smear&emsp;&nbsp;</th>
                                    <th bgcolor="#FFB6C1">&emsp;&nbsp;Low MW&emsp;&nbsp;</th>
                                    <th bgcolor="#FFB6C1">&emsp;&nbsp;High MW&emsp;&nbsp;</th>
                                    <th bgcolor="#EE82EE">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                </tr>

                                </thead>

                                <tbody>

                                @foreach($model_spec as $item )
                                    <tr>
                                        <td>{{$item->name}} :</td>
                                        <td bgcolor="#FFFF99">
                                            {{$item->alpc}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            {{$item->class1}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            {{$item->class2}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            {{$item->class3}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                           {{$item->class3_1}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                           {{$item->class3_2}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            {{$item->class3_3}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            {{$item->class4}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            {{$item->class5}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            {{$item->class5_1}}
                                        </td>
                                        <td bgcolor="#00FFFF">
                                            {{$item->class6}}
                                        </td>
                                        <td bgcolor="#00FF7F">
                                            {{$item->anion}}
                                        </td>
                                        <td bgcolor="#00FF7F">
                                            {{$item->sulphate}}
                                        </td>
                                        <td bgcolor="#00FF7F">
                                            {{$item->cation}}
                                        </td>
                                        <td bgcolor="#FFB6C1">
                                            {{$item->outgassing}}
                                        </td>
                                        <td bgcolor="#FFB6C1">
                                            {{$item->alkylacrylate}}
                                        </td>
                                        <td bgcolor="#FFB6C1">
                                            {{$item->low_MW}}
                                        </td>
                                        <td bgcolor="#FFB6C1">
                                            {{$item->high_MW}}
                                        </td>
                                        <td bgcolor="#EE82EE">
                                            {{$item->ftir}}
                                        </td>
                                        <td>: {{$item->name}}</td>
                                    </tr>
                                @endforeach

                           
                                </tbody>
                            </table>

                            
                        </div>
                    </fieldset>
                </div> 
                <br> 
                <br> 
                <div>
                    <div style="text-align: center" class="col-sm-12">
                        <label for="w3review">Details</label>
                    <textarea class="form-control" id="textnote" name="textnote" rows="4" cols="50" readonly> {{$model_spec[0]->note}}</textarea>
                    </div>
    
                </div>
                <br> 
                <br>  
                <br> 
                <br> 
                </div>
            </div>
        </div>
    </div>
    <!-- tabs card start -->
@endsection

@section('scripts')

    <script>
    </script>

@endsection
