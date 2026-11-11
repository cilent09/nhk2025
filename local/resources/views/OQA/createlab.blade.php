@extends('layouts.main')
@section('title', 'Create New OQA lot')
@section('breadcrumb-title-href', 'incoming')
@section('breadcrumb-title', 'New OQA lot')
@section('breadcrumb-sub', 'Create New OQA lot')

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

    .j-pro input[type="date"], .j-pro input#txt_tga {
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

    .j-pro input[type="date"]:hover, .j-pro input#txt_tga:hover {
        border: 1px solid #4099ff;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-block">
                <div class="j-wrapper j-wrapper-640">
                    <form  method="post" class="j-pro" id="fm-createlab" onsubmit="return false;">
                    {{ csrf_field() }}
                        <div class="j-content">
                            <div class="j-unit">
                                <label class="j-input j-select">
                                    <select id="chktype" name="chktype">
                                        <option value="">Select type</option>
                                        @php $menu = session('namemenu'); @endphp
                                        @if($menu == 'oqa')
                                            <option value="SGT_TNHK">SGT_TNHK</option>
                                            <option value="SGT_Oversea">SGT_Oversea</option>
                                        @else
                                            <option value="WD_TNHK">WD_TNHK</option>
                                            <option value="WD_Oversea">WD_Oversea</option>
                                        @endif
                                    </select>
                                    <i></i>
                                </label>
                            </div>

                            <div class="j-unit">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Barcode" id="inputbarcode" name="inputbarcode" />
                                    <!-- <span class="input-group-append">
                                        <button type="button" onclick="testManualBarcode()" class="btn btn-primary" style="height: 48px;"><i class="icofont icofont-bug"></i> TEST PARSE</button>
                                    </span> -->
                                </div>
                            </div>

                            <div class="j-unit">
                                <label class="j-input j-select">
                                    <select class="js-example-data-array col-sm-12" id="chkmodel" name="chkmodel" onchange="chkmodelst()">
                                        <option value="" selected>Select Model</option>
                                        @foreach($model as $item)
                                            <option value="{{$item->id_model}}" >{{$item->name_model}}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_part">
                                        <i class="icofont icofont-ticket"></i>
                                    </label>
                                    <input type="text" placeholder="Part UP/DN" id="txt_part" name="txt_part" disabled>
                                </div>
                            </div>
                            <div class="divider gap-bottom-25"></div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_name">
                                        <i class="icofont icofont-leaflet"></i>
                                    </label>
                                    <input type="text" id="txt_name" name="txt_name" placeholder="Name" name="address" require>
                                </div>
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_code">
                                        <i class="icofont icofont-id"></i>
                                    </label>
                                    <input type="text" id="txt_code" name="txt_code" placeholder="ID" name="address" require>
                                </div>
                            </div>
                            <div class="j-unit">
                                <label>Part Number</label>
                                <div class="j-input mt-2">
                                    <input class="form-control" type="text" id="txt_part_number" name="txt_part_number" placeholder="Part Number" require>
                                </div>
                            </div>
                            <div class="j-unit">
                                <label>TGA Date Code</label>
                                <div class="j-input mt-2">
                                    <input class="form-control" type="text" id="txt_tga" name="txt_tga" placeholder="DD/MM/YYYY" value="{{ date('d/m/Y') }}" require>
                                </div>
                            </div>
                            <div class="j-unit">
                                <div class="j-input">
                                    <label class="j-icon-right" for="txt_lotnumber">
                                        <i class="icofont icofont-bar-code"></i>
                                    </label>
                                    <input type="text" id="txt_lotnumber" name="txt_lotnumber" placeholder="Lot Number" >
                                </div>
                            </div>
                            <div class="divider gap-bottom-25"></div>
                            <div class="j-response"></div>
                        </div>
                        <div class="j-footer">
                            <button type="button" onclick="buttonadd()" id = "buttoncreate" class="btn btn-success waves-effect m-b-10"  >CREATE</button>
                            <button type="reset" onclick="buttonreset()"  class="btn btn-secondary m-r-20">RESET</button>
                            <p align="left">
                                <input  onclick="buttoncreatedate()"  type="button" class="btn btn-warning" value="Special incoming" />
                            </p>
                        </div>
                        <div class="modal fade" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                       <div id="title"> <h4 class="modal-title"><i class="ti-server text-c-green"></i> Create Incoming LAB</h4></div>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <br>
                                    <br>
                                        <div  id="datechk" class="col-sm-12">
                                            <div class="col-md-10 col-xl-12">
                                               <h4><label style="text-align: center;">Tester</label> </h4>
                                            </div>
                                        </div>
                                    <input type="hidden" id="chkaddid" name="chkaddid">
                                    <div class="col-sm-12">
                                        <div class="col-md-10 col-xl-12">
                                           <center> <h5>&nbsp;<span id="textal"></span></h5></center>
                                                <div class="card-block seo-statustic">

                                                <input type="hidden" id="lpchidden" value="0">
                                                    <p>&nbsp;</p>
                                                    <div id="h_lpc">
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input onchange="lpcchecked()" type="checkbox"
                                                                    id="ck_lpc"
                                                                    name="ck_lpc" value="1"
                                                                    style="display:none">
                                                                <span class="cr">
                                                                    <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                </span>
                                                                <span><span id="textlpc"></span> LPC</span>
                                                            </label>
                                                        </div>
                                                        <hr>
                                                    </div>

                                                    <div id="h_sem">
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input onchange="semchecked()" type="checkbox"
                                                                    id="ck_sem"
                                                                    name="ck_sem" value="1"
                                                                    style="display:none" >
                                                                <span class="cr">
                                                                    <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                </span>
                                                                <span><span id="textsem"></span> SEM</span>
                                                            </label>
                                                        </div>
                                                        <hr>
                                                    </div>

                                                    <div  id="h_ic">
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input onchange="icchecked()" type="checkbox"
                                                                    id="ck_ic"
                                                                    name="ck_ic" value="1"
                                                                    style="display:none">
                                                                <span class="cr">
                                                                    <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                </span>
                                                                <span><span id="textic"></span> IC</span>
                                                            </label>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                    <div  id="h_ftir">
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input onchange="ftirchecked()" type="checkbox"
                                                                    id="ck_ftir"
                                                                    name="ck_ftir" value="1"
                                                                    style="display:none">
                                                                <span class="cr">
                                                                    <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                </span>
                                                                <span>
                                                                    <span id="textftir"></span>
                                                                    <a id="name_ftir" style="font-size: 16px;">FTIR</a>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                    <div id="h_gcms">
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input onchange="gcmschecked()" type="checkbox"
                                                                    id="ck_gcms"
                                                                    name="ck_gcms" value="1"
                                                                    style="display:none">
                                                                <span class="cr">
                                                                    <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                </span>
                                                                <span>
                                                                    <span id="textgcms"></span>
                                                                    <a id="name_gcms" style="font-size: 16px;">GCMS</a>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <hr>
                                                    </div>

                                                    <div id="h_semtest">
                                                        <div class="checkbox-fade fade-in-primary">
                                                            <label>
                                                                <input onchange="semtestchecked()" type="checkbox"
                                                                    id="ck_semtest"
                                                                    name="ck_semtest" value="1"
                                                                    style="display:none">
                                                                <span class="cr">
                                                                    <i class="cr-icon icofont icofont-ui-check txt-primary"></i>
                                                                </span>
                                                                <span>
                                                                    <span id="textsemtest"></span>
                                                                    <a id="name_ghost" style="font-size: 16px;">SEM (Ghost)</a>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="lpc1">
                                                <input type="hidden" id="sem1">
                                                <input type="hidden" id="ic1">
                                                <input type="hidden" id="gcms1">
                                                <input type="hidden" id="ftir1">
                                                <input type="hidden" id="semtest1">

                                        </div>
                                    </div>
                                    <div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn btn-secondary">Close</a>
                                        <button type="button" id="submitcreatelab" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="myModaldate">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                       <div id="title"> <h4 class="modal-title">Create Incoming LAB</h4></div>
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    </div>
                                    <br>
                                    <br>
                                        <div  id="datechk" class="col-sm-12">
                                            <div class="col-md-10 col-xl-12">
                                               <h4><label style="text-align: center;">Date</label> </h4>
                                            <div>&nbsp;</div>
                                            <input type="datetime-local"  class="form-control" name="specialdate" id="specialdate">
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <div class="modal-footer"> <a href="#" data-dismiss="modal" class="btn btn-secondary">Close</a>
                                        <button type="button"  onclick="specialft()" id="specialftp" class="btn btn-primary">Select</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="scanModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan Barcode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="reader" style="width: 100%;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="stopScanner()" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@section('scripts')
<script>

$(document).ready(function() {

    var $logs = $('#key-logs');
    var fullString = "";
    var isProcessing = false;

    $(document).on('keydown', function(e) {
        var keyName = e.key || "";
        var keyCode = e.which || e.keyCode;

        if (e.ctrlKey && (keyName === 'j' || keyCode === 74)) {
            e.preventDefault();
            return false;
        }

        if (keyName && keyName.length === 1 && !e.ctrlKey && !e.altKey) {
            fullString += keyName;
        } else if (keyCode === 9) {
            e.preventDefault();
            fullString += " ";
        }

        if (keyCode === 13) {
            e.preventDefault();

            if (fullString && fullString.trim().length > 3 && !isProcessing) {

                isProcessing = true;

                if (typeof checkvalueQRCode === "function" && fullString !== null) {
                    checkvalueQRCode(fullString);
                }

                fullString = "";

                setTimeout(function(){
                    isProcessing = false;
                }, 500);
            } else {
                fullString = "";
            }
        }
    });
});

    function savelogscan(chktype,barcodetxt,partNumber,lotNumber,status){
        $.ajax({
            type: 'POST',
            url: '{{ route('log_scanbarcode') }}',
            dataType: 'json',
            data : {
                chktype:chktype,
                barcodetxt:barcodetxt,
                partNumber:partNumber,
                lotNumber:lotNumber,
                status:status,
                "_token": "{{ csrf_token() }}",
            },
            success: function (result) {
            },
            error: function (data) {
            }
        });
    }

    /* [ฟังก์ชันสำหรับคลิกทดสอบแบบพิมพ์เอง] ลบฟังก์ชันนี้ออกได้ภายหลัง */
    function testManualBarcode() {
        var manualValue = $('#inputbarcode').val().trim();
        var selectedType = $('#chktype').val();

        if (selectedType === "") {
            swal("warning", "กรุณาเลือก Select type ก่อนกดปุ่มทดสอบ", "warning");
            return;
        }

        if (manualValue === "") {
            swal("warning", "กรุณาพิมพ์รหัสสลัก (String) ลงในช่อง Barcode ก่อนกดปุ่มทดสอบ", "warning");
            return;
        }

        checkvalueQRCode(manualValue);
    }

    /* [ฟังก์ชันกลางสหรับตรวจสอบ Format วันที่ DD/MM/YYYY] */
    function validateTGADateFormat(dateStr) {
        if (!dateStr || dateStr.trim() === "") {
            return { isValid: false, message: "TGA Date Code ห้ามเป็นค่าว่าง! กรุณาระบุวันที่" };
        }

        // ตรวจสอบโครงสร้างเบื้องต้นด้วย Regular Expression (ต้องเป็น ตัวเลข2หลัก / ตัวเลข2หลัก / ตัวเลข4หลัก)
        var regex = /^\d{2}\/\d{2}\/\d{4}$/;
        if (!regex.test(dateStr)) {
            return { isValid: false, message: "รูปแบบวันที่ไม่ถูกต้อง! กรุณากรอกตามรูปแบบ DD/MM/YYYY เท่านั้น (ตัวอย่างเช่น 25/06/2026)" };
        }

        // ตรวจสอบความถูกต้องของวันและเดือนในทางปฏิทินป้องการกรอก เช่น 32/02/2026
        var parts = dateStr.split("/");
        var day = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var year = parseInt(parts[2], 10);

        if (month < 1 || month > 12) {
            return { isValid: false, message: "เดือนไม่ถูกต้อง! ต้องอยู่ระหว่าง 01 ถึง 12" };
        }

        var monthLength = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        // ตรวจสอบปีอธิกสุรทิน (Leap Year) สำหรับเดือนกุมภาพันธ์
        if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)) {
            monthLength[1] = 29;
        }

        if (day < 1 || day > monthLength[month - 1]) {
            return { isValid: false, message: "วันไม่ถูกต้อง! ไม่มีวันที่ " + day + " ในเดือน " + month + " ของปี พ.ศ./ค.ศ. นี้" };
        }

        return { isValid: true, message: "" };
    }

    function checkvalueQRCode(value){
        console.log("--- Raw Barcode Scanned ---", value);

        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const tgaDate = `${day}/${month}/${year}`;

        var text = value;
        var chktype = $('#chktype').val();
        var countstring = value.length;

        if(chktype == "SGT_Oversea"){

            var str2 = "/";
            const sections = text.split(' ');
            if(text.indexOf(str2) != -1){
                const dateMatch = text.match(/\d{1,2}\/\d{1,2}\/\d{2,4}/);
                var dates = dateMatch ? convertUsToCustomFormat(dateMatch[0]) : "";
                $('#txt_tga').val(dates);
                // $("#txt_name").val("");
                // $("#txt_code").val("");

                console.log("=> [SGT_Oversea - Date Path] Parsed Data:", {
                    chktype: chktype,
                    raw_text: text,
                    extracted_date: dates
                });

                savelogscan(chktype,text,"","","success");

            } else if (sections.length >= 3) {

                const partNumber = sections[0].replace(/[^a-zA-Z0-9]/g, "");
                const lotNumber = sections[2];

                $('#txt_part_number').val(partNumber);
                $('#txt_lotnumber').val(lotNumber);
                // $('#txt_tga').val(tgaDate);
                $("#txt_name").val("");
                $("#txt_code").val("");
                $("#txt_tga").val("");

                console.log("=> [SGT_Oversea - Data Path] Parsed Data:", {
                    chktype: chktype,
                    raw_text: text,
                    partNumber: partNumber,
                    lotNumber: lotNumber,
                    // tgaDate: tgaDate
                });

                savelogscan(chktype,text,partNumber,lotNumber,"success");

                getModellist_barcode(partNumber);
            }else{
                console.warn("=> [SGT_Oversea] String format unmatched standard structure.");
                savelogscan(chktype,text,"","","fail");
                errorbarcode(1);
            }
        }else if(chktype == "WD_Oversea"){
            if (text.includes('$')) {
                const sections = text.split('$');
                if(sections.length == 4){
                    const partNumber = sections[0];
                    const lotNumber = sections[1];

                    $('#txt_part_number').val(partNumber);
                    $('#txt_lotnumber').val(lotNumber);
                    $('#txt_tga').val(tgaDate);
                    $("#txt_name").val("");
                    $("#txt_code").val("");

                    console.log("=> [WD_Oversea - Split $] Parsed Data:", {
                        chktype: chktype,
                        raw_text: text,
                        partNumber: partNumber,
                        lotNumber: lotNumber,
                        tgaDate: tgaDate
                    });

                    getModellist_barcode(partNumber);

                    savelogscan(chktype,text,partNumber,lotNumber,"success");
                }else{
                    console.warn("=> [WD_Oversea] String contains '$' but section count is not equal to 4.");
                    savelogscan(chktype,text,"","","fail");
                    errorbarcode(1);
                }
            }else{
                $('#txt_part_number').val(text);
                var dataArray = $.trim(text).split(/\s+/);
                if (/\s/.test(text)) {
                    var sections2 = $.trim(text).split(/\s+/);
                    if(sections2.length == 4) {
                        const partNumber = sections2[0];
                        const lotNumber = sections2[2];

                        $('#txt_part_number').val(partNumber);
                        $('#txt_lotnumber').val(lotNumber);
                        $('#txt_tga').val(tgaDate);
                        $("#txt_name").val("");
                        $("#txt_code").val("");

                        console.log("=> [WD_Oversea - Space Split] Parsed Data:", {
                            chktype: chktype,
                            raw_text: text,
                            partNumber: partNumber,
                            lotNumber: lotNumber,
                            tgaDate: tgaDate
                        });

                        getModellist_barcode(partNumber);
                        savelogscan(chktype,text,partNumber,lotNumber,"success");
                    } else {
                        console.warn("=> [WD_Oversea] Space-separated string length is not equal to 4.");
                        savelogscan(chktype,text,"","","fail");
                        errorbarcode(1);
                    }
                } else {
                    console.warn("=> [WD_Oversea] Standard regex space rule mismatched.");
                    savelogscan(chktype,text,"","","fail");
                    errorbarcode(1);
                }
            }
        }else if(chktype == "SGT_TNHK"){
            var trimmedStr = $.trim(text);
            text = trimmedStr;
            if(text.includes('$')){
                const sections = text.split('$');
                if(sections.length == 4){
                    var partNumber = sections[0];
                    const lotNumber = sections[1];

                    if(partNumber.indexOf('-') != -1){
                        partNumber = partNumber.split('-')[0];
                    }

                    $('#txt_part_number').val(partNumber);
                    $('#txt_lotnumber').val(lotNumber);
                    $('#txt_tga').val(tgaDate);
                    $("#txt_name").val("");
                    $("#txt_code").val("");

                    console.log("=> [SGT_TNHK - Split $] Parsed Data:", {
                        chktype: chktype,
                        raw_text: text,
                        partNumber: partNumber,
                        lotNumber: lotNumber,
                        tgaDate: tgaDate
                    });

                    getModellist_barcode(partNumber);
                    savelogscan(chktype,text,partNumber,lotNumber,"success");
                }else{
                    console.warn("=> [SGT_TNHK] String contains '$' but section count is not equal to 4.");
                    savelogscan(chktype,text,"","","fail");
                    errorbarcode(1);
                }
            }else if(text.length == 34 || text.length == 33){
                const partNumber = text.split('-')[0];
                const sections = text.split('$');
                const lotNumber = (sections.length > 1) ? sections[1] : 'N/A';

                if(lotNumber.length == 9){

                    $('#txt_part_number').val(partNumber);
                    $('#txt_lotnumber').val(lotNumber);
                    $('#txt_tga').val(tgaDate);
                    $("#txt_name").val("");
                    $("#txt_code").val("");

                    console.log("=> [SGT_TNHK] Parsed Data:", {
                        chktype: chktype,
                        raw_text: text,
                        partNumber: partNumber,
                        lotNumber: lotNumber,
                        tgaDate: tgaDate
                    });

                    getModellist_barcode(partNumber);
                    savelogscan(chktype,text,partNumber,lotNumber,"success");

                }else{
                    console.warn("=> [SGT_TNHK] Split successfully but lotNumber length is not 9.");
                    savelogscan(chktype,text,"","","fail");
                    errorbarcode(1);
                }
            }else{
                console.warn("=> [SGT_TNHK] Total barcode string length is not equal to 34 or 33");
                savelogscan(chktype,text,"","","fail");
                errorbarcode(1);
            }
        }else if(chktype == "WD_TNHK"){

            const sections = text.split('$');
            if(sections.length == 4){
                const partNumber = sections[0];
                const lotNumber = sections[1];

                $('#txt_part_number').val(partNumber);
                $('#txt_lotnumber').val(lotNumber);
                $('#txt_tga').val(tgaDate);
                $("#txt_name").val("");
                $("#txt_code").val("");

                console.log("=> [WD_TNHK] Parsed Data:", {
                    chktype: chktype,
                    raw_text: text,
                    partNumber: partNumber,
                    lotNumber: lotNumber,
                    tgaDate: tgaDate
                });

                getModellist_barcode(partNumber);
                savelogscan(chktype,text,partNumber,lotNumber,"success");
            }else{
                console.warn("=> [WD_TNHK] Data format does not match standard 4 sections delimiter ($).");
                savelogscan(chktype,text,"","","fail");
                errorbarcode(1);
            }

        }else{
            console.error("=> Type selector invalid or unmatched configuration key.");
            savelogscan(chktype,text,"","","fail");
            errorbarcode(2);
        }
    }


function errorbarcode(ty) {
    const partnumber = $('#txt_part_number').val().trim();
    const lotnumber = $('#txt_lotnumber').val().trim();

    if (!partnumber || !lotnumber) {
        if (ty == 1) {
            swal("Error", "กรุณา scan barcode ใหม่ ", "error");
        } else {
            swal("Error", "โปรดเลือก type ให้ถูกต้อง", "error");
        }
    }
}

$( "#chktype" ).on( "change", function() {
    var str = "";
    $( "#chktype option:selected" ).each( function() {
      str += $( this ).val() + " ";
    } );

    if(str.length>1){

        var selects = str;
        var type = $('#chktype').val();

        if(type == "SGT_TNHK"){
            var newtype = 'TNHK';
        }else if(type == "SGT_Oversea"){
            // var newtype = 'JNHK';
            var newtype = 'SGT_Oversea';
        }else{
            var newtype = null;
        }

        $.ajax({
            type: 'POST',
            url: '{{ route('getModalList') }}',
            dataType: 'json',
            data : {
                selects:selects,
                type:newtype,
                "_token": "{{ csrf_token() }}",
            },
            success: function (result) {
                $("#chkmodel").empty();
                var table = '';
                table += '<option value="">Select Model</option>';
                $.each(result.model, function (key, value) {
                    var id = value.id_model;
                    var name = value.name_model;
                    table += '<option value="'+id+'">'+name+'</option>';
                })
                $("#chkmodel").append(table);
            },
            error: function (data) {

            }
        });

        var checktype = $( this ).val();
        if(checktype == "SGT_TNHK" || checktype == "WD_Oversea" || checktype == "WD_TNHK"){
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = day + "/" + month + "/" + now.getFullYear();
            $('#txt_tga').val(today);
        }else{
            $('#txt_tga').val("");
        }
    }

}).trigger( "change" );

function getModellist_barcode(partnumber)
{
    var type = $('#chktype').val();

    if(type == "SGT_TNHK"){
        var newtype = 'TNHK';
    }else if(type == "SGT_Oversea"){
        // var newtype = 'JNHK';
        var newtype = 'SGT_Oversea';
    }else{
        var newtype = null;
    }

    // alert(partnumber);

    $.ajax({
        type: 'POST',
        url: '{{ route('getModalList') }}',
        dataType: 'json',
        data : {
            partnumber:partnumber,
            type:newtype,
            "_token": "{{ csrf_token() }}",
        },
        success: function (result) {
            $("#chkmodel").empty();
            var table = '';
            if (result.model && result.model.length > 0) {
                $.each(result.model, function (key, value) {
                    var id = value.id_model;
                    var name = value.name_model;
                    table += '<option value="'+id+'">'+name+'</option>';
                })
                $("#chkmodel").append(table);
                chkmodelst();
            }else{
                swal("Error", "Model not found", "error");
            }

        },
        error: function (data) {

        }
    });
}

function convertUsToCustomFormat(dateString) {
    const [day, month, year] = dateString.split('/');

    let fullYear = year;
    if (year.length === 2) {
        fullYear = '20' + year;
    }

    return `${day}/${month}/${fullYear}`;
}

function buttoncreatedate() {

    var lotnumber = $("#txt_lotnumber").val();
    var part_number = $("#txt_part_number").val();
    var tgacode = $("#txt_tga").val();
    var chktype = $("#chktype").val();
    var chkmodel = $("#chkmodel").val();
    var barcode = $("#inputbarcode").val();

    /* [แก้ไขเพิ่มการตรวจสอบวันที่] ดักรูปแบบก่อนเปิด Modal Special incoming */
    var dateValidation = validateTGADateFormat(tgacode);
    if (!dateValidation.isValid) {
        swal("warning", dateValidation.message, "warning");
        return false; // หยุดทำงานทันที
    }

    if(chktype.indexOf("WD") != -1){
        console.log(" found");

        $("#name_gcms").text("GCMS Outgas");
        $("#name_ftir").text("GCMS Solvent");
        $("#name_ghost").text("MESA");
    }

    if(part_number != ""){
        if(!barcode){
            $.ajax({
                type:'GET',
                url:"{{ url('chkpartnumber') }}",
                data: {
                    'part_number': part_number,
                    'chkmodel' : chkmodel,
                    'chktype' :  chktype,
                },
                success:function(data){
                    console.log(data.chk);
                    if(data.chk != 3){

                        if(tgacode != ""){
                            if(lotnumber != "")
                            {
                                $.ajax({
                                    type:'GET',
                                    url:"{{ url('chklognumber') }}",
                                    data: {
                                        'lotnumber': lotnumber,
                                        'chkmodel' : chkmodel,
                                        'chktype' :  chktype,
                                        'part_number' :  part_number,
                                    },
                                    success:function(data){
                                        if(data.chk == 1)
                                        {
                                            swal("warning", "Improper Digit lot !  ", "warning");
                                        }
                                        else if(data.chk == 2)
                                        {
                                            swal("warning", "Improper Digit lot ! ", "warning");
                                        }
                                        else if(data.chk == 3)
                                        {
                                            swal("warning", "Improper Part Number ! ", "warning");
                                        }
                                        else
                                        {

                                            var chktype = $("#chktype").val();
                                            var chkmodel = $("#chkmodel").val();
                                            var txtname = $("#txt_name").val();
                                            var txtcode = $("#txt_code").val();
                                            var txtlotnumber = $("#txt_lotnumber").val();
                                            if (chktype == "" || chkmodel == "" || txtname == "" || txtcode == "" || txtlotnumber == "") {
                                                swal("warning", "Please complete all information...", "warning");
                                            } else {
                                                $('#myModaldate').modal('show');
                                            }
                                        }
                                    }
                                });
                            }
                            else
                            {
                                swal("warning", "Lot Number cannot be blank. Please fill it in. ", "warning");
                                document.getElementById("buttoncreate").disabled = true;
                            }
                        }else{
                            swal("warning", "TGA date code cannot be blank. Please fill it in. ", "warning");
                        }
                    }else{
                        swal("warning", "The Part Number is incorrect ! ", "warning");
                    }
                }
            });
        }else{
            var chktype = $("#chktype").val();
            var chkmodel = $("#chkmodel").val();
            var txtname = $("#txt_name").val();
            var txtcode = $("#txt_code").val();
            var txtlotnumber = $("#txt_lotnumber").val();
            if (chktype == "" || chkmodel == "" || txtname == "" || txtcode == "" || txtlotnumber == "") {
                swal("warning", "Please complete all information...", "warning");
            } else {
                $('#myModaldate').modal('show');
            }
        }
    }else{
        swal("warning", "Part Number cannot be blank. Please fill it in. ", "warning");
    }

}

function specialft()
{
    if($("#specialdate").val() == "")
    {
        $('#myModaldate').modal('hide');
        swal("warning", "Please Select Datetime...", "warning");
        return;
    }
    $("#chkaddid").val(2);
    $('#myModaldate').modal('hide');
    var forms = $('#fm-createlab')[0];
        var data = new FormData(forms);
        $.ajax({
            type: 'POST',
            url: "{{ url('chkdaterun') }}",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {

                $('#lpc1').val(0);
                $('#sem1').val(0);
                $('#ic1').val(0);
                $('#gcms1').val(0);
                $('#ftir1').val(0);

                console.log("data.chk ->"+data.chk);

                if (data.chk == 0) {
                    var i = 0;
                    document.getElementById("submitcreatelab").style.display = "inline";
                    if(i = 0)
                    {
                        document.getElementById("submitcreatelab").style.display = "none";
                        $('#textal').html('<p style="color:#ffcc00"><i class="fa fa-warning"></i>Can not create Incoming LAB</p>');
                    }
                } else if (data.chk == 1) {

                } else if (data.chk == 2) {
                    document.getElementById("submitcreatelab").style.display = "inline";
                    $('#textal').html('');
                    if(data.chkmodel.lpc == 1)
                    {

                        if(data.countlot != null)
                        {
                            if(data.countlot.st_lpc == 1)
                            {

                                $( "#ck_lpc" ).prop( "disabled", true );
                                $('#textlpc').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_lpc").checked = false;
                            }
                            else
                            {

                                $('#textlpc').html('');
                                $( "#ck_lpc" ).prop( "disabled", false );
                                document.getElementById("ck_lpc").checked = false;
                            }
                        }
                        else
                        {
                            $('#textlpc').html('');
                            $( "#ck_lpc" ).prop( "disabled", false );
                            document.getElementById("ck_lpc").checked = false;
                        }
                    }
                    else{
                        $('#h_lpc').html('');
                        $('#lpc1').val(1);
                    }

                    if(data.chkmodel.sem == 1)
                    {
                        if(data.countlot != null)
                        {
                            if(data.countlot.st_sem == 1)
                            {
                                $( "#ck_sem" ).prop( "disabled", true );
                                $('#textsem').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_sem").checked = false;
                            }
                            else
                            {
                                $('#textsem').html('');
                                $( "#ck_sem" ).prop( "disabled", false );
                                document.getElementById("ck_sem").checked = false;
                            }
                        }
                        else
                        {
                            $('#textsem').html('');
                            $( "#ck_sem" ).prop( "disabled", false );
                            document.getElementById("ck_sem").checked = false;
                        }
                    }
                    else{
                        $('#h_sem').html('');
                        $('#sem1').val(1);
                    }

                    if(data.chkmodel.ic == 1)
                    {
                        if(data.countlot != null)
                        {
                            if(data.countlot.st_ic == 1)
                            {
                                $( "#ck_ic" ).prop( "disabled", true );
                                $('#textic').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_ic").checked = false;
                            }
                            else
                            {
                                $('#textic').html('');
                                $( "#ck_ic" ).prop( "disabled", false );
                                document.getElementById("ck_ic").checked = false;
                            }
                        }
                        else
                        {
                            $('#textic').html('');
                            $( "#ck_ic" ).prop( "disabled", false );
                            document.getElementById("ck_ic").checked = false;
                        }

                    }
                    else{
                        $('#h_ic').html('');
                        $('#ic1').val(1);
                    }

                    if(data.chkmodel.gcms == 1)
                    {
                        if(data.countlot != null)
                        {
                            if(data.countlot.st_gcms == 1)
                            {
                                $( "#ck_gcms" ).prop( "disabled", true );
                                $('#textgcms').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_gcms").checked = false;
                            }
                            else
                            {
                                $('#textgcms').html('');
                                $( "#ck_gcms" ).prop( "disabled", false );
                                document.getElementById("ck_gcms").checked = false;
                            }
                        }
                        else
                        {
                            $('#textgcms').html('');
                            $( "#ck_gcms" ).prop( "disabled", false );
                            document.getElementById("ck_gcms").checked = false;
                        }

                    }
                    else{
                        $('#h_gcms').html('');
                        $('#gcms1').val(1);
                    }

                    if(data.chkmodel.ftir == 1)
                    {
                        if(data.countlot != null)
                        {
                            if(data.countlot.st_ic == 1)
                            {
                                $( "#ck_ftir" ).prop( "disabled", true );
                                $('#textftir').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_ftir").checked = false;
                            }
                            else
                            {
                                $('#textftir').html('');
                                $( "#ck_ftir" ).prop( "disabled", false );
                                document.getElementById("ck_ftir").checked = false;
                            }
                        }
                        else
                        {
                            $('#textftir').html('');
                            $( "#ck_ftir" ).prop( "disabled", false );
                            document.getElementById("ck_ftir").checked = false;
                        }
                    }
                    else{
                        $('#h_ftir').html('');
                        $('#ftir1').val(1);
                    }

                }
            }
        });
        $("#title").html('<h4 class="modal-title"><i class="ti-server text-c-green"></i> Special Incoming LAB</h4>');
        $('#myModal').modal('show');
}

function buttonadd() {

    var lotnumber = $("#txt_lotnumber").val();
    var part_number = $("#txt_part_number").val();
    var tgacode = $("#txt_tga").val();
    var chktype = $("#chktype").val();
    var chkmodel = $("#chkmodel").val();
    var txtname = $("#txt_name").val();

    /* [แก้ไขเพิ่มการตรวจสอบวันที่] ดักรูปแบบก่อนเปิด Modal ตรวจสอบเครื่องทดสอบสำหรับการทำ CREATE ปกติ */
    var dateValidation = validateTGADateFormat(tgacode);
    if (!dateValidation.isValid) {
        swal("warning", dateValidation.message, "warning");
        return false; // หยุดทำงานทันที
    }

    console.log("chktype -> "+ chktype);
    console.log("chkmodel -> "+ chkmodel);
    console.log("txtname -> "+ txtname);
    console.log("lotnumber -> "+ lotnumber);

    if(chktype.indexOf("WD") != -1){
        console.log(" found");

        $("#name_gcms").text("GCMS Outgas");
        $("#name_ftir").text("GCMS Solvent");
        $("#name_ghost").text("MESA");
    }

    var chktype = $("#chktype").val();
    var chkmodel = $("#chkmodel").val();
    var txtname = $("#txt_name").val();
    var txtcode = $("#txt_code").val();
    var txtlotnumber = $("#txt_lotnumber").val();
    $('#lpchidden').val(0);
    $("#chkaddid").val(1);
    if (chktype == "" || chkmodel == "" || txtname == "" || txtcode == "" || txtlotnumber == "") {
        swal("warning", "Please complete all information...", "warning");
    } else {
        var forms = $('#fm-createlab')[0];
        var data = new FormData(forms);
        $.ajax({
            type: 'POST',
            url: "{{ url('chkdaterun') }}",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {

                    $('#lpc1').val(0);
                    $('#sem1').val(0);
                    $('#ic1').val(0);
                    $('#gcms1').val(0);
                    $('#ftir1').val(0);

                    console.log("data.chk =>"+data.chk);
                    console.log("data.cksemlpc =>"+data.cksemlpc);
                    console.log("data.chkmodel.lpc =>"+data.chkmodel.lpc);
                    console.log("data.chkmodel.sem =>"+data.chkmodel.sem);
                    console.log("data.chkmodel.ic =>"+data.chkmodel.ic);
                    console.log("data.chkmodel.gcms =>"+data.chkmodel.gcms);
                    console.log("data.chkmodel.ftir =>"+data.chkmodel.ftir);
                    console.log("data.chkmodel.semtest =>"+data.chkmodel.semtest);


                    if (data.chk == 0) {
                        var i = 0;
                        document.getElementById("submitcreatelab").style.display = "inline";
                        if(data.chkmodel.lpc == 0)
                        {
                            $( "#ck_lpc" ).prop( "disabled", true );
                            $('#textlpc').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                            document.getElementById("ck_lpc").checked = false;
                        }
                        if(data.chkmodel.semtest == 0)
                        {
                            $( "#ck_semtest" ).prop( "disabled", true );
                            $('#textsemtest').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                            document.getElementById("ck_semtest").checked = false;
                        }
                        if(data.chkmodel.sem == 1)
                        {

                            if (data.chkdaysem > 0 && data.chkdaysem1 > 0) {

                                $("#ck_sem").prop("disabled", true);
                                $('#textsem').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_sem").checked = false;
                            } else {

                                $("#ck_sem").prop("disabled", false);
                                $('#textsem').html('');
                                document.getElementById("ck_sem").checked = false;
                                i++;
                            }
                        }
                        else{

                            $('#h_sem').html('');
                            $('#sem1').val(1);
                        }
                        if(data.chkmodel.ic == 1)
                        {
                            if (data.chkdayic > 0 && data.chkdayic1 > 0) {
                                $("#ck_ic").prop("disabled", true);
                                $('#textic').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_ic").checked = false;
                            } else {
                                $("#ck_ic").prop("disabled", false);
                                $('#textic').html('');
                                document.getElementById("ck_ic").checked = false;
                                i++;
                            }
                        }
                        else
                        {
                            $('#h_ic').html('');
                            $('#ic1').val(1);
                        }
                        if(data.chkmodel.gcms == 1)
                        {
                            if (data.chkdaygcms > 0 && data.chkdaygcms1 > 0) {
                                $("#ck_gcms").prop("disabled", true);
                                $('#textgcms').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_gcms").checked = false;
                            } else {
                                $("#ck_gcms").prop("disabled", false);
                                $('#textgcms').html('');
                                document.getElementById("ck_gcms").checked = false;
                                i++;
                            }
                        }
                        else{
                            $('#h_gcms').html('');
                            $('#gcms1').val(1);
                        }
                        if(data.chkmodel.ftir == 1)
                        {
                            if (data.chkdayftir > 0 && data.chkdayftir1 > 0) {
                                $("#ck_ftir").prop("disabled", true);
                                $('#textftir').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                document.getElementById("ck_ftir").checked = false;
                            } else {
                                $("#ck_ftir").prop("disabled", false);
                                $('#textftir').html('');
                                document.getElementById("ck_ftir").checked = false;
                                i++;
                            }
                        }
                        else{
                            $('#h_ftir').html('');
                            $('#ftir1').val(1);
                        }


                    } else if (data.chkmodel.lpc == 1 && data.chkmodel.sem == 0 && data.chkmodel.ic == 0  && data.chkmodel.gcms == 0  && data.chkmodel.ftir == 0  && data.chkmodel.semtest == 0 ) {
                        console.log("สร้างได้เฉพาะ LPC");

                        document.getElementById("submitcreatelab").style.display = "inline";
                        $('#textal').html('');
                        $('#lpchidden').val(1);

                        $('#h_sem').html('');
                        $('#h_ic').html('');
                        $('#h_ftir').html('');
                        $('#h_gcms').html('');
                        $('#h_semtest').html('');

                    } else {
                        document.getElementById("submitcreatelab").style.display = "inline";
                        $('#textal').html('');
                        if(data.chkmodel.lpc == 1)
                        {
                            $('#lpchidden').val(0);
                            if(data.countlot != null)
                            {
                                if(data.countlot.st_lpc == 1)
                                {
                                    $( "#ck_lpc" ).prop( "disabled", true );
                                    $('#textlpc').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                    document.getElementById("ck_lpc").checked = false;
                                }
                                else
                                {
                                    $('#textlpc').html('');
                                    $( "#ck_lpc" ).prop( "disabled", false );
                                    document.getElementById("ck_lpc").checked = false;
                                }
                            }
                            else
                            {
                                $('#textlpc').html('');
                                $( "#ck_lpc" ).prop( "disabled", false );
                                document.getElementById("ck_lpc").checked = false;
                            }
                        }
                        else{
                            $('#h_lpc').html('');
                            $('#lpc1').val(1);
                        }

                        if(data.chkmodel.sem == 1)
                        {
                            if(data.countlot != null)
                            {
                                if(data.countlot.st_sem == 1)
                                {
                                    $( "#ck_sem" ).prop( "disabled", true );
                                    $('#textsem').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                    document.getElementById("ck_sem").checked = false;
                                }
                                else
                                {
                                    $('#textsem').html('');
                                    $( "#ck_sem" ).prop( "disabled", false );
                                    document.getElementById("ck_sem").checked = false;
                                }
                            }
                            else
                            {
                                $('#textsem').html('');
                                $( "#ck_sem" ).prop( "disabled", false );
                                document.getElementById("ck_sem").checked = false;
                            }
                        }
                        else{
                            $('#h_sem').html('');
                            $('#sem1').val(1);
                        }

                        if(data.chkmodel.ic == 1)
                        {
                            if(data.countlot != null)
                            {
                                if(data.countlot.st_ic == 1)
                                {
                                    $( "#ck_ic" ).prop( "disabled", true );
                                    $('#textic').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                    document.getElementById("ck_ic").checked = false;
                                }
                                else
                                {
                                    $('#textic').html('');
                                    $( "#ck_ic" ).prop( "disabled", false );
                                    document.getElementById("ck_ic").checked = false;
                                }
                            }
                            else
                            {
                                $('#textic').html('');
                                $( "#ck_ic" ).prop( "disabled", false );
                                document.getElementById("ck_ic").checked = false;
                            }

                        }
                        else{
                            $('#h_ic').html('');
                            $('#ic1').val(1);
                        }

                        if(data.chkmodel.gcms == 1)
                        {
                            if(data.countlot != null)
                            {
                                if(data.countlot.st_gcms == 1)
                                {
                                    $( "#ck_gcms" ).prop( "disabled", true );
                                    $('#textgcms').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                    document.getElementById("ck_gcms").checked = false;
                                }
                                else
                                {
                                    $('#textgcms').html('');
                                    $( "#ck_gcms" ).prop( "disabled", false );
                                    document.getElementById("ck_gcms").checked = false;
                                }
                            }
                            else
                            {
                                $('#textgcms').html('');
                                $( "#ck_gcms" ).prop( "disabled", false );
                                document.getElementById("ck_gcms").checked = false;
                            }

                        }
                        else{
                            $('#h_gcms').html('');
                            $('#gcms1').val(1);
                        }

                        if(data.chkmodel.ftir == 1)
                        {
                            if(data.countlot != null)
                            {
                                if(data.countlot.st_ic == 1)
                                {
                                    $( "#ck_ftir" ).prop( "disabled", true );
                                    $('#textftir').html('&nbsp;&nbsp;<img width="4%" src="{{ asset("local/img_g/close.png")}}" alt="">');
                                    document.getElementById("ck_ftir").checked = false;
                                }
                                else
                                {
                                    $('#textftir').html('');
                                    $( "#ck_ftir" ).prop( "disabled", false );
                                    document.getElementById("ck_ftir").checked = false;
                                }
                            }
                            else
                            {
                                $('#textftir').html('');
                                $( "#ck_ftir" ).prop( "disabled", false );
                                document.getElementById("ck_ftir").checked = false;
                            }

                        }
                        else{
                            $('#h_ftir').html('');
                            $('#ftir1').val(1);
                        }

                    }
                }
        });

        $("#title").html('<h4 class="modal-title"><i class="ti-server text-c-green"></i> Create Incoming LAB</h4>');
        $('#myModal').modal('show');
    }


}

function chkmodelst() {
    var id = $("#chkmodel").val();
    $.ajax({
        type:'GET',
        url:"{{ url('chkmodel') }}",
        data: {
            'id': id,
        },
        success:function(data){
            $("#txt_part").val(data.model.part);
           if(data.model_p.lpc == 0)
           {
            document.getElementById("ck_lpc").disabled = true;
           }
           if(data.model_p.sem == 0)
           {
            document.getElementById("ck_sem").disabled = true;
           }
           if(data.model_p.ic == 0)
           {
            document.getElementById("ck_ic").disabled = true;
           }
           if(data.model_p.gcms == 0)
           {
            document.getElementById("ck_gcms").disabled = true;
           }
           if(data.model_p.ftir == 0)
           {
            document.getElementById("ck_ftir").disabled = true;
           }
        }
    });
}

function icchecked()
{
    if(document.getElementById("ck_ic").checked == false)
    {
        document.getElementById("ck_ic").checked = false;
    }
    else{
        document.getElementById("ck_ic").checked = true;
    }
}

function ftirchecked()
{
    if(document.getElementById("ck_ftir").checked == false)
    {
        document.getElementById("ck_ftir").checked = false;
    }
    else{
        document.getElementById("ck_ftir").checked = true;
    }
}

function semchecked()
{
    if(document.getElementById("ck_sem").checked == false)
    {
        document.getElementById("ck_sem").checked = false;
    }
    else{
        document.getElementById("ck_sem").checked = true;
    }
}

function lpcchecked()
{
    if($('#lpchidden').val() == 0)
    {
        if(document.getElementById("ck_lpc").checked == false)
        {
            document.getElementById("ck_lpc").checked = false;
        }
        else{
            document.getElementById("ck_lpc").checked = true;
        }
    }
}

function semtestchecked()
{
    if(document.getElementById("ck_semtest").checked == false)
    {
        document.getElementById("ck_semtest").checked = false;
    }
    else{
        document.getElementById("ck_semtest").checked = true;
    }
}

$('#submitcreatelab').click(function () {
    var i = 0;
    if($('#lpc1').val() !=1 )
    {
        var element = document.getElementById("ck_lpc");
        if (element) {
            var isChecked = element.checked;
            if( isChecked == true)
            {
                i++;
            }
        }
    }
    if($('#sem1').val() !=1 )
    {
        var element = document.getElementById("ck_sem");
        if (element) {
            var isChecked = element.checked;
            if( isChecked == true)
            {
                i++;
            }
        }

    }
    if($('#ic1').val() !=1 )
    {
        var element = document.getElementById("ck_ic");
        if (element) {
            var isChecked = element.checked;
            if( isChecked == true)
            {
                i++;
            }
        }
    }
    if($('#gcms1').val() !=1 )
    {
        var element = document.getElementById("ck_gcms");
        if (element) {
            var isChecked = element.checked;
            if( isChecked == true)
            {
                i++;
            }
        }
    }
    if($('#ftir1').val() !=1 )
    {
        var element = document.getElementById("ck_ftir");
        if (element) {
            var isChecked = element.checked;
            if( isChecked == true)
            {
                i++;
            }
        }
    }
    if($('#semtest1').val() !=1 )
    {
        var element = document.getElementById("ck_semtest");
        if (element) {
            var isChecked = element.checked;
            if( isChecked == true)
            {
                i++;
            }
        }
    }

    if(i == 0)
    {
        $('#myModal').modal('hide');
        swal("warning", "Please complete all information...", "warning");
        return;
    }


    $('#myModal').modal('hide');
    var forms = $('#fm-createlab')[0];
    var data = new FormData(forms);

    var chktype = $('#chktype').val();


    //-------- WD --------
    if(chktype.indexOf("WD_") != -1){
        swal({
            html: true,
            title: "Are you sure?",
            text: "Confirm Create OQA Incoming!",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('createlabWDSubmit') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", data.message, "success");
                        setTimeout(function () {
                            window.location.href =
                                "{{ url('OQA/incoming') }}";
                        }, 1000);
                    } else {
                        swal("warning", data.message, "warning");
                    }
                }
            });
        });
    }

    //------ SGT ------
    if(chktype.indexOf("SGT_") != -1){
        swal({
            html: true,
            title: "Are you sure?",
            text: "Confirm Create OQA Incoming!",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true
        },
        function () {
            var forms = $('#fm-createlab')[0];
            var data = new FormData(forms);
            $.ajax({
                type: 'POST',
                url: "{{ url('createlabSubmit') }}",
                data: data,
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    if (data.success == 1) {
                        swal("Successed", data.message, "success");
                        setTimeout(function () {
                            window.location.href =
                                "{{ url('OQA/incoming') }}";
                        }, 1000);
                    } else {
                        swal("warning", data.message, "warning");
                    }
                }
            });
        });
    }


});
</script>
@endsection
