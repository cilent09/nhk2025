
@extends('layouts.mainpdf')
<style>
    .btn-xl {
        padding: 10px 20px;
        font-size: 20px;
        border-radius: 10px;
    }
    .toto {
        height: 500px;
        width: 100%;
    }
    #myBtn {
        /* display: none; */
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 99;
        font-size: 18px;
        font-weight: bold;
        border: none;
        outline: none;
        background-color: #FFA500;
        color: #000000;
        cursor: pointer;
        padding: 10px;
        border-radius: 4px;
        opacity: 0.6;
    }
    #myBtn:hover {
        background-color: #FFA500;
        opacity: 1.0;
    }
    hr.new {
  border: 2px solid rgba(216, 104, 12, 0.952);
  border-radius: 10px;
    }
  hr.new1 {
  border: 1px solid rgba(216, 104, 12, 0.952);
  border-radius: 10px; 
}
hr {
    margin-top: 1rem;
    margin-bottom: 1rem;
    border: 0;
    border-top: 1px solid rgba(0,0,0,.1);
}
p.double {border-style: double;}
table {
  border-collapse: collapse;
  width: 100%;
}
td {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  background-color: #F8F8FF;
}
th {
  border: 1px solid #dddddd;
  text-align: left;
  padding: 8px;
  background-color: rgb(31, 73, 125);
  color: #dddddd;
}
tr:nth-child(even) {
  background-color: #dddddd;
}
body {
            position: relative;
            background: #FFFFFF;
        }
.pdfpx {
            position: relative;
            width: 297mm;
           height: 210mm;
            margin: 0 auto;
            color: #001028;
            background: #FFFFFF;
        }
        .block-pdf {
            position: relative;
            padding: 20px;
        }
</style>
@section('content')
<body>
<div id="htmlbody">
    <input type="hidden" id="idcreatelab" value="{{$idcreatelab}}">
    <input type="hidden" id="id" value="{{$id}}">
    <input type="hidden" id="idshowbutton" value="0">
    <input type="hidden" id="showeditalam" name="showeditalam">
    @php
    $ino = 2;
    @endphp
    <div class="pdfpx px1">
                    <p style="text-align:right">
                        <img class="img-fluid" width="130px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="165px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <p style="text-align:center;font-size: 45px;color:rgb(31, 73, 125)">Cleanliness Trend</p>
                    <hr class="new">
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <p style="text-align:left"><img class="img-fluid" width="78%"
                                    src="{{ asset('local/img_g/nhkimg.jpg')}}" alt="Theme-Logo" /></p>
                        </div>
                        <div class="col-6">
                            <p style="font-size: 25px; text-align: right">{{$textdate}}</p>
                            <p style="font-size: 30px; text-align: right">DDS Division</p>
                            <p style="font-size: 30px; text-align: right">NHK Spring (Thailand) Co., Ltd.</p>
                            <table style="font-size: 20px; text-align: right;margin-left: 25px">
                                <tr>
                                    <th style="text-align: center">Approval</th>
                                    <th style="text-align: center">Prepare</th>
                                </tr>
                                <tr>
                                    <td style="text-align: center">{{$textnamepproval}}</td>
                                    <td style="text-align: center">{{$textname}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>



                    <div class="row">
                        <div class="col-8">
                            <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                            <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                        </div>
                        <div class="col-3">
                            <i>
                                <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                                    DDS Motto: Advanced Active Action</p>
                            </i>
                        </div>
                        <div class="col-1">
                            <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                            <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                        </div>
                    </div>
                    <div style="margin-top: -75px" class="row">
                        <div style="text-align: last" class="col-4">
                            <b> TNHK-QA-18-024</b>
                        </div>
                        <div style="text-align: center" class="col-4">
                            <b>
                                <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                            </b>
                        </div>
                        <div style="text-align: right" class="col-4">
                            <b></b>
                        </div>
                    </div>
                <br>
                <br>
    </div>
@foreach ($arrayexport as $item)

    <div class="pdfpx px2">
                    <p style="text-align:right">
                        <img class="img-fluid" width="130px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="165px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                    <br>
                    <br>
                    <br>
                  
                    <p style="text-align:center;font-size: 45px;color:rgb(31, 73, 125)"><span
                            id="titlemodel{{$item['id']}}"></span></p>
                    <hr class="new">
                    <br>
                    <br>
                    <br>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <p style="text-align:left"><img class="img-fluid" width="78%"
                                    src="{{ asset('local/img_g/nhkimg.jpg')}}" alt="Theme-Logo" /></p>
                        </div>
                        <div class="col-6">
                            <p style="font-size: 25px; text-align: right">{{$textdate}}</p>
                            <p style="font-size: 30px; text-align: right">DDS Division</p>
                            <p style="font-size: 30px; text-align: right">NHK Spring (Thailand) Co., Ltd.</p>
                            <table style="font-size: 20px; text-align: right;margin-left: 25px">
                                <tr>
                                    <th style="text-align: center">Approval</th>
                                    <th style="text-align: center">Prepare</th>
                                </tr>
                                <tr>
                                    <td style="text-align: center">{{$textnamepproval}}</td>
                                    <td style="text-align: center">{{$textname}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                            <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                        </div>
                        <div class="col-3">
                            <i>
                                <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                                    DDS Motto: Advanced Active Action</p>
                            </i>
                        </div>
                        <div class="col-1">
                            <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                            <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                        </div>
                    </div>
                    <div style="margin-top: -75px" class="row">
                        <div style="text-align: last" class="col-4">
                            <b> TNHK-QA-18-024</b>
                        </div>
                        <div style="text-align: center" class="col-4">
                            <b>
                                <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                            </b>
                        </div>
                        <div style="text-align: right" class="col-4">
                            @php
                            $no = $ino++;
                            @endphp
                            
                        </div>
                    </div>
    </div>
    <input type="hidden" id="chkshow_lpc" name="chkshow_lpc">
    <input type="hidden" id="chkshow_sem" name="chkshow_sem">
    <input type="hidden" id="chkshow_ic" name="chkshow_ic">
    <input type="hidden" id="chkshow_gcms" name="chkshow_gcms">
    <input type="hidden" id="chkshow_ftir" name="chkshow_ftir">

    <div id="show_lpc">
        <div class=" pdfpx px3">
                        <div style="margin-top: 0px" class="row">
                            <div style="text-align: last;margin-top: 0px" class="col-6">
                                <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> LPC trend</b></i>
                            </div>
                            <div style="text-align: right;margin-top: 0px" class="col-6">
                                <p style="text-align:right">
                                    <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                                        alt="Theme-Logo" />
                                    <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}"
                                        alt="Theme-Logo" /></p>
                            </div>
                        </div>
                        <hr style="margin-top: -2px" class="new">
                        <div style="margin-top: 0px" class="row">
                            <div style="text-align: last;margin-top: -10px" class="col-8">
                                <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                            </div>
                            <div style="text-align: right;margin-top: -10px" class="col-4">
                                <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                            </div>
                        </div>
                        <br>
                        <div id="Chart_line{{$item['id']}}"></div>
                        <div class="row">
                            <div style="font-size: 10px;" class="col-1">
                            </div>
                            <div style="font-size: 10px;margin-left: 10px" class="col-4">
                                <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                                    id="lpctxt1{{$item['id']}}"></span><br>
                                <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                    id="lpctxt2{{$item['id']}}"></span><br>
                                <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt3{{$item['id']}}"></span><br>
                                <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt4{{$item['id']}}"></span><br>
                                <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt5{{$item['id']}}"></span><br>
                                <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt6{{$item['id']}}"></span><br>
                                <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt7{{$item['id']}}"></span><br>
                                <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                                    id="lpctxt8{{$item['id']}}"></span><br>
                                <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                                    id="lpctxt9{{$item['id']}}"></span><br>
                            </div>
                            <div style="font-size: 10px" class="col-1">
                                <b>|</b><br>
                                <b>|</b><br>
                                <b>|</b><br>
                                <b>|</b><br>
                                <b>|</b><br>
                                <b>|</b><br>
                                <b>|</b><br>
                                <b>|</b><br>
                                <b>|</b><br>

                            </div>

                            <div style="font-size: 10px" class="col-4">
                                <div id="showlpchistory{{$item['id']}}">
                                    <b><span style="text-align: center">History table </span></b>
                                    <br>
                                    <table style="font-size: 10px" id="lpchistory{{$item['id']}}">
                                        <thead>
                                            <tr>
                                                <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                                    width="30%">Date</th>
                                                <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                                    width="50%">Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div style="font-size: 10px" class="col-2">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-8">
                                <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                                <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                            </div>
                            <div class="col-3">
                                <i>
                                    <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                                        DDS Motto: Advanced Active Action</p>
                                </i>
                            </div>
                            <div class="col-1">
                                <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                                <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                            </div>
                        </div>
                        <div style="margin-top: -75px" class="row">
                            <div style="text-align: last" class="col-4">
                                <b> TNHK-QA-18-024</b>
                            </div>
                            <div style="text-align: center" class="col-4">
                                <b>
                                    <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                                </b>
                            </div>
                            <div style="text-align: right" class="col-4">
                                @php
                                $no = $ino++;
                                @endphp
                                
                            </div>
                        </div>
        </div>
    </div>
  
    <div id="show_sem">
        <div class=" pdfpx px4">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class1 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass1{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class1txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class1txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class1txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass1history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class1history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px5">

            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class2 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass2{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class2txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class2txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class2txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass2history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class2history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px6">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class3 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass3{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass3history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class3history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px7">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class3.1 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass3_1{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_1txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_1txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_1txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass3_1history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class3_1history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px8">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class3.2 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass3_2{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_2txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_2txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_2txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass3_2history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class3_2history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px9">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class3.3 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass3_3{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_3txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_3txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class3_3txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass3_3history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class3_3history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px10">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class4 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass4{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class4txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class4txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class4txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass4history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class4history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class="pdfpx px11">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class5 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass5{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class5txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class5txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class5txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass5history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class5history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px12">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class5.1 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass5_1{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class5_1txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class5_1txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class5_1txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass5_1history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class5_1history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px13">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> SEM Class6 trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineclass6{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class6txt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class6txt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="class6txt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showclass6history{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="class6history{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
    </div>

    <div id="show_ic">
        <div class=" pdfpx px14">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> IC Anion trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineanion{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="aniontxt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showanionhistory{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="anionhistory{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px15">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> IC Sulphat trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_linesulphat{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphattxt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="sulphattxt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphattxt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphattxt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphattxt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphattxt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphattxt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="sulphattxt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="sulphattxt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showsulphathistory{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="sulphathistory{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px16">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> IC Cation trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_linecation{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="cationtxt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="cationtxt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="cationtxt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showcationhistory{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="cationhistory{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
    </div>


    <div id="show_gcms">
        <div class=" pdfpx px17">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> GCMS Outgassing trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineoutgassing{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="outgassingtxt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="outgassingtxt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="outgassingtxt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="outgassingtxt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showoutgassinghistory{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="outgassinghistory{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px18">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> GCMS Smear trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_linesmear{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="smeartxt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showsmearhistory{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="smearhistory{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px19">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> GCMS Low_mw trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_linelow_mw{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="low_mwtxt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="low_mwtxt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="low_mwtxt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showlow_mwhistory{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="low_mwhistory{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
        <div class=" pdfpx px20">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> GCMS High_mw trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_linehigh_mw{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="high_mwtxt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="high_mwtxt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="high_mwtxt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showhigh_mwhistory{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="high_mwhistory{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
    </div>


    <div id="show_ftir">
        <div class=" pdfpx px21">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: 0px" class="col-6">
                    <i><b style="font-size: 30px;color:rgb(31, 73, 125)"> FTIR trend</b></i>
                </div>
                <div style="text-align: right;margin-top: 0px" class="col-6">
                    <p style="text-align:right">
                        <img class="img-fluid" width="80px" src="{{ asset('local/img_g/logopdf.png')}}"
                            alt="Theme-Logo" />
                        <img class="img-fluid" width="100px" src="{{ asset('local/img_g/dds.png')}}" alt="Theme-Logo" />
                    </p>
                </div>
            </div>
            <hr style="margin-top: -2px" class="new">
            <div style="margin-top: 0px" class="row">
                <div style="text-align: last;margin-top: -10px" class="col-8">
                    <b style="font-size: 23px"><span class="titlemodellist{{$item['id']}}"></span> </b>
                </div>
                <div style="text-align: right;margin-top: -10px" class="col-4">
                    <b style="font-size: 23px"><span class="titlpart{{$item['id']}}"></span></b>
                </div>
            </div>
            <br>
            <div id="Chart_lineftir{{$item['id']}}"></div>
            <div class="row">
                <div style="font-size: 10px;" class="col-1">
                </div>
                <div style="font-size: 10px;margin-left: 10px" class="col-4">
                    <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt1{{$item['id']}}"></span><br>
                    <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span
                        id="ftirtxt2{{$item['id']}}"></span><br>
                    <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt3{{$item['id']}}"></span><br>
                    <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt4{{$item['id']}}"></span><br>
                    <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt5{{$item['id']}}"></span><br>
                    <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt6{{$item['id']}}"></span><br>
                    <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt7{{$item['id']}}"></span><br>
                    <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt8{{$item['id']}}"></span><br>
                    <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt9{{$item['id']}}"></span><br>
                </div>
                <div style="font-size: 10px" class="col-1">
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>
                    <b>|</b><br>

                </div>

                <div style="font-size: 10px" class="col-4">
                    <div id="showftirhistory{{$item['id']}}">
                        <b><span style="text-align: center">History table </span></b>
                        <br>
                        <table style="font-size: 10px" id="ftirhistory{{$item['id']}}">
                            <thead>
                                <tr>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="30%">Date</th>
                                    <th style="padding:3px;text-align: center;background-color: #F8F8FF;color: #000000;"
                                        width="50%">Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="font-size: 10px" class="col-2">
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-8">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
                <div class="col-3">
                    <i>
                        <p style="font-size: 15px;margin-top:-10px;margin-bottom:80px;color: rgb(28, 32, 230);">
                            DDS Motto: Advanced Active Action</p>
                    </i>
                </div>
                <div class="col-1">
                    <hr style="margin-top: 0px;margin-bottom:0px;" class="new1">
                    <hr style="margin-top: 2px;margin-bottom:2px;" class="new1">
                </div>
            </div>
            <div style="margin-top: -75px" class="row">
                <div style="text-align: last" class="col-4">
                    <b> TNHK-QA-18-024</b>
                </div>
                <div style="text-align: center" class="col-4">
                    <b>
                        <p style="font-size: 15px;color:red;">TNHK CONFIDENTIAL</p>
                    </b>
                </div>
                <div style="text-align: right" class="col-4">
                    @php
                    $no = $ino++;
                    @endphp
                    
                </div>
            </div>
        </div>
    </div>

</span>

@endforeach
<input type="hidden" id="namemodel" value="{{$namemodel}}">
<input type="hidden" id="datenow" value="{{$datenow}}">
</div>
<center><a onclick="report()"><button type="button"
    class="btn btn-success btn-sm">&nbsp;&nbsp;<i
        class="fa fa-file-excel-o"></i> Export PDF&nbsp;&nbsp;</button></a></center>
</body>
<br>
<br>
<br>
@endsection

@section('scripts')
<script>
    var idcreatelab = $('#idcreatelab').val();
    $(document).ready(function (e) {
       
        var dataexport = "{{$dataexport}}";
        var jsondataexport = JSON.parse(dataexport.replace(/&quot;/g,'"'));
       
        $.each(jsondataexport, function (v, itemjs) {
            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 1, 
                    'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showlpchistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#lpchistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass1history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class1history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            
            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass2history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class2history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass3history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class3history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass3_1history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class3_1history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass3_2history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class3_2history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass4history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class4history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass3_3history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class3_3history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });


            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass5history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class5history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass5_1history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class5_1history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 2,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showclass6history" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#class6history' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });
            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 3,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showanionhistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#anionhistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });
            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 3,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showsulphathistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#sulphathistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 3,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showcationhistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#cationhistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 4,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showoutgassinghistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#outgassinghistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 4,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showsmearhistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#smearhistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 4,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showlow_mwhistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#low_mwhistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 4,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showhigh_mwhistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#high_mwhistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });

            $.ajax({
                type: 'get',
                url: "{{ url('pdfhistoryshow') }}",
                data: {
                    'modelid': itemjs.id,
                    'id_typedata': 5,
                     'chk': 1
                },
                success: function (data) {
                    
                    if (data.success == 1) {
                        var lpchistory = '';
                        $.each(data.history, function (v, item) {
                            lpchistory += '<tr>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + Getdate(item.history_date) + '</td>' +
                                '<td style="padding:2px;background-color: #FFFFFF;color: #000000;">' + item.history_detail + '</td>' +
                                '</tr>';
                        });
                        if (data.history.length == 0) {
                            var mydivhistory = document.getElementById("showftirhistory" + itemjs.id);
                            mydivhistory.style.display = "none";
                        } else {
                            $('#ftirhistory' + itemjs.id + ' tbody').html(lpchistory);
                        }

                    } else {

                    }
                }
            });
           
        var idcreatelab = $("#idcreatelab").val();
        var id = $("#id").val();
        $.ajax({
            type: 'get',
            url: "{{ url('outputgraph') }}",
            data: {
                id: id,
                idcreatelab: idcreatelab,
                modelid: itemjs.id,
                datestart:itemjs.datestart,
                dateend:itemjs.dateend,
                lpccount:itemjs.lpccount,
                semcount:itemjs.semcount,
                iccount:itemjs.iccount,
                gcmscount:itemjs.gcmscount,
                ftircount:itemjs.ftircount,
                chkpdf:1,
            },
            success: function (data) {
                var heightimg = '364';
                var widthimg =  '1000';
                $('#titlemodel'+itemjs.id).html(data.modelname.name_model+' ('+data.modelname.drive_name+') Cleanliness ('+data.modelname.part+')');
                $('.titlemodellist'+itemjs.id).html(data.modelname.drive_name+'/'+data.modelname.name_model+' (part no. '+data.modelname.part_number+')');
                $('.titlpart'+itemjs.id).html('('+data.modelname.part+')');
                var sumalertall = 0;
                var test1all = 0;
                var test2all = 0;
                var test3all = 0;
                var test4all = 0;
                var test5all = 0;
                var test6all = 0;
                //-------------------------------- lpc --------------------------//
                if (data.chkoqadate == 1) {
                var chklpcall = 0;
                var array = [10, 20, 30, 40, 50];
                var series = [],
                    animals = [],
                    datalab = [],
                    datest = [],
                    len = data.model.length,
                    lenvs = data.datalaball.length,
                    i = 0;
                ckvs = 0;
                vs = 0;
                var sumvs2 = lenvs - data.alarmtest2;
                var sumvs3 = lenvs -  data.alarmtest3y;
                if(data.model[2].alpc =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].alpc == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].alpc =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].alpc == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].alpc =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].alpc == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                data.datalaball.reverse();
                data.newlpcdatalab.reverse();
                var lpc1 = '';
                var lpc2 = '';
                var lpc3 = '';
                var lpc4 = '';
                var lpc5 = '';
                var lpc6 = '';
                var itemdata = data.datalab.length-1;
                var itemdata1 = data.newlpcdatalab.length-1;
               // --------------------------- TEST2 ------------------------------------- //
                $('#lpctxt1'+itemjs.id).html(Getdate(data.modelname.alpc_dc));
                $('#lpctxt2'+itemjs.id).html(data.modelname.alpc_dr);
                $('#lpctxt3'+itemjs.id).html(data.modelname.part_number);
                $('#lpctxt4'+itemjs.id).html(data.modelname.name_model);
                $('#lpctxt5'+itemjs.id).html(data.modelname.drive_name);
                $('#lpctxt6'+itemjs.id).html(data.model[0].alpc);
                $('#lpctxt7'+itemjs.id).html(data.newlpcdatalab[itemdata1][0].alpc);
                $('#lpctxt8'+itemjs.id).html(data.newlpcdatalab[itemdata1][1].alpc);
                $('#lpctxt9'+itemjs.id).html(data.newlpcdatalab[itemdata1][2].alpc);
                $('#textlot_number').html(data.oqalot.lotnumber_lab);
                
                var test7 = data.datalaball.length - data.alarmtest3;
                var chklpcucl = 0;
                if (data.higher >= data.alarmtest2) {
                    lpc1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                    sumalertall++;
                    test2all++;
                    chklpcall++;

                } else if (data.below >= data.alarmtest2) {
                    lpc1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                    sumalertall++;
                    test2all++;
                    chklpcall++;
                } else {
                    lpc1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                // ------------------------- END TEST2 -------------------------------------//
                // ------------------------- TEST3 -------------------------------------//
                if (data.highervs3 >= data.alarmtest3) {
                    lpc2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                    sumalertall++;
                    test3all++;
                    chklpcall++;
                } else if (data.belowvs3 >= data.alarmtest3) {
                    lpc2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                    sumalertall++;
                    test3all++;
                    chklpcall++;
                } else {
                    lpc2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                // -------------------------END  TEST3 -------------------------------------//
                // ------------------------- TEST1 -------------------------------------//
                if (parseFloat(data.datalab[itemdata].lpc_data) < parseFloat(data.lcl.alpc)) {
                    if(nalcltest2 == 1){
                    lpc3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                    sumalertall++;
                    test1all++;
                    chklpcall++;
                    }
                    else
                    {
                        lpc3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    //alert(1);
                } else if (parseFloat(data.datalab[itemdata].lpc_data) > parseFloat(data.ucl.alpc)) {
                    if(naucltest2 == 1){
                        lpc3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test1all++;
                        chklpcall++;
                        chklpcucl++;
                    }
                    else
                    {
                        lpc3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                     
                } else {
                    lpc3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                // ------------------------- END TEST1 -------------------------------------//
                // -------------------------TEST4 -------------------------------------//
                if (data.datalab[itemdata].lpc_data > data.is.alpc) {
                    if(naistest4 == 1){
                        lpc4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test4all++;
                        chklpcall++;
                    }
                    else
                    {
                        lpc4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                } else {
                    lpc4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                $('#showeditalam').val(chklpcucl);
                // ------------------------- END TEST4 -------------------------------------//
                $.each(data.datalaball, function (k, v) {
                    var textvalue = '';
                    ckvs++;
                    if (data.higher >= data.alarmtest2) {

                        if (ckvs > sumvs2) {
                         
                            textvalue = {
                                y: v.lpc_data,
                                color: 'green'
                            };
                     
                        } else {
                            textvalue = v.lpc_data;
                        }
                    } else if (data.below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                        
                            textvalue = {
                                y: v.lpc_data,
                                color: 'green'
                            };
                            
                        } else {
                            textvalue = v.lpc_data;
                        }
                    } else {
                        if (data.highervs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if(ckvs == test7)
                            {
                                textvalue = v.lpc_data;
                            }
                            else{
                                textvalue = {
                                    y: v.lpc_data,
                                    color: 'green'
                                };
                            }
                            } else {
                                textvalue = v.lpc_data;
                            }

                        } else if (data.belowvs3 >= data.alarmtest3) {
                            if (ckvs > sumvs3) {
                                if(ckvs == test7)
                            {
                                textvalue = v.lpc_data;
                            }
                            else{
                                textvalue = {
                                    y: v.lpc_data,
                                    color: 'green'
                                };
                            }
                            } else {
                                textvalue = v.lpc_data;
                            }
                        } else {
                            if (v.lpc_data < data.lcl.alpc) {
                                if(nalcltest2 == 1)
                                {
                                textvalue = {
                                    y: v.lpc_data,
                                    color: 'green'
                                };
                                }
                                else
                                {
                                    textvalue = v.lpc_data;
                                }
                                
                            } else if (v.lpc_data > data.ucl.alpc) {
                                if(naucltest2 == 1)
                                {
                                textvalue = {
                                    y: v.lpc_data,
                                    color: 'green',
                                };
                                }
                                else
                                {
                                    textvalue = v.lpc_data;
                                }
                            } else {
                                
                                if (v.lpc_data > data.is.alpc) {
                                    if(naistest4 == 1)
                                    {
                                    textvalue = {
                                        y: v.lpc_data,
                                        color: 'green',
                                    };
                                    }
                                    else
                                    {
                                        textvalue = v.lpc_data;
                                    }
                                }
                                 else {
                                    textvalue = v.lpc_data;
                                }
                            }
                        }
                    }
                    datalab[vs] = textvalue;
                    var fmdate = Getdate(v.date_lpc);    
                    datest[vs] = fmdate+' '+v.lotnumber_lab;
                    vs++;
                });
                $.each(data.model, function (k, v) {
                    i++;
                    if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.alpc));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newlpcdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].alpc));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newlpcdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].alpc));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newlpcdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].alpc));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newlpcdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].alpc));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.alpc));
                            }

                        }
                    var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {

                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }




                        
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                
                var chartdata = []
                Highcharts.chart('Chart_line' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                );
                $('#chkshow_lpc').val(1);
                } else {
                    document.getElementById("show_lpc").style.display = "none";
                    $('#chkshow_lpc').val(0);
                }
                //--------------------------------end lpc --------------------------//
                // alert(data.chkgcmsshow);
                //-------------------------------- sem class1 --------------------------//
                 if (data.chkoqadatesem == 1) {
                 var chksemclass1all = 0;
                    var semclass1_1 = '';
                    var semclass1_2 = '';
                    var semclass1_3 = '';
                    var semclass1_4 = '';
                    data.semdatalaball.reverse();
                    data.semdatalab.reverse();
                    data.newsemdatalab.reverse();
                    var itemdata = data.semdatalab.length-1;
                    var itemdata1 = data.newsemdatalab.length-1;
                    $('#class1txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
                    $('#class1txt2'+itemjs.id).html(data.modelname.sem_dr);
                    $('#class1txt3'+itemjs.id).html(data.modelname.part_number);
                    $('#class1txt4'+itemjs.id).html(data.modelname.name_model);
                    $('#class1txt5'+itemjs.id).html(data.modelname.drive_name);
                    $('#class1txt6'+itemjs.id).html(data.model[0].class1);
                    $('#class1txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class1);
                    $('#class1txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class1);
                    $('#class1txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class1);
                    var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class1 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class1 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class1 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class1 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class1 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class1 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
    
                    var showv6 = data.semdatalaball.length-data.alarmtest3y;
                    if (data.sem1higher >= data.alarmtest2) {
                        semclass1_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test2all++;
                        chksemclass1all++;
                    } else if (data.sem1below >= data.alarmtest2) {
                        semclass1_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test2all++;
                        chksemclass1all++;
                    } else {
                        semclass1_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.sem1highervs3 >= data.alarmtest3) {
                        semclass1_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test3all++;
                        chksemclass1all++;
                    } else if (data.sem1belowvs3 >= data.alarmtest3) {
                        semclass1_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test3all++;
                        chksemclass1all++;
                    } else {
                        semclass1_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class1 < data.lcl.class1) {
                        if(nalcltest2 == 1){
                            semclass1_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass1all++;
                        }
                        else
                        {
                            semclass1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
              
                    } else if (data.semdatalab[itemdata].sem_class1 > data.ucl.class1) {
       
                        if(naucltest2 == 1){
                        semclass1_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test1all++;
                        chksemclass1all++;
                        //chkclass1all++;
                        }
                        else
                        {
                            semclass1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    } else {
                        semclass1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.semdatalab[itemdata].sem_class1 > data.is.class1) {

                        if(naistest4 == 1){
                            semclass1_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass1all++;
                        }
                        else
                        {
                            semclass1_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    } else {
                        semclass1_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        datest = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    var test7 = data.semdatalaball.length - data.alarmtest3;
                
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;
                       // console.log(v.sem_class1);
                        if (data.sem1higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class1,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class1;
                            }
                        } else if (data.sem1below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class1,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class1;
                            }
                        } else {
                            if (data.sem1highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class1;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class1;
                                }

                            } else if (data.sem1belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class1;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class1;
                                }
                            } else {
                                if (v.sem_class1 < data.lcl.class1) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class1;
                                }
                       
                                } else if (v.sem_class1 > data.ucl.class1) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class1;
                                }
                               
                                } else {
                                    if (v.sem_class1 > data.is.class1) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class1,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class1;
                                    }
                               
                                    } else {
                                        textvalue = v.sem_class1;
                                    }
                                }

                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                    i++;
                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class1));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class1));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class1));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class1));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class1));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class1));
                            }

                        }
                    var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        enableMouseTracking: false,
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                    Highcharts.chart('Chart_lineclass1' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //-------------------------------- end sem class1 --------------------------//

                    
                    //-------------------------------- sem class2 --------------------------//
                    var chksemclass2all = 0;
                    var semclass2_1 = '';
                    var semclass2_2 = '';
                    var semclass2_3 = '';
                    var semclass2_4 = '';
                    $('#class2txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
                    $('#class2txt2'+itemjs.id).html(data.modelname.sem_dr);
                    $('#class2txt3'+itemjs.id).html(data.modelname.part_number);
                    $('#class2txt4'+itemjs.id).html(data.modelname.name_model);
                    $('#class2txt5'+itemjs.id).html(data.modelname.drive_name);
                    $('#class2txt6'+itemjs.id).html(data.model[0].class2);
                    $('#class2txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class2);
                    $('#class2txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class2);
                    $('#class2txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class2);
                    var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class2 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class2 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class2 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class2 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class2 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class2 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sem2higher >= data.alarmtest2) {
                        semclass2_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test2all++;
                        chksemclass2all++;
                    } else if (data.sem2below >= data.alarmtest2) {
                        semclass2_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                         test2all++;
                        chksemclass2all++;
                    } else {
                        semclass2_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.sem2highervs3 >= data.alarmtest3) {
                        semclass2_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass2all++;
                    } else if (data.sem2belowvs3 >= data.alarmtest3) {
                        semclass2_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass2all++;
                    } else {
                        semclass2_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class2 < data.lcl.class2) {
                        if(nalcltest2 == 1){
                            semclass2_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass2all++;
                        }
                        else
                        {
                            semclass2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    } else if (data.semdatalab[itemdata].sem_class2 > data.ucl.class2) {
                        if(naucltest2 == 1){
                            semclass2_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass2all++;
                        }
                        else
                        {
                            semclass2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    } else {
                        semclass2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class2 > data.is.class2) {
                        if (naistest4 == 1) {
                            semclass2_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test4all++;
                        chksemclass2all++;
                       } else {
                        semclass2_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                  
                    } else {
                        semclass2_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;
                        if (data.sem2higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class2,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class2;
                            }
                        } else if (data.sem2below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class2,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class2;
                            }
                        } else {
                            if (data.sem2highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class2;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class2;
                                }

                            } else if (data.sem2belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class2;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class2;
                                }
                            } else {
                                if (v.sem_class2 < data.lcl.class2) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class2;
                                }

                                } else if (v.sem_class2 > data.ucl.class2) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class2;
                                }

                                } else {
                                    if (v.sem_class2 > data.is.class2) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class2,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class2;
                                    }
                
                                    } else {
                                        textvalue = v.sem_class2;
                                    }
                                }
                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;
                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class2));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class2));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class2));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class2));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class2));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class2));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },

                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass2' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series
                    }
                    );

                    //-------------------------------- end sem class2 --------------------------//

                      //-------------------------------- sem class3 --------------------------//

                      var chksemclass3all = 0;
                    var semclass3_1 = '';
                    var semclass3_2 = '';
                    var semclass3_3 = '';
                    var semclass3_4 = '';
                    $('#class3txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
                    $('#class3txt2'+itemjs.id).html(data.modelname.sem_dr);
                    $('#class3txt3'+itemjs.id).html(data.modelname.part_number);
                    $('#class3txt4'+itemjs.id).html(data.modelname.name_model);
                    $('#class3txt5'+itemjs.id).html(data.modelname.drive_name);
                    $('#class3txt6'+itemjs.id).html(data.model[0].class3);
                    $('#class3txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class3);
                    $('#class3txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class3);
                    $('#class3txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class3);
                    var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class3 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class3 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class3 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class3 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class3 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class3 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sem3higher >= data.alarmtest2) {
                        semclass3_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test2all++
                        chksemclass3all++;
                    } else if (data.sem3below >= data.alarmtest2) {
                        semclass3_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test2all++
                        chksemclass3all++;
                    } else {
                        semclass3_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem3highervs3 >= data.alarmtest3) {
                        semclass3_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++
                        chksemclass3all++;
                    } else if (data.sem3belowvs3 >= data.alarmtest3) {
                        semclass3_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++
                        chksemclass3all++;
                    } else {
                        semclass3_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class3 < data.lcl.class3) {
                        if (nalcltest2 == 1) {
                            semclass3_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++
                        chksemclass3all++;
                       } else {
                        semclass3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                   
                    } else if (data.semdatalab[itemdata].sem_class3 > data.ucl.class3) {
                        if(naucltest2 == 1){
                            semclass3_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++
                        chksemclass3all++;
                        }
                        else
                        {
                            semclass3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        semclass3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.semdatalab[itemdata].sem_class3 > data.is.class3) {
                        if (naistest4 == 1) {
                            semclass3_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test4all++
                        chksemclass3all++;
                       } else {
                        semclass3_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }


                    } else {
                        semclass3_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;
                        if (data.sem3higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class3,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class3;
                            }
                        } else if (data.sem3below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class3,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class3;
                            }
                        } else {
                            if (data.sem3highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class3;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class3;
                                }
                            } else if (data.sem3belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class3;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class3;
                                }
                            } else {
                                if (v.sem_class3 < data.lcl.class3) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class3;
                                }
                             
                                } else if (v.sem_class3 > data.ucl.class3) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class3;
                                }
                                } else {
                                    if (v.sem_class3 > data.is.class3) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class3;
                                    }
                      
                                    } else {
                                        textvalue = v.sem_class3;
                                    }
                                }
                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);       
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class3));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class3));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class3));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class3));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class3));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class3));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass3' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //-------------------------------- end sem class3 --------------------------//

                     //-------------------------------- sem class3_1 --------------------------//
                     var chksemclass31all = 0;
                    var semclass31_1 = '';
                    var semclass31_2 = '';
                    var semclass31_3 = '';
                    var semclass31_4 = '';
                    $('#class3_1txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
                    $('#class3_1txt2'+itemjs.id).html(data.modelname.sem_dr);
                    $('#class3_1txt3'+itemjs.id).html(data.modelname.part_number);
                    $('#class3_1txt4'+itemjs.id).html(data.modelname.name_model);
                    $('#class3_1txt5'+itemjs.id).html(data.modelname.drive_name);
                    $('#class3_1txt6'+itemjs.id).html(data.model[0].class3_1);
                    $('#class3_1txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class3_1);
                    $('#class3_1txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class3_1);
                    $('#class3_1txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class3_1);
                    var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class3_1 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class3_1 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class3_1 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class3_1 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class3_1 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class3_1 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sem31higher >= data.alarmtest2) {
                        semclass31_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                         chksemclass31all++;
                    } else if (data.sem31below >= data.alarmtest2) {
                        semclass31_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                         chksemclass31all++;
                    } else {
                        semclass31_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem31highervs3 >= data.alarmtest3) {
                         semclass31_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                         chksemclass31all++;
                    } else if (data.sem31belowvs3 >= data.alarmtest3) {
                        semclass31_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass31all++;
                    } else {
                        semclass31_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class3_1 < data.lcl.class3_1) {
                        if (nalcltest2 == 1) {
                            semclass31_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass31all++;
                       } else {
                        semclass31_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.semdatalab[itemdata].sem_class3_1 > data.ucl.class3_1) {
                        
                       if(naucltest2 == 1){
                        semclass31_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                         chksemclass31all++;
                        }
                        else
                        {
                            semclass31_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        semclass31_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.semdatalab[itemdata].sem_class3_1 > data.is.class3_1) {
                        if (naistest4 == 1) {
                            semclass31_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                        chksemclass31all++;
                       } else {
                        semclass31_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }


                    } else {
                        semclass31_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.sem31higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class3_1,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class3_1;
                            }
                        } else if (data.sem31below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class3_1,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class3_1;
                            }
                        } else {
                            if (data.sem31highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class3_1;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class3_1;
                                }

                            } else if (data.sem31belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class3_1;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class3_1;
                                }
                            } else {
                                if (v.sem_class3_1 < data.lcl.class3_1) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class3_1;
                                }

                                } else if (v.sem_class3_1 > data.ucl.class3_1) {
                                    
                                if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class3_1;
                                }
                             
                                } else {
                                    if (v.sem_class3_1 > data.is.class3_1) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_1,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class3_1;
                                    }
                                   
                                    } else {
                                        textvalue = v.sem_class3_1;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);       
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class3_1));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class3_1));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class3_1));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class3_1));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class3_1));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class3_1));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass3_1' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //-------------------------------- end sem class3_1 --------------------------//

                    //-------------------------------- sem class3_2 --------------------------//
                    var chksemclass32all = 0;
                    var semclass32_1 = '';
                    var semclass32_2 = '';
                    var semclass32_3 = '';
                    var semclass32_4 = '';
                    $('#class3_2txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
                    $('#class3_2txt2'+itemjs.id).html(data.modelname.sem_dr);
                    $('#class3_2txt3'+itemjs.id).html(data.modelname.part_number);
                    $('#class3_2txt4'+itemjs.id).html(data.modelname.name_model);
                    $('#class3_2txt5'+itemjs.id).html(data.modelname.drive_name);
                    $('#class3_2txt6'+itemjs.id).html(data.model[0].class3_2);
                    $('#class3_2txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class3_2);
                    $('#class3_2txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class3_2);
                    $('#class3_2txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class3_2);
                    var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class3_2 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class3_2 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class3_2 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class3_2 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class3_2 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class3_2 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sem32higher >= data.alarmtest2) {
                        semclass32_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass32all++;
                    } else if (data.sem32below >= data.alarmtest2) {
                        semclass32_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass32all++;
                    } else {
                        semclass32_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem32highervs3 >= data.alarmtest3) {
                        semclass32_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass32all++;
                    } else if (data.sem32belowvs3 >= data.alarmtest3) {
                        semclass32_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass32all++;
                    } else {
                        semclass32_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class3_2 < data.lcl.class3_2) {
                        if (nalcltest2 == 1) {
                            semclass32_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass32all++;
                       } else {
                        semclass32_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.semdatalab[itemdata].sem_class3_2 > data.ucl.class3_2) {
                        if(naucltest2 == 1){
                            semclass32_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass32all++;
                        }
                        else
                        {
                            semclass32_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        semclass32_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class3_2 > data.is.class3_2) {
                        if (naistest4 == 1) {
                            semclass32_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chksemclass32all++;
                       } else {
                        semclass32_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }


                    } else {
                        semclass32_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.sem32higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class3_2,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class3_2;
                            }
                        } else if (data.sem32below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class3_2,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class3_2;
                            }
                        } else {
                            if (data.sem32highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class3_2;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class3_2;
                                }

                            } else if (data.sem32belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class3_2;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class3_2;
                                }
                            } else {
                                if (v.sem_class3_2 < data.lcl.class3_2) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class3_2;
                                }
                            
                                } else if (v.sem_class3_2 > data.ucl.class3_2) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class3_2;
                                }
                            
                                } else {
                                    if (v.sem_class3_2 > data.is.class3_2) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_2,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class3_2;
                                    }
                       
                                    } else {
                                        textvalue = v.sem_class3_2;
                                    }
                                }
                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);       
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class3_2));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class3_2));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class3_2));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class3_2));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class3_2));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class3_2));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass3_2' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //-------------------------------- end sem class3_2 --------------------------//


                    //-------------------------------- end sem class3_3 --------------------------//
                    var chksemclass33all = 0;
                    var semclass33_1 = '';
                    var semclass33_2 = '';
                    var semclass33_3 = '';
                    var semclass33_4 = '';
                    $('#class3_3txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
                    $('#class3_3txt2'+itemjs.id).html(data.modelname.sem_dr);
                    $('#class3_3txt3'+itemjs.id).html(data.modelname.part_number);
                    $('#class3_3txt4'+itemjs.id).html(data.modelname.name_model);
                    $('#class3_3txt5'+itemjs.id).html(data.modelname.drive_name);
                    $('#class3_3txt6'+itemjs.id).html(data.model[0].class3_3);
                    $('#class3_3txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class3_3);
                    $('#class3_3txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class3_3);
                    $('#class3_3txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class3_3);
                    var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class3_3 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class3_3 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class3_3 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class3_3 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class3_3 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class3_3 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sem33higher >= data.alarmtest2) {
                        semclass33_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass33all++;
                    } else if (data.sem33below >= data.alarmtest2) {
                        semclass33_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass33all++;
                    } else {
                        semclass33_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem33highervs3 >= data.alarmtest3) {
                        semclass33_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass33all++;
                    } else if (data.sem33belowvs3 >= data.alarmtest3) {
                        semclass33_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                        chksemclass33all++;
                    } else {
                        semclass33_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class3_3 < data.lcl.class3_3) {
                        if (nalcltest2 == 1) {
                            semclass33_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass33all++;
                       } else {
                        semclass33_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.semdatalab[itemdata].sem_class3_3 > data.ucl.class3_3) {
                        if(naucltest2 == 1){
                            semclass33_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        test1all++;
                        sumalertall++;
                        chksemclass33all++;
                        }
                        else
                        {
                            semclass33_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        semclass33_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.semdatalab[itemdata].sem_class3_3 > data.is.class3_3) {
                        if (naistest4 == 1) {
                            semclass33_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chksemclass33all++;
                       } else {
                        semclass33_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else {
                        semclass33_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.sem33higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class3_3,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class3_3;
                            }
                        } else if (data.sem33below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class3_3,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class3_3;
                            }
                        } else {
                            if (data.sem33highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class3_3;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class3_3;
                                }

                            } else if (data.sem33belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class3_3;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class3_3;
                                }
                            } else {
                                if (v.sem_class3_3 < data.lcl.class3_3) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class3_3;
                                }

                                } else if (v.sem_class3_3 > data.ucl.class3_3) {
                                    
                                if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class3_3;
                                }

                                } else {
                                    if (v.sem_class3_3 > data.is.class3_3) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_3,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class3_3;
                                    }
                                
                                    } else {
                                        textvalue = v.sem_class3_3;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);       
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class3_3));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class3_3));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class3_3));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class3_3));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class3_3));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class3_3));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass3_3' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //-------------------------------- end sem class3_3 --------------------------//

                    
                    //--------------------------------  sem class4 --------------------------//
                    var chksemclass4all = 0;
                    var semclass4_1 = '';
                    var semclass4_2 = '';
                    var semclass4_3 = '';
                    var semclass4_4 = '';
                    $('#class4txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
            $('#class4txt2'+itemjs.id).html(data.modelname.sem_dr);
            $('#class4txt3'+itemjs.id).html(data.modelname.part_number);
            $('#class4txt4'+itemjs.id).html(data.modelname.name_model);
            $('#class4txt5'+itemjs.id).html(data.modelname.drive_name);
            $('#class4txt6'+itemjs.id).html(data.model[0].class4);
            $('#class4txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class4);
            $('#class4txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class4);
            $('#class4txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class4);
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class4 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class4 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class4 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class4 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class4 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class4 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sem4higher >= data.alarmtest2) {
                        semclass4_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                       chksemclass4all++;
                    } else if (data.sem4below >= data.alarmtest2) {
                        semclass4_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                       chksemclass4all++;
                    } else {
                        semclass4_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem4highervs3 >= data.alarmtest3) {
                        semclass4_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                       chksemclass4all++;
                    } else if (data.sem4belowvs3 >= data.alarmtest3) {
                        semclass4_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                       chksemclass4all++;
                    } else {
                        semclass4_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class4 < data.lcl.class4) {
                        if (nalcltest2 == 1) {
                            semclass4_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                       chksemclass4all++;
                       } else {
                        semclass4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else if (data.semdatalab[itemdata].sem_class4 > data.ucl.class4) {
                        if(naucltest2 == 1){
                            semclass4_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                       chksemclass4all++;
                        }
                        else
                        {
                            semclass4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    } else {
                        semclass4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.semdatalab[itemdata].sem_class4 > data.is.class4) {
                        if (naistest4 == 1) {
                            semclass4_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chksemclass4all++;
                       } else {
                        semclass4_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else {
                        semclass4_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.sem4higher >= data.alarmtest2) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.sem_class4,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class4;
                            }
                        } else if (data.sem4below >= data.alarmtest2) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.sem_class4,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class4;
                            }
                        } else {
                            if (data.sem4highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class4;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class4;
                                }

                            } else if (data.sem4belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class4;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class4;
                                }
                            } else {
                                if (v.sem_class4 < data.lcl.class4) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class4;
                                }
                                
                                } else if (v.sem_class4 > data.ucl.class4) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class4;
                                }
                            
                                } else {
                                    if (v.sem_class4 > data.is.class4) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class4,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class4;
                                    }
                               
                                    } else {
                                        textvalue = v.sem_class4;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);       
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class4));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class4));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class4));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class4));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class4));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class4));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass4' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }
                    );
                    //-------------------------------- end sem class4 --------------------------//

                    //--------------------------------  sem class5 --------------------------//
                    var chksemclass5all = 0;
                    var semclass5_1 = '';
                    var semclass5_2 = '';
                    var semclass5_3 = '';
                    var semclass5_4 = '';
                    $('#class5txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
            $('#class5txt2'+itemjs.id).html(data.modelname.sem_dr);
            $('#class5txt3'+itemjs.id).html(data.modelname.part_number);
            $('#class5txt4'+itemjs.id).html(data.modelname.name_model);
            $('#class5txt5'+itemjs.id).html(data.modelname.drive_name);
            $('#class5txt6'+itemjs.id).html(data.model[0].class5);
            $('#class5txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class5);
            $('#class5txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class5);
            $('#class5txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class5);

            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class5 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class5 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class5 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class5 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class5 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class5 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }

                    if (data.sem5higher >= data.alarmtest2) {
                        semclass5_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass5all++;
                    } else if (data.sem5below >= data.alarmtest2) {
                        semclass5_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass5all++;
                    } else {
                        semclass5_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem5highervs3 >= data.alarmtest3) {
                        semclass5_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass5all++;
                    } else if (data.sem5belowvs3 >= data.alarmtest3) {
                        semclass5_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass5all++;
                    } else {
                        semclass5_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class5 < data.lcl.class5) {
                        if (nalcltest2 == 1) {
                            semclass5_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass5all++;
                       } else {
                        semclass5_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else if (data.semdatalab[itemdata].sem_class5 > data.ucl.class5) {
                        if(naucltest2 == 1){
                            semclass5_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass5all++;
                        }
                        else
                        {
                            semclass5_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    } else {
                        semclass5_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.semdatalab[itemdata].sem_class5 > data.is.class5) {
                        if (naistest4 == 1) {
                            semclass5_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                        sumalertall++;
                        test4all++;
                        chksemclass5all++;
                       } else {
                        semclass5_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else {
                        semclass5_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.sem5higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class5,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class5;
                            }
                        } else if (data.sem5below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class5,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class5;
                            }
                        } else {
                            if (data.sem5highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class5;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class5;
                                }

                            } else if (data.sem5belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class5;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class5;
                                }
                            } else {
                                if (v.sem_class5 < data.lcl.class5) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class5;
                                }
                                } else if (v.sem_class5 > data.ucl.class5) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class5;
                                }
                                } else {
                                    if (v.sem_class5 > data.is.class5) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class5,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class5;
                                    }
                               
                                    } else {
                                        textvalue = v.sem_class5;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);       
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class5));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class5));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class5));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class5));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class5));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class5));
                            }
                        }
                        
                     var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass5' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //-------------------------------- end sem class5 --------------------------//

                    //-------------------------------- sem class5_1 --------------------------//
                    var chksemclass51all = 0;
                    var semclass51_1 = '';
                    var semclass51_2 = '';
                    var semclass51_3 = '';
                    var semclass51_4 = '';
                    $('#class5_1txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
            $('#class5_1txt2'+itemjs.id).html(data.modelname.sem_dr);
            $('#class5_1txt3'+itemjs.id).html(data.modelname.part_number);
            $('#class5_1txt4'+itemjs.id).html(data.modelname.name_model);
            $('#class5_1txt5'+itemjs.id).html(data.modelname.drive_name);
            $('#class5_1txt6'+itemjs.id).html(data.model[0].class5_1);
            $('#class5_1txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class5_1);
            $('#class5_1txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class5_1);
            $('#class5_1txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class5_1);
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class5_1 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class5_1 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class5_1 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class5_1 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class5_1 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class5_1 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sem51higher >= data.alarmtest2) {
                        semclass51_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass51all++;
                    } else if (data.sem51below >= data.alarmtest2) {
                        semclass51_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass51all++;
                    } else {
                        semclass51_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.sem51highervs3 >= data.alarmtest3) {
                        semclass51_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass51all++;
                    } else if (data.sem51belowvs3 >= data.alarmtest3) {
                        semclass51_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass51all++;
                    } else {
                        semclass51_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class5_1 < data.lcl.class5_1) {
                        if (nalcltest2 == 1) {
                            semclass51_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass51all++;
                       } else {
                        semclass51_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.semdatalab[itemdata].sem_class5_1 > data.ucl.class5_1) {
                        if(naucltest2 == 1){
                            semclass51_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass51all++;
                        }
                        else
                        {
                            semclass51_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    } else {
                        semclass51_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class5_1 > data.is.class5_1) {
                        
                       if (naistest4 == 1) {
                        semclass51_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chksemclass51all++;
                       } else {
                        semclass51_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else {
                        semclass51_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.sem51higher >= data.alarmtest2) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.sem_class5_1,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class5_1;
                            }
                        } else if (data.sem51below >= data.alarmtest2) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.sem_class5_1,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class5_1;
                            }
                        } else {
                            if (data.sem51highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class5_1;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        
                                        y: v.sem_class5_1,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class5_1;
                                }

                            } else if (data.sem51belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.sem_class5_1;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.sem_class5_1,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class5_1;
                                }
                            } else {
                                if (v.sem_class5_1 < data.lcl.class5_1) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class5_1,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class5_1;
                                }
                           
                                } else if (v.sem_class5_1 > data.ucl.class5_1) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class5_1,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class5_1;
                                }
                                 
                                } else {
                                    if (v.sem_class5_1 > data.sp.class5_1) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class5_1,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class5_1;
                                    }
                                    
                                    } else {
                                        textvalue = v.sem_class5_1;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);       
            datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class5_1));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class5_1));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class5_1));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class5_1));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class5_1));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class5_1));
                            }
                        }
                        
                     var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass5_1' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //-------------------------------- end sem class5_1 --------------------------//

                    //-------------------------------- sem class6 --------------------------//  
                    var chksemclass6all = 0;
                    var semclass6_1 = '';
                    var semclass6_2 = '';
                    var semclass6_3 = '';
                    var semclass6_4 = '';
                    $('#class6txt1'+itemjs.id).html(Getdate(data.modelname.sem_dc));
            $('#class6txt2'+itemjs.id).html(data.modelname.sem_dr);
            $('#class6txt3'+itemjs.id).html(data.modelname.part_number);
            $('#class6txt4'+itemjs.id).html(data.modelname.name_model);
            $('#class6txt5'+itemjs.id).html(data.modelname.drive_name);
            $('#class6txt6'+itemjs.id).html(data.model[0].class6);
            $('#class6txt7'+itemjs.id).html(data.newsemdatalab[itemdata1][0].class6);
            $('#class6txt8'+itemjs.id).html(data.newsemdatalab[itemdata1][1].class6);
            $('#class6txt9'+itemjs.id).html(data.newsemdatalab[itemdata1][2].class6);
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].class6 =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].class6 == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].class6 =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].class6 == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].class6 =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].class6 == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sem6higher >= data.alarmtest2) {
                        semclass6_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass6all++;
                    } else if (data.sem6below >= data.alarmtest2) {
                        semclass6_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass6all++;
                    } else {
                        semclass6_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem6highervs3 >= data.alarmtest3) {
                        semclass6_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass6all++;
                    } else if (data.sem6belowvs3 >= data.alarmtest3) {
                        semclass6_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass6all++;
                    } else {
                        semclass6_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.semdatalab[itemdata].sem_class6 < data.lcl.class6) {
                        if (nalcltest2 == 1) {
                            semclass6_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass6all++;
                       } else {
                        semclass6_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.semdatalab[itemdata].sem_class6 > data.ucl.class6) {
                        if(naucltest2 == 1){
                            semclass6_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chksemclass6all++;
                        }
                        else
                        {
                            semclass6_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        semclass6_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.semdatalab[itemdata].sem_class6 > data.is.class6) {
                        if (naistest4 == 1) {
                            semclass6_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chksemclass6all++;
                       } else {
                        semclass6_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else {
                        semclass6_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.semdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.sem6higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class6,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class6;
                            }
                        } else if (data.sem6below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.sem_class6,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.sem_class6;
                            }
                        } else {
                            if (data.sem6highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    textvalue = {
                                        y: v.sem_class6,
                                        color: 'green'
                                    };
                                } else {
                                    textvalue = v.sem_class6;
                                }

                            } else if (data.sem6belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    textvalue = {
                                        y: v.sem_class6,
                                        color: 'green'
                                    };
                                } else {
                                    textvalue = v.sem_class6;
                                }
                            } else {
                                if (v.sem_class6 < data.lcl.class6) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class6,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class6;
                                }
                             
                                } else if (v.sem_class6 > data.ucl.class6) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class6,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class6;
                                }
                            
                                } else {
                                    if (v.sem_class6 > data.is.class6) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class6,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class6;
                                    }
                                    
                                    } else {
                                        textvalue = v.sem_class6;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_sem);       
            datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;
                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class6));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].class6));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].class6));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].class6));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newsemdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].class6));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.class6));
                            }
                        }
                       
                     var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineclass6' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'Particle/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' Particle/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    $('#chkshow_sem').val(1);
                } else {
                    document.getElementById("show_sem").style.display = "none";
                    $('#chkshow_sem').val(0);
                }
                    //-------------------------------- end sem class6 --------------------------//
                      //-------------------------------- ic anion --------------------------//
                if (data.chkoqadateic == 1) {
                    var chkicanionall = 0;
                    var icanion1 = '';
                    var icanion2 = '';
                    var icanion3 = '';
                    var icanion4 = '';
                    data.icdatalaball.reverse();
            data.icdatalab.reverse();
            data.newicdatalab.reverse();
            var itemdata = data.icdatalab.length-1;
            var itemdata1 = data.newicdatalab.length-1;
                    $('#aniontxt1'+itemjs.id).html(Getdate(data.modelname.ic_dc));
            $('#aniontxt2'+itemjs.id).html(data.modelname.ic_dr);
            $('#aniontxt3'+itemjs.id).html(data.modelname.part_number);
            $('#aniontxt4'+itemjs.id).html(data.modelname.name_model);
            $('#aniontxt5'+itemjs.id).html(data.modelname.drive_name);
            $('#aniontxt6'+itemjs.id).html(data.model[0].anion);
            $('#aniontxt7'+itemjs.id).html(data.newicdatalab[itemdata1][0].anion);
            $('#aniontxt8'+itemjs.id).html(data.newicdatalab[itemdata1][1].anion);
            $('#aniontxt9'+itemjs.id).html(data.newicdatalab[itemdata1][2].anion);
            
            var test7 = data.icdatalaball.length -data.alarmtest3;
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].anion =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].anion == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].anion =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].anion == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].anion =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].anion == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.icanionhigher >= data.alarmtest2) {
                        icanion1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkicanionall++;
                    } else if (data.icanionbelow >= data.alarmtest2) {
                        icanion1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkicanionall++;
                    } else {
                        icanion1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.icanionhighervs3 >= data.alarmtest3) {
                        icanion2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkicanionall++;
                    } else if (data.icanionbelowvs3 >= data.alarmtest3) {
                        icanion2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkicanionall++;
                    } else {
                        icanion2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (parseFloat(data.icdatalab[itemdata].ic_anion_up) < parseFloat(data.lcl.anion)) {
                        if (nalcltest2 == 1) {
                            icanion3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkicanionall++;
                       } else {
                        icanion3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else if (parseFloat(data.icdatalab[itemdata].ic_anion_up) > parseFloat(data.ucl.anion)) {
                        if(naucltest2 == 1){
                        icanion3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkicanionall++;
                        }
                        else
                        {
                            icanion3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
      
                    } else {
                        icanion3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.icdatalab[itemdata].ic_anion_up > data.is.anion) {
                        if (naistest4 == 1) {
                            icanion4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chkicanionall++;
                       } else {
                        icanion4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
             

                    } else {
                        icanion4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        datest = [],
                        len = data.model.length,
                        lenvs = data.icdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.icdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.icanionhigher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_anion_up,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.ic_anion_up;
                            }
                        } else if (data.icanionbelow >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_anion_up,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.ic_anion_up;
                            }
                        } else {
                            if (data.icanionhighervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.ic_anion_up;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.ic_anion_up,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.ic_anion_up;
                                }

                            } else if (data.icanionbelowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.ic_anion_up;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.ic_anion_up,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.ic_anion_up;
                                }
                            } else {
                                if (parseFloat(v.ic_anion_up) < parseFloat(data.lcl.anion)) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_anion_up,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ic_anion_up;
                                }
                            
                                } else if (parseFloat(v.ic_anion_up) > parseFloat(data.ucl.anion)) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_anion_up,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ic_anion_up;
                                }
                          
                                } else {
                                    if (parseFloat(v.ic_anion_up) > parseFloat(data.is.anion)) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.ic_anion_up,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.ic_anion_up;
                                    }
                                 
                                    } else {
                                        textvalue = v.ic_anion_up;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_ic);       
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;
                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.anion));
                            }
                        }
                        if (v.id_model_type == 2) {
                            // $.each(data.newicatalab, function (k, u) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].anion));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].anion));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].anion));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].anion));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.anion));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                     
                   animals = [];
                });
               
                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
             
                Highcharts.chart('Chart_lineanion' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'ug/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' ug/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }
                    );
                    //-------------------------------- end ic anion --------------------------//

                     //--------------------------------  ic sulphat --------------------------//
                     var chkicsulphatall = 0;
                    var icsulphat1 = '';
                    var icsulphat2 = '';
                    var icsulphat3 = '';
                    var icsulphat4 = '';
                    $('#sulphattxt1'+itemjs.id).html(Getdate(data.modelname.ic_dc));
            $('#sulphattxt2'+itemjs.id).html(data.modelname.ic_dr);
            $('#sulphattxt3'+itemjs.id).html(data.modelname.part_number);
            $('#sulphattxt4'+itemjs.id).html(data.modelname.name_model);
            $('#sulphattxt5'+itemjs.id).html(data.modelname.drive_name);
            $('#sulphattxt6'+itemjs.id).html(data.model[0].sulphate);
            $('#sulphattxt7'+itemjs.id).html(data.newicdatalab[itemdata1][0].sulphate);
            $('#sulphattxt8'+itemjs.id).html(data.newicdatalab[itemdata1][1].sulphate);
            $('#sulphattxt9'+itemjs.id).html(data.newicdatalab[itemdata1][2].sulphate);
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].sulphate =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].sulphate == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].sulphate =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].sulphate == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].sulphate =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].sulphate == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.sulphathigher >= data.alarmtest2) {
                        icsulphat1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkicsulphatall++;
                    } else if (data.sulphatbelow >= data.alarmtest2) {
                        icsulphat1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkicsulphatall++;
                    } else {
                        icsulphat1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sulphathighervs3 >= data.alarmtest3) {
                        icsulphat2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkicsulphatall
                    } else if (data.sulphathighervs3 >= data.alarmtest3) {
                        icsulphat2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkicsulphatall++;
                    } else {
                        icsulphat2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.icdatalab[itemdata].ic_sulphate < data.lcl.sulphate) {
                        if (nalcltest2 == 1) {
                            icsulphat3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkicsulphatall++;
                       } else {
                        icsulphat3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
   
                    } else if (data.icdatalab[itemdata].ic_sulphate > data.ucl.sulphate) {
                        if(naucltest2 == 1){
                            icsulphat3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkicsulphatall++;
                        }
                        else
                        {
                            icsulphat3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        icsulphat3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.icdatalab[itemdata].ic_sulphate > data.is.sulphate) {
                        if (naistest4 == 1) {
                            icsulphat4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chkicsulphatall++;
                       } else {
                        icsulphat4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
              
                    } else {
                        icsulphat4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.icdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.icdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.sulphathigher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_sulphate,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.ic_sulphate;
                            }
                        } else if (data.sulphatbelow >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_sulphate,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.ic_sulphate;
                            }
                        } else {
                            if (data.sulphathighervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.ic_sulphate;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.ic_sulphate,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.ic_sulphate;
                                }

                            } else if (data.sulphatbelowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.ic_sulphate;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.ic_sulphate,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.ic_sulphate;
                                }
                            } else {
                                if (v.ic_sulphate < data.lcl.sulphate) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_sulphate,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ic_sulphate;
                                }
                               
                                } else if (v.ic_sulphate > data.ucl.sulphate) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_sulphate,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ic_sulphate;
                                }
                                } else {
                                    if (v.ic_sulphate > data.is.sulphate) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.ic_sulphate,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.ic_sulphate;
                                    }
                                  
                                    } else {
                                        textvalue = v.ic_sulphate;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_ic);       
            datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                   
                    $.each(data.model, function (k, v) {
                        i++;
                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.sulphate));
                            }
                        }
                        if (v.id_model_type == 2) {
                            // $.each(data.newicatalab, function (k, u) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].sulphate));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].sulphate));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].sulphate));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].sulphate));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.sulphate));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_linesulphat' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'ug/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' ug/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //-------------------------------- end ic sulphat --------------------------//
 //-------------------------------- ic cation --------------------------//
 var chkiccationall = 0;
                    var iccation1 = '';
                    var iccation2 = '';
                    var iccation3 = '';
                    var iccation4 = '';
                    $('#cationtxt1'+itemjs.id).html(Getdate(data.modelname.ic_dc));
            $('#cationtxt2'+itemjs.id).html(data.modelname.ic_dr);
            $('#cationtxt3'+itemjs.id).html(data.modelname.part_number);
            $('#cationtxt4'+itemjs.id).html(data.modelname.name_model);
            $('#cationtxt5'+itemjs.id).html(data.modelname.drive_name);
            $('#cationtxt6'+itemjs.id).html(data.model[0].cation);
            $('#cationtxt7'+itemjs.id).html(data.newicdatalab[itemdata1][0].cation);
            $('#cationtxt8'+itemjs.id).html(data.newicdatalab[itemdata1][1].cation);
            $('#cationtxt9'+itemjs.id).html(data.newicdatalab[itemdata1][2].cation);
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].cation =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].cation == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].cation =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].cation == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].cation =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].cation == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.cationhigher >= data.alarmtest2) {
                        iccation1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkiccationall++;
                    } else if (data.cationbelow >= data.alarmtest2) {
                        iccation1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkiccationall++;
                    } else {
                        iccation1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.cationhighervs3 >= data.alarmtest3) {
                        iccation2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkiccationall++;
                    } else if (data.cationbelowvs3 >= data.alarmtest3) {
                        iccation2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkiccationall++;
                    } else {
                        iccation2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.icdatalab[itemdata].ic_cation_up < data.lcl.cation) {
                        if (nalcltest2 == 1) {
                            iccation3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkiccationall++;
                       } else {
                        iccation3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
         
                    } else if (data.icdatalab[itemdata].ic_cation_up > data.ucl.cation) {
                        if(naucltest2 == 1){
                            iccation3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkiccationall++;
                        }
                        else
                        {
                            iccation3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
     
                    } else {
                        iccation3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.icdatalab[itemdata].ic_cation_up > data.is.cation) {
                        if (naistest4 == 1) {
                            iccation4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chkiccationall++;
                       } else {
                        iccation4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else {
                        iccation4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.icdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.icdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.cationhigher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_cation_up,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.ic_cation_up;
                            }
                        } else if (data.cationbelow >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_cation_up,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.ic_cation_up;
                            }
                        } else {
                            if (data.cationhighervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.ic_cation_up;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.ic_cation_up,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.ic_cation_up;
                                }

                            } else if (data.cationbelowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.ic_cation_up;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.ic_cation_up,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.ic_cation_up;
                                }
                            } else {
                                if (v.ic_cation_up < data.lcl.cation) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_cation_up,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ic_cation_up;
                                }
                                } else if (v.ic_cation_up > data.ucl.cation) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_cation_up,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ic_cation_up;
                                }
   
                                } else {
                                    if (v.ic_cation_up > data.is.cation) {
                                        
                                if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.ic_cation_up,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.ic_cation_up;
                                    }

                                    } else {
                                        textvalue = v.ic_cation_up;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_ic);       
            datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.cation));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newicatalab, function (k, u) {
                                animals.push(parseFloat(u[0].cation));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newicatalab, function (k, u) {
                                animals.push(parseFloat(u[1].cation));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newicatalab, function (k, u) {
                                animals.push(parseFloat(u[2].cation));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newicatalab, function (k, u) {
                                animals.push(parseFloat(u[3].cation));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.cation));
                            }
                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                console.log(series);
                Highcharts.chart('Chart_linecation' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'ug/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' ug/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    $('#chkshow_ic').val(1);
                      } else {
                    document.getElementById("show_ic").style.display = "none";
                    $('#chkshow_ic').val(0);
                }
                    //-------------------------------- end ic cation --------------------------//
             
 //-------------------------------- gcms1 --------------------------//
 if (data.chkoqadategcms == 1) {
                    var chkgcms1all = 0;
                    var gcms1_1 = '';
                    var gcms1_2 = '';
                    var gcms1_3 = '';
                    var gcms1_4 = '';
                    data.gcmsdatalaball.reverse();
            data.gcmsdatalab.reverse();
            data.newgcmsdatalab.reverse();
            var itemdata = data.gcmsdatalab.length-1;
            var itemdata1 = data.newgcmsdatalab.length-1;
                    $('#outgassingtxt1'+itemjs.id).html(Getdate(data.modelname.gcms_dc));
            $('#outgassingtxt2'+itemjs.id).html(data.modelname.gcms_dr);
            $('#outgassingtxt3'+itemjs.id).html(data.modelname.part_number);
            $('#outgassingtxt4'+itemjs.id).html(data.modelname.name_model);
            $('#outgassingtxt5'+itemjs.id).html(data.modelname.drive_name);
            $('#outgassingtxt6'+itemjs.id).html(data.model[0].outgassing);
            $('#outgassingtxt7'+itemjs.id).html(data.newgcmsdatalab[itemdata1][0].outgassing);
            $('#outgassingtxt8'+itemjs.id).html(data.newgcmsdatalab[itemdata1][1].outgassing);
            $('#outgassingtxt9'+itemjs.id).html(data.newgcmsdatalab[itemdata1][2].outgassing);
          
            var test7 = data.gcmsdatalaball.length -data.alarmtest3;
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].outgassing =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].outgassing == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].outgassing =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].outgassing == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].outgassing =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].outgassing == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                if (data.gcms1higher >= data.alarmtest2) {
                        gcms1_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms1all++;
                    } else if (data.gcms1below >= data.alarmtest2) {
                        gcms1_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms1all++;
                    } else {
                        gcms1_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.gcms1highervs3 >= data.alarmtest3) {
                        gcms1_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms1all++;
                    } else if (data.gcms1belowvs3 >= data.alarmtest3) {
                        gcms1_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms1all++;
                    } else {
                        gcms1_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.gcmsdatalab[itemdata].gcms_outgassing < data.lcl.outgassing) {
                        if (nalcltest2 == 1) {
                            gcms1_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms1all++;
                       } else {
                        gcms1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
            
                    } else if (data.gcmsdatalab[itemdata].gcms_outgassing > data.ucl.outgassing) {
                        if(naucltest2 == 1){
                            gcms1_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                         chkgcms1all++;
                        }
                        else
                        {
                            gcms1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
           
                    } else {
                        gcms1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.gcmsdatalab[itemdata].gcms_outgassing > data.is.outgassing) {
                        if (naistest4 == 1) {
                            gcms1_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chkgcms1all++;
                       } else {
                        gcms1_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }


                    } else {
                        gcms1_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        datest = [],
                        len = data.model.length,
                        lenvs = data.gcmsdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    
                    $.each(data.gcmsdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.gcms1higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_outgassing,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.gcms_outgassing;
                            }
                        } else if (data.gcms1below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_outgassing,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.gcms_outgassing;
                            }
                        } else {
                            if (data.gcms1highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.gcms_outgassing;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.gcms_outgassing,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_outgassing;
                                }

                            } else if (data.gcms1belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.gcms_outgassing;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.gcms_outgassing,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_outgassing;
                                }
                            } else {
                                if (v.gcms_outgassing < data.lcl.outgassing) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_outgassing,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_outgassing;
                                }
                               
                                } else if (v.gcms_outgassing > data.ucl.outgassing) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_outgassing,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_outgassing;
                                }

                                } else {
                                    if (v.gcms_outgassing > data.is.outgassing) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.gcms_outgassing,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.gcms_outgassing;
                                    }
                          
                                    } else {
                                        textvalue = v.gcms_outgassing;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_gcms);       
            datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;
                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.outgassing));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].outgassing));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].outgassing));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].outgassing));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].outgassing));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.outgassing));
                            }

                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineoutgassing' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'ng/part'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' ng/part';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }
                    );
                    //--------------------------------end gcms1 --------------------------//

                    //-------------------------------- gcms2 --------------------------//
                    var chkgcms2all = 0;
                    var gcms2_1 = '';
                    var gcms2_2 = '';
                    var gcms2_3 = '';
                    var gcms2_4 = '';
                    $('#smeartxt1'+itemjs.id).html(Getdate(data.modelname.gcms_dc));
            $('#smeartxt2'+itemjs.id).html(data.modelname.gcms_dr);
            $('#smeartxt3'+itemjs.id).html(data.modelname.part_number);
            $('#smeartxt4'+itemjs.id).html(data.modelname.name_model);
            $('#smeartxt5'+itemjs.id).html(data.modelname.drive_name);
            $('#smeartxt6'+itemjs.id).html(data.model[0].alkylacrylate);
            $('#smeartxt7'+itemjs.id).html(data.newgcmsdatalab[itemdata1][0].alkylacrylate);
            $('#smeartxt8'+itemjs.id).html(data.newgcmsdatalab[itemdata1][1].alkylacrylate);
            $('#smeartxt9'+itemjs.id).html(data.newgcmsdatalab[itemdata1][2].alkylacrylate);
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].alkylacrylate =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].alkylacrylate == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].alkylacrylate =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].alkylacrylate == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].alkylacrylate =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].alkylacrylate == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.gcms2higher >= data.alarmtest2) {
                        gcms2_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms2all++;
                    } else if (data.gcms2below >= data.alarmtest2) {
                        gcms2_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms2all++;
                    } else {
                        gcms2_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.gcms2highervs3 >= data.alarmtest3) {
                        gcms2_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms2all++;
                    } else if (data.gcms2belowvs3 >= data.alarmtest3) {
                        gcms2_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms2all++;
                    } else {
                        gcms2_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.gcmsdatalab[itemdata].gcms_smear < data.lcl.alkylacrylate) {
                        if (nalcltest2 == 1) {
                            gcms2_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms2all++;
                       } else {
                        gcms2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.gcmsdatalab[itemdata].gcms_smear > data.ucl.alkylacrylate) {
                        if(naucltest2 == 1){
                            gcms2_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms2all++;
                        }
                        else
                        {
                            gcms2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        gcms2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.gcmsdatalab[itemdata].gcms_smear > data.is.alkylacrylate) {
                        
                       if (naistest4 == 1) {
                        gcms2_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chkgcms2all++;
                       } else {
                        gcms2_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
           

                    } else {
                        gcms2_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.gcmsdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.gcmsdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.gcms2higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_smear,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.gcms_smear;
                            }
                        } else if (data.gcms2below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_smear,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.gcms_smear;
                            }
                        } else {
                            if (data.gcms2highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.gcms_smear;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.gcms_smear,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_smear;
                                }

                            } else if (data.gcms2belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.gcms_smear;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.gcms_smear,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_smear;
                                }
                            } else {
                                if (v.gcms_smear < data.lcl.alkylacrylate) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_smear,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_smear;
                                }
                               
                                } else if (v.gcms_smear > data.ucl.alkylacrylate) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_smear,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_smear;
                                }
                           
                                } else {
                                    if (v.gcms_smear > data.is.alkylacrylate) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.gcms_smear,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.gcms_smear;
                                    }
                                
                                    } else {
                                        textvalue = v.gcms_smear;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        
            var fmdate = Getdate(v.date_gcms);       
            datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.alkylacrylate));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].alkylacrylate));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].alkylacrylate));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].alkylacrylate));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].alkylacrylate));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.alkylacrylate));
                            }

                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_linesmear' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'ng/part'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' ng/part';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //--------------------------------end gcms2 --------------------------//

                    //-------------------------------- gcms3 --------------------------//
                    var chkgcms3all = 0;
                    var gcms3_1 = '';
                    var gcms3_2 = '';
                    var gcms3_3 = '';
                    var gcms3_4 = '';
                    $('#low_mwtxt1'+itemjs.id).html(Getdate(data.modelname.gcms_dc));
            $('#low_mwtxt2'+itemjs.id).html(data.modelname.gcms_dr);
            $('#low_mwtxt3'+itemjs.id).html(data.modelname.part_number);
            $('#low_mwtxt4'+itemjs.id).html(data.modelname.name_model);
            $('#low_mwtxt5'+itemjs.id).html(data.modelname.drive_name);
            $('#low_mwtxt6'+itemjs.id).html(data.model[0].low_MW);
            $('#low_mwtxt7'+itemjs.id).html(data.newgcmsdatalab[itemdata1][0].low_MW);
            $('#low_mwtxt8'+itemjs.id).html(data.newgcmsdatalab[itemdata1][1].low_MW);
            $('#low_mwtxt9'+itemjs.id).html(data.newgcmsdatalab[itemdata1][2].low_MW);
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].low_MW =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].low_MW == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].low_MW =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].low_MW == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].low_MW =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].low_MW == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.gcms3higher >= data.alarmtest2) {
                        gcms3_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                         chkgcms3all++;
                    } else if (data.gcms3below >= data.alarmtest2) {
                        gcms3_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms3all++;
                    } else {
                        gcms3_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.gcms3highervs3 >= data.alarmtest3) {
                        gcms3_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms3all++;
                    } else if (data.gcms3belowvs3 >= data.alarmtest3) {
                        gcms3_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms3all++;
                    } else {
                        gcms3_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.gcmsdatalab[itemdata].gcms_low_mw < data.lcl.low_MW) {
                        if (nalcltest2 == 1) {
                            gcms3_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms3all++;
                       } else {
                        gcms3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else if (data.gcmsdatalab[itemdata].gcms_low_mw > data.ucl.low_MW) {
                        
                       if(naucltest2 == 1){
                        gcms3_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms3all++;
                        }
                        else
                        {
                            gcms3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        gcms3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.gcmsdatalab[itemdata].gcms_low_mw > data.is.low_MW) {
                        if (naistest4 == 1) {
                            gcms3_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chkgcms3all++;
                       } else {
                        gcms3_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }


                    } else {
                        gcms3_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.gcmsdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.gcmsdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.gcms3higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_low_mw,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.gcms_low_mw;
                            }
                        } else if (data.gcms3below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_low_mw,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.gcms_low_mw;
                            }
                        } else {
                            if (data.gcms3highervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.gcms_low_mw;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.gcms_low_mw,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_low_mw;
                                }

                            } else if (data.gcms3belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.gcms_low_mw;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.gcms_low_mw,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_low_mw;
                                }
                            } else {
                                if (v.gcms_low_mw < data.lcl.low_MW) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_low_mw,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_low_mw;
                                }
                               
                                } else if (v.gcms_low_mw > data.ucl.low_MW) {
                                    
                                if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_low_mw,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_low_mw;
                                }
                               
                                } else {
                                    if (v.gcms_low_mw > data.is.low_MW) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.gcms_low_mw,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.gcms_low_mw;
                                    }
                               
                                    } else {
                                        textvalue = v.gcms_low_mw;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_gcms);       
            datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;
                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.low_MW));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].low_MW));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].low_MW));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].low_MW));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].low_MW));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.low_MW));
                            }

                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_linelow_mw' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'ng/part'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' ng/part';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //--------------------------------end gcms3 --------------------------//

                    //-------------------------------- gcms4 --------------------------//
                    var chkgcms4all = 0;
                    var gcms4_1 = '';
                    var gcms4_2 = '';
                    var gcms4_3 = '';
                    var gcms4_4 = '';
                    $('#high_mwtxt1'+itemjs.id).html(Getdate(data.modelname.gcms_dc));
            $('#high_mwtxt2'+itemjs.id).html(data.modelname.gcms_dr);
            $('#high_mwtxt3'+itemjs.id).html(data.modelname.part_number);
            $('#high_mwtxt4'+itemjs.id).html(data.modelname.name_model);
            $('#high_mwtxt5'+itemjs.id).html(data.modelname.drive_name);
            $('#high_mwtxt6'+itemjs.id).html(data.model[0].high_MW);
            $('#high_mwtxt7'+itemjs.id).html(data.newgcmsdatalab[itemdata1][0].high_MW);
            $('#high_mwtxt8'+itemjs.id).html(data.newgcmsdatalab[itemdata1][1].high_MW);
            $('#high_mwtxt9'+itemjs.id).html(data.newgcmsdatalab[itemdata1][2].high_MW);
            var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].high_MW =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].high_MW == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].high_MW =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].high_MW == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].high_MW =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].high_MW == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
                    if (data.gcms4higher >= data.alarmtest2) {
                        gcms4_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms4all++;
                    } else if (data.gcms4below >= data.alarmtest2) {
                        gcms4_1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms4all++;
                    } else {
                        gcms4_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.gcms4highervs3 >= data.alarmtest3) {
                        gcms4_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms4all++;
                    } else if (data.gcms4belowvs3 >= data.alarmtest3) {
                        gcms4_2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms4all++;
                    } else {
                        gcms4_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.gcmsdatalab[itemdata].gcms_high_mw < data.lcl.high_MW) {
                        if (nalcltest2 == 1) {
                            gcms4_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms4all++;
                       } else {
                        gcms4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.gcmsdatalab[itemdata].gcms_high_mw > data.ucl.high_MW) {
                        if(naucltest2 == 1){
                            gcms4_3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms4all++;
                        }
                        else
                        {
                            gcms4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    } else {
                        gcms4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.gcmsdatalab[itemdata].gcms_high_mw > data.is.high_MW) {
                        if (naistest4 == 1) {
                            gcms4_4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chkgcms4all++;
                       } else {
                        gcms4_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                

                    } else {
                        gcms4_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        len = data.model.length,
                        lenvs = data.gcmsdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.gcmsdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.gcms4higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_high_mw,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.gcms_high_mw;
                            }
                        } else if (data.gcms4below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_high_mw,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.gcms_high_mw;
                            }
                        } else {
                            if (data.gcms4highervs3 >= data.alarmtest3) {
                                
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.gcms_high_mw;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.gcms_high_mw,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_high_mw;
                                }

                            } else if (data.gcms4belowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.gcms_high_mw;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.gcms_high_mw,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_high_mw;
                                }
                            } else {
                                if (v.gcms_high_mw < data.lcl.high_MW) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_high_mw,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_high_mw;
                                }
                             
                                } else if (v.gcms_high_mw > data.ucl.high_MW) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_high_mw,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_high_mw;
                                }
                            
                                } else {
                                    if (v.gcms_high_mw > data.is.high_MW) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.gcms_high_mw,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.gcms_high_mw;
                                    }
                                  
                                    } else {
                                        textvalue = v.gcms_high_mw;
                                    }
                                }


                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_gcms);       
            datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.high_MW));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].high_MW));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].high_MW));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].high_MW));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newgcmsdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].high_MW));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.high_MW));
                            }

                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_linehigh_mw' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'ng/part'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' ng/part';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }

                    );
                    //--------------------------------end gcms4 --------------------------//
                    $('#chkshow_gcms').val(1);
                } else {
                    document.getElementById("show_gcms").style.display = "none";
                    $('#chkshow_gcms').val(0);
                }

                 //-------------------------------- ftir --------------------------//
            
                 if (data.chkoqadateftir == 1) {
                    var chkftirall = 0;
                    var ftir1 = '';
                    var ftir2 = '';
                    var ftir3 = '';
                    var ftir4 = '';
                    data.ftirdatalaball.reverse();
                    data.ftirdatalab.reverse();
                    data.newftirdatalab.reverse();
                    var itemdata = data.ftirdatalab.length-1;
                    var itemdata1 = data.newftirdatalab.length-1;
                    $('#ftirtxt1'+itemjs.id).html(Getdate(data.modelname.ftir_dc));
                    $('#ftirtxt2'+itemjs.id).html(data.modelname.ftir_dr);
                    $('#ftirtxt3'+itemjs.id).html(data.modelname.part_number);
                    $('#ftirtxt4'+itemjs.id).html(data.modelname.name_model);
                    $('#ftirtxt5'+itemjs.id).html(data.modelname.drive_name);
                    $('#ftirtxt6'+itemjs.id).html(data.model[0].ftir);
                    $('#ftirtxt7'+itemjs.id).html(data.newftirdatalab[itemdata1][0].ftir);
                    $('#ftirtxt8'+itemjs.id).html(data.newftirdatalab[itemdata1][1].ftir);
                    $('#ftirtxt9'+itemjs.id).html(data.newftirdatalab[itemdata1][2].ftir);
                    var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
                if(data.model[2].ftir =="N/A")
                {
                    naucltest2 = 0;
                }
                else if( data.model[2].ftir == "TBD")
                {
                    naucltest2 = 0;
                }
                else
                {
                    naucltest2 = 1;
                }
                if(data.model[3].ftir =="N/A")
                {
                    nalcltest2 = 0;
                }
                else if( data.model[3].ftir == "TBD")
                {
                    nalcltest2 = 0;
                }
                else
                {
                    nalcltest2 = 1;
                }
                if(data.model[5].ftir =="N/A")
                {
                    naistest4 = 0;
                }
                else if( data.model[5].ftir == "TBD")
                {
                    naistest4 = 0;
                }
                else
                {
                    naistest4 = 1;
                }
          
                    var test7 = data.ftirdatalaball.length -data.alarmtest3;
                    if (data.ftirhigher >= data.alarmtest2) {
                        ftir1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkftirall++;
                    } else if (data.ftirbelow >= data.alarmtest2) {
                        ftir1 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test2all++;
                        chkftirall++;
                    } else {
                        ftir1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.ftirhighervs3 >= data.alarmtest3) {
                        ftir2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkftirall++;
                    } else if (data.ftirbelowvs3 >= data.alarmtest3) {
                        ftir2 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test3all++;
                        chkftirall++;
                    } else {
                        ftir2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.ftirdatalab[itemdata].ftir_silicone < data.lcl.ftir) {
                        
                        if (nalcltest2 == 1) {
                            ftir3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkftirall++;
                        
                       } else {
                        ftir3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
          
                    } else if (data.ftirdatalab[itemdata].ftir_silicone > data.ucl.ftir) {
                        if(naucltest2 == 1){
                            ftir3 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test1all++;
                        chkftirall++;
                        }
                        else
                        {
                            ftir3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                     
                    } else {
                        ftir3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                    }
                    if (data.ftirdatalab[itemdata].ftir_silicone > data.is.ftir) {
                        if (naistest4 == 1) {
                            ftir4 = '<i class="fa fa-times fa-1x" style="color:green;"></i>';
                         sumalertall++;
                         test4all++;
                         chkftirall++;
                       } else {
                        ftir4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                  

                    } else {
                        ftir4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        datest = [],
                        len = data.model.length,
                        lenvs = data.ftirdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs -  data.alarmtest3y;
                    $.each(data.ftirdatalaball, function (k, v) {
                        var textvalue = '';
                        ckvs++;

                        if (data.ftirhigher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ftir_silicone,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.ftir_silicone;
                            }
                        } else if (data.ftirbelow >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ftir_silicone,
                                    color: 'green'
                                };
                            } else {
                                textvalue = v.ftir_silicone;
                            }
                        } else {
                            if (data.ftirhighervs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.ftir_silicone;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.ftir_silicone,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.ftir_silicone;
                                }

                            } else if (data.ftirbelowvs3 >= data.alarmtest3) {
                                if (ckvs > sumvs3) {
                                    if(ckvs == test7 )
                                    {
                                        textvalue = v.ftir_silicone;
                                    }
                                    else
                                    {
                                    textvalue = {
                                        y: v.ftir_silicone,
                                        color: 'green'
                                    };
                                    }
                                } else {
                                    textvalue = v.ftir_silicone;
                                }
                            } else {
                                if (v.ftir_silicone < data.lcl.ftir) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ftir_silicone,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ftir_silicone;
                                }
                             
                                } else if (v.ftir_silicone > data.ucl.ftir) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ftir_silicone,
                                        color: 'green'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ftir_silicone;
                                }
                    
                                } else {
                                    if (v.ftir_silicone > data.is.ftir) {
                                        if(naistest4 == 1)
                                    {
                                        textvalue = {
                                            y: v.ftir_silicone,
                                            color: 'green',
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.ftir_silicone;
                                    }
                                
                                    } else {
                                        textvalue = v.ftir_silicone;
                                    }
                                }
                            }
                        }
                        datalab[vs] = textvalue;
                        var fmdate = Getdate(v.date_ftir);       
                        datest[vs] = fmdate+' '+v.lotnumber_lab;
                        vs++;
                    });
                    $.each(data.model, function (k, v) {
                        i++;

                        if (v.id_model_type == 1) {
                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.ftir));
                            }
                        }
                        if (v.id_model_type == 2) {
                            $.each(data.newftirdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].ftir));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newftirdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].ftir));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newftirdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].ftir));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newftirdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].ftir));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.ftir));
                            }

                        }
                        var st = null;
                    var textchkif = null;
                    if (v.id_model_type == 1) {
                        textchkif = '{{$spec}}';
                    }
                    else if (v.id_model_type == 2) {
                        textchkif = '{{$tl}}';
                    }
                    else if (v.id_model_type == 3) {
                        textchkif = '{{$ucl}}';
                    }
                    else if (v.id_model_type == 4) {
                        textchkif = '{{$lcl}}';
                    }
                    else if (v.id_model_type == 5) {
                        st = 'dot';
                        textchkif = '{{$cl}}';
                    }
                    else if (v.id_model_type == 6) {
                        textchkif = '{{$is}}';
                    }
                    else
                    {
                        textchkif = 0;
                    }
                   if(textchkif == 1)
                   {
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                   }
                    
                    animals = [];
                });

                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 3,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 3,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
                Highcharts.chart('Chart_lineftir' + itemjs.id, {
                        chart: {
                            type: 'line',
                            height: heightimg,
                            width: widthimg
                        },
                        title: {
                            text: ''
                        },
                        exporting: {
                            enabled: false
                        },
                        xAxis: {
                            categories: datest,
                            title: {
                                enabled: true,
                                text: 'Date'
                            },
                            labels: {
                                rotation: 270
                            }
                        },
                        yAxis: {
                            title: {
                                text: 'ng/cm2'
                            },
                            labels: {
                                formatter: function () {
                                    return Highcharts.numberFormat(this.value, 0, '', ',');
                                }
                            }
                        },
                        tooltip: {
                            formatter: function () {
                                return this.points.reduce(function (s, point) {
                                    return s + '<br/>' + point.series.name + ': ' +
                                        point.y + ' ng/cm2';
                                }, 'Date :' + this.x);
                            },
                            shared: true
                        },
                        plotOptions: {
                            line: {
                                dataLabels: {
                                    enabled: false
                                },
                                enableMouseTracking: false
                            }
                        },
                        series: series

                    }
                    );
                    //--------------------------------end ftir --------------------------//
                    $('#chkshow_ftir').val(1);
                } else {
                    document.getElementById("show_ftir").style.display = "none";
                    $('#chkshow_ftir').val(0);
                }
            }
        });

        });
 
    });
 
    function editDatalpc(id) {
       var  showeditalam = $('#showeditalam').val();
        window.location.href = "../../output/editdatalpc/" + id+'/2/'+showeditalam;
    }

    function editDatasem(id) {
        window.location.href = "../../output/editdatasem/" + id;
    }

    function editDataic(id) {
        window.location.href = "../../output/editdataic/" + id;
    }

    function editDatagcms(id) {
        window.location.href = "../../output/editdatagcms/" + id;
    }

    function editDataftir(id) {

        window.location.href = "../../output/editdataftir/" + id;
    }
    function formvs() {
         $('#textdate').val('');
        $('#textdetail').val('');
    }
   function addScript(url) {
            var script = document.createElement('script');
            script.type = 'application/javascript';
            script.src = url;
            document.head.appendChild(script);
        }
        addScript('../template/assets/js/exportpdf.js');
   function report() {
     px1  = $('.px1').html();
     px2  = $('.px2').html();
     px3  = $('.px3').html();
     px4  = $('.px4').html();
     px5  = $('.px5').html();
     px6  = $('.px6').html();
     px7  = $('.px7').html();
     px8  = $('.px8').html();
     px9  = $('.px9').html();
     px10  = $('.px10').html();
     px11  = $('.px11').html();
     px12  = $('.px12').html();
     px8  = $('.px8').html();
     px9  = $('.px9').html();
     px10  = $('.px10').html();
     px11  = $('.px11').html();
     px12  = $('.px12').html();
     px13  = $('.px13').html();
     px14  = $('.px14').html();
     px15  = $('.px15').html();
     px16  = $('.px16').html();
     px17  = $('.px17').html();
     px18  = $('.px18').html();
     px19  = $('.px19').html();
     px20  = $('.px20').html();
     px21  = $('.px21').html();
     var textshow = '';
     textshow += '<pangpdf>'+ px1 +'</pangpdf>';
     textshow += '<pangpdf>'+ px2 +'</pangpdf>';
     if($('#chkshow_lpc').val()  == 1)
     {
        textshow += '<pangpdf>'+ px3 +'</pangpdf>';
     }
   if($('#chkshow_sem').val() == 1)
     {
          textshow +=  '<pangpdf>'+ 
             px4 + '</pangpdf><pangpdf>'+
            px5 + '</pangpdf><pangpdf>'+
            px6 + '</pangpdf><pangpdf>'+
            px7 + '</pangpdf><pangpdf>'+
            px8 + '</pangpdf><pangpdf>'+
            px9 + '</pangpdf><pangpdf>'+
            px10 + '</pangpdf><pangpdf>'+
            px11 + '</pangpdf><pangpdf>'+
            px12 + '</pangpdf><pangpdf>'+
            px13 + '</pangpdf>';
     }
     if($('#chkshow_ic').val() == 1)
     {
         textshow +=  '<pangpdf>'+px14 + '</pangpdf><pangpdf>'+
            px15 + '</pangpdf><pangpdf>'+
            px16 + '</pangpdf>';
     }
     if($('#chkshow_gcms').val() == 1)
     {
        textshow += '<pangpdf>'+ px17 + '</pangpdf><pangpdf>'+
              px18 + '</pangpdf><pangpdf>'+
              px19 + '</pangpdf><pangpdf>'+
              px20 +'</pangpdf>';
     }
     if($('#chkshow_ftir').val() == 1)
     {
        textshow += '<pangpdf>'+ px21 +'</pangpdf>';
     }
     var html = textshow;
     var namemodel = $('#namemodel').val();
     var datenow = $('#datenow').val();
     var namefile = "PDF_Customer_"+namemodel+"_"+datenow+".pdf";
     var opt = {

         margin: [1, 5, 1, 5],
         filename: namefile,
         jsPDF: {
             unit: 'mm',
             format: 'a4',
             orientation: 'L'
         },
         pagebreak: {  avoid: 'pangpdf' }
     };
     var pdf = html2pdf().set(opt).from(html).save();
 }
</script>
@endsection
