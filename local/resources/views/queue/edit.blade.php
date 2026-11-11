@extends('layouts.main')
@section('title', 'Edit Queuing')
@section('breadcrumb-title-href', 'Queuing')
@section('breadcrumb-title', 'Queuing')
@section('breadcrumb-sub', 'Edit Queuing')

@section('content')
<style>
    .mystyle {
      width: 100%;
      padding: 25px;
      background-color: coral;
      color: white;
      font-size: 25px;
      box-sizing: border-box;
    }

    .j-pro input[type="date"] {
        background: #fff;
        border: 2px solid rgba(0, 0, 0, .12);
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        -o-border-radius: 3px;
        border-radius: 3px;
        color: rgba(0, 0, 0, .87);
        display: block;
        font-family: inherit;
        font-size: 16px;
        height: 48px;
        padding: 10px 15px;
        width: 100%;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-transition: all .4s;
        -moz-transition: all.4s;
        -ms-transition: all.4s;
        -o-transition: all.4s;
        transition: all .4s;
    }

    .j-pro input[type="date"]:hover {
        border: 1px solid #4099ff;
    }

    .checkbox {
        transform: scale(2);
    }

    table, th, td {
        border: 1px solid white;
        border-collapse: collapse; 
    } 

    .red {
        color: red;
    }
  

</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <form method="post" id="fm-updatelab" >
                {{ csrf_field() }}

                    <input type="hidden" id="id" name="id" value="{{ $queuing->id }}">

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="title">Job Name <span class="red">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $queuing->title }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="customer">Customer</label>
                            <select id="customer" name="customer" class="form-control">
                                <option value="SGT" {{ ($queuing->customer == "SGT")? 'selected' : '' }}>SGT</option>
                                <option value="WD" {{ ($queuing->customer == "WD")? 'selected' : '' }}>WD</option>
                                <option value="SAE" {{ ($queuing->customer == "SAE")? 'selected' : '' }}>SAE</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="model">Model</label>
                            <input type="text" class="form-control" id="model" name="model" value="{{ $queuing->model }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="requestdate">Request Date <span class="red">*</span></label>
                            <label class="form-label text-danger requestdate_err _err"></label>
                            <input type="date" class="form-control" id="requestdate" name="requestdate" value="{{ $queuing->requestdate }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="sample_send_date">Sample send date <span class="red">*</span></label>
                            <label class="form-label text-danger sample_send_date_err _err"></label>
                            <input type="date" class="form-control" id="sample_send_date" name="sample_send_date" value="{{ $queuing->sample_send_date }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="duedate">Due date <span class="red">*</span></label>
                            <label class="form-label text-danger duedate_err _err"></label>
                            <input type="date" class="form-control" id="duedate" name="duedate" value="{{ $queuing->duedate }}">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="detail">Details (โปรดระบุรายละเอียดการวัดงานอย่างชัดเจนเพื่อง่ายต่อการสั่งงาน) : <span class="red">*</span></label>
                            <textarea class="form-control" id="detail" name="detail" rows="6">{{ $queuing->detail }}</textarea>                          
                        </div>
                    </div>
                    <div class="form-row"> 
                        <div class="form-group col-md-6">
                            <label for="parttype">Part type</label>
                            <select id="parttype" name="parttype" class="form-control">
                                <option value="">--Select--</option>
                                <option value="Up" {{ ($queuing->parttype == "Up")? 'selected' : '' }}>Up</option>
                                <option value="Dn" {{ ($queuing->parttype == "Dn")? 'selected' : '' }}>Dn</option>
								<option value="Up/Dn" {{ ($queuing->parttype == "Up/Dn")? 'selected' : '' }}>Up/Dn</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="qty">Sample Q'ty <span class="red">*</span></label>
                            <input type="number" step="1" min="0" class="form-control" id="qty" name="qty" value="{{ $queuing->qty }}">
                        </div>
                    </div>
                    <div class="form-row" style=" padding-left: 5px;">
                        <table style="width: 98%;">
                            <tr style="height: 50px;">
                                @php
                                    $tester_sem = DB::table('nt_queuing_tester')->where('id_queue',$queuing->id)->where('tester','SEM/EDX Tester')->first();
                                @endphp
                                <td>Tester Name</td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="SEM/EDX Tester" id="tester_sem" {{ (in_array('SEM/EDX Tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>SEM/EDX Tester</td>
                                <td><i class="fa fa-arrow-right" aria-hidden="true"></i> ระบุรายละเอียด</td>
                                <td style=" width: 20%; ">
                                    <select id="sem_option_1" name="sem_option_1" class="form-control">
                                        <option value="">---Select---</option>
                                        @foreach($option_sem as $item) 
                                        <option value="{{ $item->detail }}" {{ (@$tester_sem->sem_option_1 == $item->detail)? 'selected' : '' }}>{{ $item->detail }}</option> 
                                        @endforeach
                                    </select>
                                </td>
                                <td style=" width: 20%; ">
                                    <select id="sem_option_2" name="sem_option_2" class="form-control">
                                        <option value="">---Select---</option>
                                        @foreach($option_sem as $item) 
                                        <option value="{{ $item->detail }}" {{ (@$tester_sem->sem_option_2 == $item->detail)? 'selected' : '' }}>{{ $item->detail }}</option> 
                                        @endforeach
                                    </select>
                                </td>
                                <td style=" width: 20%; ">
                                    <select id="sem_option_3" name="sem_option_3" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach($option_sem as $item) 
                                        <option value="{{ $item->detail }}" {{ (@$tester_sem->sem_option_3 == $item->detail)? 'selected' : '' }}>{{ $item->detail }}</option> 
                                        @endforeach
                                    </select>
                                </td>
                            </tr> 
                            <tr style="height: 50px;">
                                @php
                                    $tester_ftir = DB::table('nt_queuing_tester')->where('id_queue',$queuing->id)->where('tester','LIKE','FT-IR tester')->first(); 
                                @endphp
                                <td></td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="FT-IR tester" id="tester_ftir" {{ (in_array('FT-IR tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>FT-IR tester</td>
                                <td><i class="fa fa-arrow-right" aria-hidden="true"></i> ระบุรายละเอียด</td>
                                <td>
                                    <select id="ftir_option_1" name="ftir_option_1" class="form-control">
                                        <option value="">---Select---</option>
                                        @foreach($option_ftir as $item) 
                                        <option value="{{ $item->detail }}" {{ (@$tester_ftir->ftir_option_1 == $item->detail)? 'selected' : '' }}>{{ $item->detail }}</option> 
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select id="ftir_option_2" name="ftir_option_2" class="form-control">
                                        <option value="">---Select---</option>
                                        @foreach($option_ftir as $item) 
                                        <option value="{{ $item->detail }}" {{ (@$tester_ftir->ftir_option_2 == $item->detail)? 'selected' : '' }}>{{ $item->detail }}</option> 
                                        @endforeach
                                    </select>
                                </td>
                                <td></td>
                            </tr>
                            <tr style="height: 50px;">
                                <td></td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="LPC tester" id="tester_lpc" {{ (in_array('LPC tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>LPC tester</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="height: 50px;">
                                @php
                                    $tester_gcms = DB::table('nt_queuing_tester')->where('id_queue',$queuing->id)->where('tester','LIKE','GC-MS Tester')->first(); 
                                @endphp
                                <td></td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="GC-MS Tester" id="tester_gcms" {{ (in_array('GC-MS Tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>GC-MS Tester</td>
                                <td><i class="fa fa-arrow-right" aria-hidden="true"></i> ระบุรายละเอียด AML flow: </td>
                                <td>
                                    <select id="gcms_option" name="gcms_option" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="Yes" style="background: rgb(140 197 74);" {{ (@$tester_gcms->gcms_option == 'Yes')? 'selected' : '' }}>Yes</option>
                                        <option value="No" style="background: rgb(249 81 69);" {{ (@$tester_gcms->gcms_option == 'No')? 'selected' : '' }}>No</option> 
                                    </select>
                                </td>
                                <td style="padding-left: 50px;"><i class="fa fa-arrow-right" aria-hidden="true"></i> AML type :</td>
                                <td>
                                    <select id="gcms_aml_type" name="gcms_aml_type" class="form-control">
                                        <option value="">--Select--</option>
                                        <option value="Special" style="background: rgb(242 221 35);" {{ (@$tester_gcms->gcms_aml_type == 'Special')? 'selected' : '' }}>Special</option> 
                                        <option value="Monitor" style="background: rgb(140 197 74);" {{ (@$tester_gcms->gcms_aml_type == 'Monitor')? 'selected' : '' }}>Monitor</option>
                                    </select>
                                </td>
                            </tr>
                            <tr style="height: 50px;">
                                <td></td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="IC Tester" id="tester_ic" {{ (in_array('IC Tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>IC Tester</td>
                                <td></td>
                                <td></td>
                                <td id="td_gcms_smple_type_desc1" style="padding-left: 50px;"><i class="fa fa-arrow-right" aria-hidden="true"></i>Sample Type Description:</td>
                                <td id="td_gcms_smple_type_desc2">
                                    <input type="text" class="form-control" id="gcms_smple_type_desc" name="gcms_smple_type_desc" value="{{ @$tester_gcms->gcms_smple_type_desc }}">
                                </td>
                            </tr>
                            <tr style="height: 50px;">
                                <td></td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="DSC Tester" id="tester_dsc" {{ (in_array('DSC Tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>DSC Tester</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="height: 50px;">
                                <td></td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="Weight Tester" id="tester_weight" {{ (in_array('Weight Tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>Weight Tester</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="height: 50px;">
                                <td></td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="Fallen Dust Tester" id="tester_fallen" {{ (in_array('Fallen Dust Tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>Fallen Dust Tester </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr style="height: 50px;">
                                <td></td>
                                <td> <input class="form-check-input checkbox" type="checkbox" name="tester[]" value="Smoke Tester" id="tester_smoke" {{ (in_array('Smoke Tester', $array_tester))? 'checked' : '' }}> </td>
                                <td>Smoke Tester </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr> 
                        </table>
                    </div> 
                    <br>

                    <label><b>Sample from Process</b></label>
                    <div class="form-row"> 
                        <div class="form-group col-md-6">
                            <label for="returnsemple_text">Process</label>
                            <input type="text" class="form-control" id="returnsemple_text" name="returnsemple_text" value="{{ $queuing->returnsemple_text }}">
                        </div>
                        <div class="form-group col-md-6 mt-2">
                            <br>เช่น LB-Form / Fx-Form / Laser /GL Forming / FVI เป็นต้น 
                        </div>
                    </div>
                    <div class="form-row"> 
                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Return Sample or Not </label>
                            <select id="returnsemple_st" name="returnsemple_st" class="form-control">
                                <option value="Yes" style="background: rgb(140 197 74);" {{ ($queuing->returnsemple_st == 'Yes')? 'selected' : '' }}>Yes</option>
                                <option value="No" style="background: rgb(249 81 69);" {{ ($queuing->returnsemple_st == 'No')? 'selected' : '' }}>No</option> 
                            </select>
                        </div>
                         <div class="form-group col-md-3" id="div_return_recipient">
                            <label for="return_recipient"> Sample Return Recipient </label>
                            <input type="text" class="form-control" id="return_recipient" name="return_recipient" value="{{ $queuing->return_recipient }}">
                        </div>
                        <div class="form-group col-md-3" id="div_return_recipient_number">
                            <label for="return_recipient_number">  Recipient Contact Number  </label>
                            <input type="text" class="form-control" id="return_recipient_number" name="return_recipient_number" value="{{ $queuing->return_recipient_number }}">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="requester">Requester<span class="red">*</span></label>
                            <input type="text" class="form-control" id="requester" name="requester" value="{{ $queuing->requester }}" require>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email_requester">E-mail Requester <span class="red">*</span></label>
                            <label class="form-label text-danger email_requester_err _err"></label>
                            <input type="text" class="form-control" id="email_requester" name="email_requester" value="{{ $queuing->email_requester }}" require>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="requester">Pictures</label>
                            <input type="file" class="form-control" id="pictures" name="pictures" accept="image/png, image/gif, image/jpeg" />
                            @if(isset($queuing->pictures) && !empty($queuing->pictures))
                                @php
                                    $picture ='../local/storage/images/Queue/'.$queuing->pictures; 
                                @endphp
                                <br><img data-toggle="modal" data-target="#ModalImages" src="{{ @$picture }}" alt="Picture" style=" width: 200px; cursor: pointer;">
                            @endif
                        </div>
                    </div>
                    
                    <br>
                    <div class="form-row text-right">
                        <a href="{{ url('queue') }}"><button type="button" class="btn btn-light waves-effect m-r-10">BACK</button></a>
                        <button type="button" onclick="buttonadd()" id="buttoncreate" class="btn btn-success waves-effect m-r-10">EDIT DATA</button>
                    </div> 

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalImages" tabindex="-1" role="dialog" aria-labelledby="ModalImages" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titleModalImages">Pictures</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div> 
            <div class="modal-body">
                    
                <div class="mb-3">
                    @if($queuing->pictures)
                    @php
                        $picture ='../local/storage/images/Queue/'.$queuing->pictures; 
                    @endphp
                    <img src="{{ @$picture }}" alt="Picture" style=" width: 100%;">
                    @endif
                </div> 
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="{{ @$picture }}" Download><button type="button" class="btn btn-primary">Download Pictures</button></a>
            </div>
            </div>
        </div>
</div>
@endsection

@section('scripts')
<script>

    
    $('#gcms_option').change(function () {
        let selected = $(this).val();
        if( selected == "Yes"){
           
            $('#td_gcms_aml_type1').show();  
            $('#td_gcms_aml_type2').show();
            
            $('#td_gcms_smple_type_desc1').show();
            $('#td_gcms_smple_type_desc2').show();

        }else{

            $('#td_gcms_aml_type1').hide();  
            $('#td_gcms_aml_type2').hide();  

            $('#td_gcms_smple_type_desc1').hide();
            $('#td_gcms_smple_type_desc2').hide();

        }
    });

    $('#gcms_aml_type').change(function () {
        let selected = $(this).val();
        if( selected == "Special"){
            $('#td_gcms_smple_type_desc1').show();  
            $('#td_gcms_smple_type_desc2').show();
        }else{
            $('#td_gcms_smple_type_desc1').hide();
            $('#td_gcms_smple_type_desc2').hide();
        }
    });

    
    $('#returnsemple_st').change(function () {
        let selected = $(this).val();
        if( selected == "Yes"){
            $('#div_return_recipient').show();  
            $('#div_return_recipient_number').show();  
        }else{
            $('#div_return_recipient').hide();
            $('#div_return_recipient_number').hide();
        }
    });

    $('#return_recipient').change(function () {
        let selected = $(this).val();
        if( selected !== ""){
            $('#div_return_recipient_number').show();  
        }else{
            $('#div_return_recipient_number').hide();
        }
    });
    
 
    function buttonadd() {

        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });

        var waringpoint = 0;
        $('input[name="tester[]"]:checked').each(function() {
            if ($(this).val() === 'SEM/EDX Tester') {
                var sem_option_1 = $('#sem_option_1').val();
                if (sem_option_1 === "") {
                    
                    swal("error", "Enter SEM/EDX tester details – Field 1" , "error");
                    waringpoint++;
                    
                }
            }

            if ($(this).val() === 'FT-IR tester') {
                var ftir_option_1 = $('#ftir_option_1').val();
                if (ftir_option_1 === "") {
                    
                    swal("error", "Enter FT-IR tester details – Field 1" , "error");
                    waringpoint++;
                    
                }
            }

            if ($(this).val() === 'GC-MS Tester') {
                var gcms_option = $('#gcms_option').val();
                if (gcms_option === "") {
                    
                    swal("error", "Enter GC-MS Tester details – Field AML flow" , "error");
                    waringpoint++;
                    
                }else{

                    if(gcms_option == "Yes"){

                        var gcms_aml_type = $('#gcms_aml_type').val();
                        if (gcms_aml_type === "") {
                            swal("error", "Enter AML type" , "error");
                            waringpoint++;
                        }else{
                            if(gcms_aml_type == "Special") {
                                var gcms_smple_type_desc = $('#gcms_smple_type_desc').val();
                                if (gcms_smple_type_desc === "") {
                                    swal("error", "Sample Type Description" , "error");
                                    waringpoint++;
                                }
                            }
                        }
                    }


                }
            }

        });

        
        var returnsemple_st = $('#returnsemple_st').val();  
        if(returnsemple_st == "Yes"){
            
            var return_recipient = $('#return_recipient').val();  
            var return_recipient_number = $('#return_recipient_number').val();  

            if(return_recipient === "" || return_recipient_number == ""){

                swal("error", "Enter Retrun recipient and Email Field" , "error");
                waringpoint++;
            }
        }

        if(waringpoint == 0){
            var forms = $('#fm-updatelab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('updateQueue') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    swal.close();
                    if (data.success == 1) {

                        swal("Successed", "Submit save data successed", "success");
                        setTimeout(function () {
                           location.reload();
                        }, 1000);
                        

                    } else {
 
                        if(typeof data.error === 'string') {
                            setTimeout(function () {
                                swal("error", "Please complete all required fields", "error");
                            }, 1000);
                        }else{
                            setTimeout(function () {
                                swal("error", data.error , "error");
                            }, 1000);
                            printErrorMsg(data.error);
                        }

                    }
                }
            });
        } 
    }

        // -- Start msg err ที่ถูกส่งมา --
        function printErrorMsg(msg) {
            // console.log(msg);
            $('._err').text('');
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(`*${value}*`);
                let TextKey = key.split(".", 2);
                if (TextKey.length == 2) {
                    $(`#storeModal .detailLot:eq(${TextKey[1]}) .${TextKey[0]}_err`).text(`*${value}*`);
                    $(`#pickOut .box_pick_out:eq(${TextKey[1]}) .${TextKey[0]}_err`).text(`*${value}*`);
                }
            });
        }        
  
</script>
@endsection
