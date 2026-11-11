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
                    <form  method="post"  id="fm-editsem" >
                    {{ csrf_field() }}
                        <table id="dt" class="table table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="120">SEM</th>
                                    <th class="text-center">Model</th>
                                    <th class="text-center">Part</th>
                                    <th class="text-center">Lot</th>
                                    <th class="text-center">Spec.</th>
                                    <th class="text-center">UCL</th>
                                    <th class="text-center">Raw data</th>
                                    <th class="text-center">Input Edit Data</th>
                                    <th class="text-center">Last Edit Data</th>
                                    <th class="text-center">Submit Edit Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td>Class 1</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class1}} </td>
                                    <td>{{$ucl->class1}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class1,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class1))
                                            <input  class="form-control" type="text" name="sem_class1" id="sem_class1" value="{{ number_format($data_sem->last_sem_class1,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class1" id="sem_class1" value="{{ number_format($data_sem->sem_class1,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class1))
                                    {
                                        $showdata =  $data_sem->last_sem_class1;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                    <td rowspan="10">
                                        <input type="hidden" name="id_oqa" value="{{$oqa_lab->id_createlab}}">
                                        <button type="button" onclick="editsem()" class="btn btn-primary"  ><i class="fa fa-save"></i></button>
                                        {{-- <button class="btn btn-primary" onclick="editsem();"><i class="fa fa-save"></i></button> --}}
                                    </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 2</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class2}} </td>
                                    <td>{{$ucl->class2}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class2,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class2))
                                            <input  class="form-control" type="text" name="sem_class2" id="sem_class2" value="{{ number_format($data_sem->last_sem_class2,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class2" id="sem_class2" value="{{ number_format($data_sem->sem_class2,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class2))
                                    {
                                        $showdata =  $data_sem->last_sem_class2;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 3</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class3}} </td>
                                    <td>{{$ucl->class3}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class3,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class3))
                                            <input  class="form-control" type="text" name="sem_class3" id="sem_class3" value="{{ number_format($data_sem->last_sem_class3,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class3" id="sem_class3" value="{{ number_format($data_sem->sem_class3,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class3))
                                    {
                                        $showdata =  $data_sem->last_sem_class3;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 3.1</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class3_1}} </td>
                                    <td>{{$ucl->class3_1}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class3_1,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class3_1))
                                            <input  class="form-control" type="text" name="sem_class3_1" id="sem_class3_1" value="{{ number_format($data_sem->last_sem_class3_1,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class3_1" id="sem_class3_1" value="{{ number_format($data_sem->sem_class3_1,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class3_1))
                                    {
                                        $showdata =  $data_sem->last_sem_class3_1;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 3.2</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class3_2}} </td>
                                    <td>{{$ucl->class3_2}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class3_2,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class3_2))
                                            <input  class="form-control" type="text" name="sem_class3_2" id="sem_class3_2" value="{{ number_format($data_sem->last_sem_class3_2,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class3_2" id="sem_class3_2" value="{{ number_format($data_sem->sem_class3_2,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class3_2))
                                    {
                                        $showdata =  $data_sem->last_sem_class3_2;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 3.3</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class3_3}} </td>
                                    <td>{{$ucl->class3_3}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class3_3,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class3_3))
                                            <input  class="form-control" type="text" name="sem_class3_3" id="sem_class3_3" value="{{ number_format($data_sem->last_sem_class3_3,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class3_3" id="sem_class3_3" value="{{ number_format($data_sem->sem_class3_3,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class3_3))
                                    {
                                        $showdata =  $data_sem->last_sem_class3_3;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 4</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class4}} </td>
                                    <td>{{$ucl->class4}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class4,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class4))
                                            <input  class="form-control" type="text" name="sem_class4" id="sem_class4" value="{{ number_format($data_sem->last_sem_class4,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class4" id="sem_class4" value="{{ number_format($data_sem->sem_class4,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class4))
                                    {
                                        $showdata =  $data_sem->last_sem_class4;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 5</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class5}} </td>
                                    <td>{{$ucl->class5}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class5,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class5))
                                            <input  class="form-control" type="text" name="sem_class5" id="sem_class5" value="{{ number_format($data_sem->last_sem_class5,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class5" id="sem_class5" value="{{ number_format($data_sem->sem_class5,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class5))
                                    {
                                        $showdata =  $data_sem->last_sem_class5;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 5.1</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class5_1}} </td>
                                    <td>{{$ucl->class5_1}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class5_1,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class5_1))
                                            <input  class="form-control" type="text" name="sem_class5_1" id="sem_class5_1" value="{{ number_format($data_sem->last_sem_class5_1,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class5_1" id="sem_class5_1" value="{{ number_format($data_sem->sem_class5_1,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class5_1))
                                    {
                                        $showdata =  $data_sem->last_sem_class5_1;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Class 6</td>
                                    <td>{{$oqa_lab->name_model}}</td>
                                    <td>{{$oqa_lab->part}}</td>
                                    <td>{{$oqa_lab->lotnumber_lab}} </td>
                                    <td>{{$spec->class6}} </td>
                                    <td>{{$ucl->class6}} </td>
                                    <td>{{ number_format($oqa_labfc->sem_class6,1) }} </td>
                                    <td>
                                        @if(!empty($data_sem->last_sem_class6))
                                            <input  class="form-control" type="text" name="sem_class6" id="sem_class6" value="{{ number_format($data_sem->last_sem_class6,1) }}">
                                        @else
                                            <input  class="form-control" type="text" name="sem_class6" id="sem_class6" value="{{ number_format($data_sem->sem_class6,1) }}">
                                        @endif
                                    </td>
                                    @php
                                    if(!empty($data_sem->last_sem_class6))
                                    {
                                        $showdata =  $data_sem->last_sem_class6;
                                    }
                                    else
                                    {
                                        $showdata =  '0.0';
                                    }
                                @endphp
                                    <td>{{$showdata}} </td>
                                </tr>
                            </tbody> 
                        </table>

                        <table id="dt" class="table table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">SEM Class</th>
                                    <th class="text-center">SEM Element</th>
                                    <th class="text-center" style="width: 141px;">Part</th>
                                    <th class="text-center" style="width: 141px;">Lot</th>
                                    <th class="text-center" style="width: 101px;">Spec.</th>
                                    <th class="text-center" style="width: 83px;">UCL</th>
                                    <th class="text-center">Raw data</th>
                                    <th class="text-center">Input Edit Data</th>
                                    <th class="text-center" style="width: 170px;">Last Edit Data</th>
                                    <th class="text-center" style="color: white; width: 199px;">Submit Edit Data</th>
                                </tr>
                            </thead>
                            <tbody>  
                                @php
                                    $row = 0;
                                    $rowline = [];
                                    foreach ($classname_element as $key => $classname)
                                    {
                                        if (strlen($classname) < 2) {
                                            $row = $row;
                                        }else{
                                            $row++;
                                        }
                                        $rowline[$key] = $row;
                                    }

                                    $temp1 = 0; $temp2 = 0; $temp3 = 0; $temp4 = 0; $temp5 = 0; $temp6 = 0;
                                    foreach($rowline as $key => $value) {
                                        if($value == 1){
                                            $temp1++;
                                        }elseif ($value == 2) {
                                            $temp2++;
                                        }elseif ($value == 3) {
                                            $temp3++;
                                        }elseif ($value == 4) {
                                            $temp4++;
                                        }elseif ($value == 5) {
                                            $temp5++;
                                        }elseif ($value == 6) {
                                            $temp6++;
                                        }
                                    }
                                    $rowscip = []; 
                                    $rowscip[1] = $temp1;
                                    $rowscip[2] = $temp2;
                                    $rowscip[3] = $temp3;
                                    $rowscip[4] = $temp4;
                                    $rowscip[5] = $temp5;
                                    $rowscip[6] = $temp6;
                                @endphp
                                @foreach ($classname_element as $key => $classname) 
                                    <tr>    
                                        @php
                                            $pieces = explode(" ", $classname);
                                        @endphp
                                        @if (strlen($classname) > 2)
                                        <td rowspan="{{ $rowscip[ $pieces[1] ] }}" class="text-center" style="vertical-align: middle;">{{ $classname }}</td>
                                        @endif
                                        <td class="text-left" style="padding-left: 20px;">{{ $columnname_element[$key] }}</td>
                                        <td class="text-center">{{$oqa_lab->part}}</td>
                                        <td class="text-center">{{$oqa_lab->lotnumber_lab}} </td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">
                                            {{ number_format($value_element[$key],1) }}
                                        </td>
                                        <td class="text-center">
                                            @if($value_last_element[$key] !== null)
                                                <input class="form-control" type="number" min="0" step="0.1" id="I{{ $key }}" name="I{{ $key }}" value="{{ number_format($value_last_element[$key],1) }}">
                                            @else
                                                <input class="form-control" type="number" min="0" step="0.1" id="I{{ $key }}" name="I{{ $key }}" value="{{ number_format($value_element[$key],1) }}">
                                            @endif
                                        </td>
                                        <td class="text-center">{{ number_format($value_last_element[$key],1) }}</td>
                                    </tr>
                                   
                                @endforeach
                            </tbody> 
                        </table> 
                    </form>
                </div>

            </div>
        </div>
        {{-- <a href="{{ url('output/results') }}" class="btn btn-warning pull-right">Back</a> --}}
    </div>
</div>
@endsection

@section('scripts')
<script>
    function editsem()
    {
        swal({
                html: true,
                title: "Are you sure?",
                text: "Submit Edit Data! ",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                var forms = $('#fm-editsem')[0];
                var data = new FormData(forms);
                $.ajax({
                    type: 'POST',
                    url: "{{ url('editdatasemsave') }}",
                    data: data,
                    processData: false,
                    contentType: false,
                    cache: false,
                    success: function (data) {
                        if (data.success == 1) {
                                swal("Success", "Submit edit data success", "success");
                                setTimeout(function () {
                                    window.location.href =
                                    "{{ url('output/results') }}";
                                }, 1000);
                            } else {

                            }
                         }
                    });
                 });
    }
    
     

</script>
@endsection