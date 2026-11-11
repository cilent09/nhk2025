
@extends('layouts.main')
@section('title', 'View Results')
@section('breadcrumb-title', 'Report Customer')
@section('breadcrumb-sub', 'View Customer')
<style>
    .btn-xl {
        padding: 10px 20px;
        font-size: 20px;
        border-radius: 10px;
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
</style>
@section('content')
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">
                <br>
                <h4>Model : <span style="color: red">{{$laball->name_model}}</span> </h4>
                <br>
                
                <br>
            </div>
        </div>
    </div>
</div>
{{-- <button id="myBtn" class="btn btn-warning waves-effect waves-light btn-lg" data-toggle="modal"
    data-target=".bs-example-modal-lg">Warning !</button> --}}
<input type="hidden" id="idcreatelab" value="{{$idcreatelab}}">
<input type="hidden" id="id" value="{{$id}}">
<input type="hidden" id="idshowbutton" value="0">
<input type="hidden" id="idhistory" name="idhistory">
<input type="hidden" id="showeditalam" name="showeditalam">
<div class="my-4 text-center">
</div>
<div class="row" id="show_lpc">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">
                <div id="Chart_line" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="lpctxt9"></span><br>
                    </div>
                    <div class="col-1">
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
                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                   
                        <br>
                        <br>
                        <table id="lpchistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
</div>
<div class="row" id="show_sem">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass1" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class1txt9"></span><br>
                    </div>
                    <div class="col-1">
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
                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                 
                        <br>
                        <br>
                        <table id="class1history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>  
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass2" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class2txt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                    
                        <br>
                        <br>
                        <table id="class2history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass3" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3txt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp;
                
                        <br>
                        <br>
                        <table id="class3history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>

        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass3_1" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_1txt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp;
                  
                        <br>
                        <br>
                        <table id="class3_1history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
           
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">
           
                <div id="Chart_lineclass3_2" style="height: 600px"></div>
            </div>
            <br>
                <div class="container">
                    <div class="row">
                        <div class="col-5">
                            <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt1"></span><br>
                            <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt2"></span><br>
                            <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt3"></span><br>
                            <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt4"></span><br>
                            <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt5"></span><br>
                            <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt6"></span><br>
                            <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt7"></span><br>
                            <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt8"></span><br>
                            <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_2txt9"></span><br>
                        </div>
                        <div class="col-1">
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

                        <div class="col-6">
                            <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                  
                            <br>
                            <br>
                            <table id="class3_2history" width="100%" style="font-size: 95%">
                                <thead>
                                    <tr>
                                        <th width="20%"></th>
                                        <th width="30%">Date</th>
                                        <th width="50%">Detail</th>
                                        
                                    </tr>
                                </thead>
                                <tbody >
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
             <br>

        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass3_3" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class3_3txt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                     
                        <br>
                        <br>
                        <table id="class3_3history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
         <br>
            
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass4" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class4txt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                   
                        <br>
                        <br>
                        <table id="class4history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
         <br>

        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass5" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5txt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                  
                        <br>
                        <br>
                        <table id="class5history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
         <br>
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass5_1" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class5_1txt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
            
                        <br>
                        <br>
                        <table id="class5_1history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
         <br>
            
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineclass6" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="class6txt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                    
                        <br>
                        <br>
                        <table id="class6history" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
         <br>
        </div>
    </div>
</div>


<div class="row" id="show_ic">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineicanion" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="aniontxt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp;
                    
                        <br>
                        <br>
                        <table id="anionhistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>

        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_linesulphat" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="sulphatetxt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp;
                   
                        <br>
                        <br>
                        <table id="sulphatehistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>

        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_linecation" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="cationtxt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp;
                
                        <br>
                        <br>
                        <table id="cationhistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>

     
        </div>
    </div>
</div>

<div class="row" id="show_gcms">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_linegcms1" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt5"></span><br>
                        <b>Specifioutgassing :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="outgassingtxt9"></span><br>
                    </div>
                    <div class="col-1">
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
            
                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                
                        <br>
                        <br>
                        <table id="outgassinghistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
   
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_linegcms2" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt5"></span><br>
                        <b>Specifioutgassing :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="smeartxt9"></span><br>
                    </div>
                    <div class="col-1">
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
            
                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
                       
                        <br>
                        <br>
                        <table id="smearhistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>

        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_linegcms3" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="low_mwtxt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp;
                  
                        <br>
                        <br>
                        <table id="low_mwhistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>
           
        </div>
    </div>
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_linegcms4" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="high_mwtxt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp;
                  
                        <br>
                        <br>
                        <table id="high_mwhistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>

                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <br>

        </div>
    </div>
</div>

<div class="row" id="show_ftir">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-block">

                <div id="Chart_lineftir" style="height: 600px"></div>
            </div>
            <br>
            <div class="container">
                <div class="row">
                    <div class="col-5">
                        <b>Date correlated :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt1"></span><br>
                        <b>Document no./Revision : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt2"></span><br>
                        <b>Part number : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt3"></span><br>
                        <b>Model :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt4"></span><br>
                        <b>Drive name : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt5"></span><br>
                        <b>Specification :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt6"></span><br>
                        <b>Trigger limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt7"></span><br>
                        <b>Upper control limit :</b> &nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt8"></span><br>
                        <b>Lower control limit : </b>&nbsp;&nbsp;&nbsp;&nbsp;<span id="ftirtxt9"></span><br>
                    </div>
                    <div class="col-1">
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

                    <div class="col-6">
                        <b><span style="text-align: center">History table </span></b> &nbsp; &nbsp; &nbsp; 
       
                        <br>
                        <br>
                        <table id="ftirhistory" width="100%" style="font-size: 95%">
                            <thead>
                                <tr>
                                    <th width="20%"></th>
                                    <th width="30%">Date</th>
                                    <th width="50%">Detail</th>
                                    
                                </tr>
                            </thead>
                            <tbody >
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
         <br>
   
        </div>
    </div>
</div>


<div id="container"></div>

<div class="modal fade" id="default-Modalall" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table id="lpchistoryall" width="100%" style="font-size: 95%">
                    <thead>
                        <tr>
                            <th width="20%"></th>
                            <th width="30%">Date</th>
                            <th width="50%">Detail</th>
                            
                        </tr>
                    </thead>
                    <tbody >
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="default-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add History</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col">
                    <div class="col-sm-12">
                        <label for="name-2" class="block">Date
                    </div>
                    <div class="col-sm-12">
                        <input id="textdate" name="textdate" class="form-control" type="date" />
                    </div>
                </div>
                <br>
                <div class="col">
                    <div class="col-sm-12">
                        <label for="surname-2" class="block">Detail</label>
                    </div>
                    <div class="col-sm-12">
                        <textarea class="form-control" rows="5" id="textdetail" name="textdetail"></textarea>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect " data-dismiss="modal">Close</button>
                <button type="button" onclick="addsavehistory()"
                    class="btn btn-primary waves-effect waves-light ">Add</button>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div style="background-color: gold;" class="modal-header">
                <h5 class="modal-title mt-0" id="myLargeModalLabel">Warning ! <br> Lot number : <span
                        id="textlot_number"><span> </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="dt-responsive table-responsive">
                    <table id="modelalert" class="table table-bordered table-hover nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>
                                    <div id="htest1" class="card  order-card">
                                        <div class="card-block">
                                            <h6 class="m-b-20 text-center">
                                                <font size="4">Test1</font>
                                            </h6>
                                            <h2 class="text-center"><span id="test1">1</span></h2>
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div id="htest2" class="card  order-card">
                                        <div class="card-block">
                                            <h6 class="m-b-20 text-center">
                                                <font size="4">Test2</font>
                                            </h6>
                                            <h2 class="text-center"><span id="test2">1</span></h2>
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div id="htest3" class="card  order-card">
                                        <div class="card-block">
                                            <h6 class="m-b-20 text-center">
                                                <font size="4">Test3</font>
                                            </h6>
                                            <h2 class="text-center"><span id="test3">0</span></h2>
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div id="htest4" class="card order-card">
                                        <div class="card-block">
                                            <h6 class="m-b-20 text-center">
                                                <font size="4">Test4</font>
                                            </h6>
                                            <h2 class="text-center"><span id="test4">0</span></h2>
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div id="htest5" class="card bg-c-pink order-card">
                                        <div class="card-block">
                                            <h6 class="m-b-20 text-center">
                                                <font size="4">Test5</font>
                                            </h6>
                                            <h2 class="text-center"><span id="test5">1</span></h2>
                                        </div>
                                    </div>
                                </th>
                                <th>
                                    <div id="htest6" class="card bg-c-pink order-card">
                                        <div class="card-block">
                                            <h6 class="m-b-20 text-center">
                                                <font size="4">Test6</font>
                                            </h6>
                                            <h2 class="text-center"><span id="test6">0</span></h2>
                                        </div>
                                    </div>
                                </th>

                            </tr>
                            <tr>
                                <th style="text-align: center">Data</th>
                                <th style="text-align: center">Test 1</th>
                                <th style="text-align: center">Test 2</th>
                                <th style="text-align: center">Test 3</th>
                                <th style="text-align: center">Test 4</th>
                                <th style="text-align: center">Test 5</th>
                                <th style="text-align: center">Test 6</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection

@section('scripts')
<script>
    //var mybutton = document.getElementById("myBtn");
    // mybutton.style.display = "block";
    // window.onscroll = function () {
    //     scrollFunction()
    // };

    $(document).ready(function (e) {
        var idcreatelab = $('#idcreatelab').val();

        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        }); 

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 1,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var lpchistory = '';
                    $.each(data.history, function (v, item) {
                        lpchistory += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#lpchistory tbody').html(lpchistory);
                } else {

                }
            }
        });

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var class1history = '';
                    $.each(data.history, function (v, item) {
                        class1history += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class1history tbody').html(class1history);
                } else {

                }
            }
        });

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var class2history = '';
                    $.each(data.history, function (v, item) {
                        class2history += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class2history tbody').html(class2history);
                } else {

                }
            }
        });

        
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var class3history = '';
                    $.each(data.history, function (v, item) {
                        class3history += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class3history tbody').html(class3history);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class3_1history tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class3_2history tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class3_3history tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class4history tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class5history tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class5_1history tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 2,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#class6history tbody').html(historydata);
                } else {

                }
            }
        });

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 3,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#anionhistory tbody').html(historydata);
                } else {

                }
            }
        });

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 3,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#sulphatehistory tbody').html(historydata);
                } else {

                }
            }
        });

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 3,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#cationhistory tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 4,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#outgassinghistory tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 4,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#smearhistory tbody').html(historydata);
                } else {

                }
            }
        });
        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 4,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#smearhistory tbody').html(historydata);
                } else {

                }
            }
        });

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 4,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#low_mwhistory tbody').html(historydata);
                } else {

                }
            }
        });

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 4,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#high_mwhistory tbody').html(historydata);
                } else {

                }
            }
        });

        $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': 5,
                'chk': 1
            },
            success: function (data) {

                if (data.success == 1) {
                    var historydata = '';
                    $.each(data.history, function (v, item) {
                        historydata += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#ftirhistory tbody').html(historydata);
                } else {

                }
            }
        });

    });
    
    
    $(document).ready(function (e) {
         var idcreatelab = $("#idcreatelab").val();
        var id = $("#id").val();
        $.ajax({
            type: 'get',
            url: "{{ url('outputgraphtest7') }}",
            data: {
                id: id,
                idcreatelab: idcreatelab,
                chkpdf:2, 
            },
            success: function (data) {

                swal.close();

                var sumalertall = 0;
                var test1all = 0;
                var test2all = 0;
                var test3all = 0;
                var test4all = 0;
                var test5all = 0;
                var test6all = 0;
                
                $('#textlot_number').html(data.oqalot.lotnumber_lab);
                //-------------------------------- lpc --------------------------//
                var chklpcall = 0;
                var array = [10, 20, 30, 40, 50];
                var series = [],
                    animals = [],
                    datalab = [],
                    datest = [],
                    len = data.model.length,
                    i = 0;
                ckvs = 0;
                vs = 0;
         
                if (data.chklpcshow == 1) {
                    var  lenvs = data.datalaball.length;
                var sumvs2 = lenvs - data.alarmtest2;
                var sumvs3 = lenvs - data.alarmtest3y;
                data.datalaball.reverse();
                data.newlpcdatalab.reverse();
                data.datalab.reverse();
                var lpc1 = '';
                var lpc2 = '';
                var lpc3 = '';
                var lpc4 = '';
                var lpc5 = '';
                var lpc6 = '';
                var itemdata = data.datalab.length-1;
                var itemdata1 = data.newlpcdatalab.length-1;
                
                var showv6 = data.datalaball.length-data.alarmtest3y;
                var naucltest2 = 0;
                var nalcltest2 = 0;
                var naistest4 = 0;
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
                // ------------------------- TEST2 -------------------------------------//
                $('#lpctxt1').html(Getdate(data.modelname.alpc_dc));
                $('#lpctxt2').html(data.modelname.alpc_dr);
                $('#lpctxt3').html(data.modelname.part_number);
                $('#lpctxt4').html(data.modelname.name_model);
                $('#lpctxt5').html(data.modelname.drive_name);
                $('#lpctxt6').html(data.model[0].alpc);
                $('#lpctxt7').html(data.newlpcdatalab[itemdata1][0].alpc);
                $('#lpctxt8').html(data.newlpcdatalab[itemdata1][1].alpc);
                $('#lpctxt9').html(data.newlpcdatalab[itemdata1][2].alpc);
                
                var test7 = data.datalaball.length -data.alarmtest3;
                var datauclalpc =  data.newlpcdatalab[itemdata1][1].alpc;
                var datalclalpc =  data.newlpcdatalab[itemdata1][2].alpc;
                var chklpcucl = 0;
                if (data.higher >= data.alarmtest2) {
                    lpc1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                    sumalertall++;
                    test2all++;
                    chklpcall++;

                } else if (data.below >= data.alarmtest2) {
                    lpc1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                    sumalertall++;
                    test2all++;
                    chklpcall++;
                } else {
                    lpc1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                // ------------------------- END TEST2 -------------------------------------//
                // ------------------------- TEST3 -------------------------------------//
                if (data.highervs3 >= data.alarmtest3) {
                    lpc2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                    sumalertall++;
                    test3all++;
                    chklpcall++;
                } else if (data.belowvs3 >= data.alarmtest3) {
                    lpc2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                    sumalertall++;
                    test3all++;
                    chklpcall++;
                } else {
                    lpc2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                // -------------------------END  TEST3 -------------------------------------//
                // ------------------------- TEST1 -------------------------------------//
                if (parseFloat(data.datalab[itemdata].lpc_data) < parseFloat(datalclalpc)) {
                    if(nalcltest2 == 1){
                    lpc3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                    sumalertall++;
                    test1all++;
                    chklpcall++;
                    }
                    else
                    {
                        lpc3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                  
                    //alert(1);
                } else if (parseFloat(data.datalab[itemdata].lpc_data) > parseFloat(datauclalpc)) {
                    if(naucltest2 == 1){
                        lpc3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        lpc4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                    var uclalpc =  data.newlpcdatalab[ckvs][1].alpc;
                    var lclalpc =  data.newlpcdatalab[ckvs][2].alpc;
                    var textvalue = '';
                    ckvs++;
                    if (data.higher >= data.alarmtest2) {

                        if (ckvs > sumvs2) {
                         
                            textvalue = {
                                y: v.lpc_data,
                                color: '#BF0B23'
                            };
                     
                        } else {
                            textvalue = v.lpc_data;
                        }
                    } else if (data.below >= data.alarmtest2) {
                        if (ckvs > sumvs2) {
                        
                            textvalue = {
                                y: v.lpc_data,
                                color: '#BF0B23'
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
                                    color: '#BF0B23'
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
                                    color: '#BF0B23'
                                };
                            }
                            } else {
                                textvalue = v.lpc_data;
                            }
                        } else {
                            if (v.lpc_data < lclalpc) {
                                if(nalcltest2 == 1)
                                {
                                textvalue = {
                                    y: v.lpc_data,
                                    color: '#BF0B23'
                                };
                                }
                                else
                                {
                                    textvalue = v.lpc_data;
                                }
                                
                            } else if (v.lpc_data > uclalpc) {
                                if(naucltest2 == 1)
                                {
                                textvalue = {
                                    y: v.lpc_data,
                                    color: '#BF0B23',
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
                                        color: '#BF0B23',
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
                        $.each(data.newlpcdatalab, function (k, u)  {
                        animals.push(parseFloat(u[0].alpc));
                    });
                    }
                    if (v.id_model_type == 3) {
                        $.each(data.newlpcdatalab, function (k, u)  {
                        animals.push(parseFloat(u[1].alpc));
                    });
                    }
                    if (v.id_model_type == 4) {
                        $.each(data.newlpcdatalab, function (k, u)  {
                        animals.push(parseFloat(u[2].alpc));
                    });
                    }
                    if (v.id_model_type == 5) {
                        $.each(data.newlpcdatalab, function (k, u)  {
                        animals.push(parseFloat(u[3].alpc));
                    });
                    }
                    if (v.id_model_type == 6) {
                       
                       for (i = 0; i < lenvs; i++) {
                           animals.push(parseFloat(v.alpc));
                       }
                    
                   }
                    var st = null;
                    if (v.id_model_type == 5) {
                        st = 'dot';
                    }
                    series.push({
                        name: v.name,
                        data: animals,
                        color: v.color,
                        lineWidth: 2,
                        marker: {
                            enabled: false
                        },
                        // enableMouseTracking: false,
                        label: {
                            enabled: false
                        },
                        zones: [{
                            value: 0
                        }, {
                            dashStyle: st
                        }]
                    });
                    animals = [];
                });
                series.push({
                    name: 'Data',
                    data: datalab,
                    color: "#2b8a16",
                    enableMouseTracking: true,
                    lineWidth: 4,
                    tooltip: {
                        enabled: true
                    },
                    label: {
                        enabled: false
                    },
                    marker: {
                        symbol: "circle",
                        lineWidth: 6,
                        lineColor: null // inherit from series
                    },
                    style: {
                        fontWeight: 'bold'
                    },
                });
               
                Highcharts.chart('Chart_line', {
                        title: {
                            text: 'LPC Trend Chart'
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
                               
                                return this.points.reduce(function (s, point,fmdate) {
                                    return s + '<br/>' + point.series.name + ': ' 
                                        + point.y + ' Particle/cm2';
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
                var textslopelpc = '';
                var textdfflpc = '';
                if ((data.slopelpc >= 0.5 || data.slopelpc <= -0.5) || data.dfflpc >= 3.5) {
                    test5all++;
                    chklpcall++;
                    lpc5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    lpc5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopelpc !=null)
                {
                    if (data.slopelpc >= 0.5 || data.slopelpc <= -0.5) {
                        textslopelpc += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopelpc +
                            '</td>';
                    } else {
                        textslopelpc += '<td>' + data.slopelpc + '</td>';
                    }
                }
                else
                {
                    textslopelpc += '<td>-</td>';
                }
                if(data.dfflpc !=null)
                {
                if (data.dfflpc >= 3.5) {
                    textdfflpc += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dfflpc +
                        '</td>';
                } else {
                    textdfflpc += '<td>' + data.dfflpc + '</td>';
                }
                }
                else
                {
                    textdfflpc += '<td>-</td>';
                }
                var textlpc = '<tr>' +
                    '<td>' + data.modelname.name_model + '</td>' +
                    '<td>' + data.modelname.part + '</td>' +
                    '<td>' + data.modelname.part_number + '</td>' +
                    '<td>' + data.countlpc + '</td>' +
                    '<td>' + (Number(data.avgslope).toFixed(3)) + '</td>' +
                    '<td>' + data.stdlpc + '</td>' +
                    '<td>' + Getdatetime(data.oqalot.save_date) + '</td>' +
                    textslopelpc +
                    textdfflpc +
                    '</tr>';
                $('#dtlpc tbody').html(textlpc);

                //--------------------------------end lpc --------------------------//
                 } else {
                    document.getElementById("show_lpc").style.display = "none";
                }

                if (data.chksemshow == 1) {
                    //-------------------------------- sem class1 --------------------------//
                    var chksemclass1all = 0;
                    var chkclass1all = 0;
                    var semclass1_1 = '';
                    var semclass1_2 = '';
                    var semclass1_3 = '';
                    var semclass1_4 = '';
                    var itemdata = data.semdatalab.length-1;
                    var itemdata1 = data.newsemdatalab.length-1;
                    data.semdatalaball.reverse();
                    data.semdatalab.reverse();
                    data.newsemdatalab.reverse();
                   
                    console.log(data.newsemdatalab[itemdata1][0]);
                    $('#class1txt1').html(Getdate(data.modelname.sem_dc));
                    $('#class1txt2').html(data.modelname.sem_dr);
                    $('#class1txt3').html(data.modelname.part_number);
                    $('#class1txt4').html(data.modelname.name_model);
                    $('#class1txt5').html(data.modelname.drive_name);
                    $('#class1txt6').html(data.model[0].class1);
                    $('#class1txt7').html(data.newsemdatalab[itemdata1][0].class1);
                    $('#class1txt8').html(data.newsemdatalab[itemdata1][1].class1);
                    $('#class1txt9').html(data.newsemdatalab[itemdata1][2].class1);
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
                    var datauclclass1 =  data.newsemdatalab[itemdata1][1].class1;
                    var datalclclass1 =  data.newsemdatalab[itemdata1][2].class1;
                    if (data.sem1higher >= data.alarmtest2) {
                        semclass1_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                        sumalertall++;
                        test2all++;
                        chksemclass1all++;
                    } else if (data.sem1below >= data.alarmtest2) {
                        semclass1_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                        sumalertall++;
                        test2all++;
                        chksemclass1all++;
                    } else {
                        semclass1_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.sem1highervs3 >= data.alarmtest3) {
                        semclass1_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                        sumalertall++;
                        test3all++;
                        chksemclass1all++;
                    } else if (data.sem1belowvs3 >= data.alarmtest3) {
                        semclass1_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                        sumalertall++;
                        test3all++;
                        chksemclass1all++;
                    } else {
                        semclass1_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if(data.semdatalab[itemdata].last_sem_class1 !== null){

                        if (data.semdatalab[itemdata].last_sem_class1 < datalclclass1) {
                            if(nalcltest2 == 1){
                                semclass1_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                sumalertall++;
                                test1all++;
                                chksemclass1all++;
                            }
                            else
                            {
                                semclass1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }
                
                        } else if (data.semdatalab[itemdata].last_sem_class1 > datauclclass1) {
        
                            if(naucltest2 == 1){
                            semclass1_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass1all++;
                            chkclass1all++;
                            }
                            else
                            {
                                semclass1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }
                        } else {
                            semclass1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';

                        }
                        if (data.semdatalab[itemdata].last_sem_class1 > data.is.class1) {

                            if(naistest4 == 1){
                                semclass1_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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

                    }else{

                        if (data.semdatalab[itemdata].sem_class1 < datalclclass1) {
                            if(nalcltest2 == 1){
                                semclass1_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                sumalertall++;
                                test1all++;
                                chksemclass1all++;
                            }
                            else
                            {
                                semclass1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }
                
                        } else if (data.semdatalab[itemdata].sem_class1 > datauclclass1) {
        
                            if(naucltest2 == 1){
                            semclass1_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass1all++;
                            chkclass1all++;
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
                                semclass1_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                    var test7 = data.semdatalaball.length -data.alarmtest3;
                
                    $.each(data.semdatalaball, function (k, v) {
                        var uclclass1 =  data.newsemdatalab[ckvs][1].class1;
                        var lclclass1 =  data.newsemdatalab[ckvs][2].class1;
                        var textvalue = '';
                        ckvs++;
                        
                        if(v.last_sem_class1 !== null){
                            if (data.sem1higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class1;
                                }
                            } else if (data.sem1below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class1;
                                }
                            } else {
                                if (data.sem1highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class1;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class1,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class1;
                                    }

                                } else if (data.sem1belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class1;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class1,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class1;
                                    }
                                } else {
                                    if (v.last_sem_class1 < lclclass1) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class1,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class1;
                                    }
                        
                                    } else if (v.last_sem_class1 > uclclass1) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class1,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class1;
                                    }
                                
                                    } else {
                                        if (v.last_sem_class1 > data.is.class1) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class1,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class1;
                                        }
                                
                                        } else {
                                            textvalue = v.last_sem_class1;
                                        }
                                    }

                                }
                            }
                        }else{
                            if (data.sem1higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class1;
                                }
                            } else if (data.sem1below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class1,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23'
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
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.sem_class1;
                                    }
                                } else {
                                    if (v.sem_class1 < lclclass1) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class1,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class1;
                                    }
                        
                                    } else if (v.sem_class1 > uclclass1) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class1,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
                        //alert(st);
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
                        animals = [];
                        
                    });
                    series.push({
                        name: 'Data',
                        data: datalab,
                        color: "#2b8a16",
                        enableMouseTracking: true,
                        lineWidth: 4,
                        tooltip: {
                            enabled: true
                        },
                        label: {
                            enabled: false
                        },
                        marker: {
                            symbol: "circle",
                            lineWidth: 6,
                            lineColor: null // inherit from series
                        },
                        style: {
                            fontWeight: 'bold'
                        }

                    });
                    Highcharts.chart('Chart_lineclass1', {

                            title: {
                                text: 'SEM Class1 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
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
                            series: series,
                        }
                    );
                var textslopel = '';
                var textdff = '';
                if (data.slopesem1 >= 0.5 || data.slopesem1 <= -0.5 || data.dffsem1 >= 3.5) {
                    test5all++;
                    chksemclass1all++;
                    semclass1_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass1_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem1 !=null)
                {
                    if (data.slopesem1 >= 0.5 || data.slopesem1 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem1 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem1 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem1 !=null)
                {
                if (data.dffsem1 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem1 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem1 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem1 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem1 + '</td>' +
                        '<td>' + (Number(data.avgsem1).toFixed(3))  + '</td>' + 
                        '<td>' + data.stdsem1 + '</td>' +
                        '<td>' + Getdatetime(data.oqalot.save_date) + '</td>' +
                        textslopel +textdff+
                        '</tr>';
                    $('#dtsem1 tbody').html(textsem1);
                    //-------------------------------- end sem class1 --------------------------//
                    //-------------------------------- sem class2 --------------------------//
                    var chksemclass2all = 0;
                    var semclass2_1 = '';
                    var semclass2_2 = '';
                    var semclass2_3 = '';
                    var semclass2_4 = '';
                    $('#class2txt1').html(Getdate(data.modelname.sem_dc));
                    $('#class2txt2').html(data.modelname.sem_dr);
                    $('#class2txt3').html(data.modelname.part_number);
                    $('#class2txt4').html(data.modelname.name_model);
                    $('#class2txt5').html(data.modelname.drive_name);
                    $('#class2txt6').html(data.model[0].class2);
                    $('#class2txt7').html(data.newsemdatalab[itemdata1][0].class2);
                    $('#class2txt8').html(data.newsemdatalab[itemdata1][1].class2);
                    $('#class2txt9').html(data.newsemdatalab[itemdata1][2].class2);
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
                var datalclclass2 =  data.newsemdatalab[itemdata1][2].class2;
                var datauclclass2 =  data.newsemdatalab[itemdata1][1].class2;
                    if (data.sem2higher >= data.alarmtest2) {
                        semclass2_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                        sumalertall++;
                        test2all++;
                        chksemclass2all++;
                    } else if (data.sem2below >= data.alarmtest2) {
                        semclass2_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                        sumalertall++;
                         test2all++;
                        chksemclass2all++;
                    } else {
                        semclass2_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.sem2highervs3 >= data.alarmtest3) {
                        semclass2_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass2all++;
                    } else if (data.sem2belowvs3 >= data.alarmtest3) {
                        semclass2_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass2all++;
                    } else {
                        semclass2_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if(data.semdatalab[itemdata].last_sem_class2 !== null){
                        if (data.semdatalab[itemdata].last_sem_class2 < datalclclass2) {
                            if(nalcltest2 == 1){
                                semclass2_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                sumalertall++;
                                test1all++;
                                chksemclass2all++;
                            }
                            else
                            {
                                semclass2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }
                        } else if (data.semdatalab[itemdata].last_sem_class2 > datauclclass2) {
                            if(naucltest2 == 1){
                                semclass2_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class2 > data.is.class2) {
                            if (naistest4 == 1) {
                                semclass2_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass2all++;
                        } else {
                            semclass2_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    
                        } else {
                            semclass2_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    }else{
                        if (data.semdatalab[itemdata].sem_class2 < datalclclass2) {
                            if(nalcltest2 == 1){
                                semclass2_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                sumalertall++;
                                test1all++;
                                chksemclass2all++;
                            }
                            else
                            {
                                semclass2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }
                        } else if (data.semdatalab[itemdata].sem_class2 > datauclclass2) {
                            if(naucltest2 == 1){
                                semclass2_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass2_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass2all++;
                        } else {
                            semclass2_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    
                        } else {
                            semclass2_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                 
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass2 =  data.newsemdatalab[ckvs][2].class2;
                        var uclclass2 =  data.newsemdatalab[ckvs][1].class2;
                        ckvs++;
                        
                        if(v.last_sem_class2 !== null){
                            if (data.sem2higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class2;
                                }
                            } else if (data.sem2below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class2;
                                }
                            } else {
                                if (data.sem2highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class2;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class2,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class2;
                                    }

                                } else if (data.sem2belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class2;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class2,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class2;
                                    }
                                } else {
                                    if (v.last_sem_class2 < lclclass2) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class2,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class2;
                                    }

                                    } else if (v.last_sem_class2 > uclclass2) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class2,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class2;
                                    }

                                    } else {
                                        if (v.last_sem_class2 > data.is.class2) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class2,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class2;
                                        }
                    
                                        } else {
                                            textvalue = v.last_sem_class2;
                                        }
                                    }
                                }
                            }
                        }else{
                            if (data.sem2higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class2;
                                }
                            } else if (data.sem2below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class2,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23'
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
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.sem_class2;
                                    }
                                } else {
                                    if (v.sem_class2 < lclclass2) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class2,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class2;
                                    }

                                    } else if (v.sem_class2 > uclclass2) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class2,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                        name: 'Data',
                        data: datalab,
                        color: "#2b8a16",
                        enableMouseTracking: true,
                        lineWidth: 4,
                        tooltip: {
                            enabled: true
                        },
                        label: {
                            enabled: false
                        },
                        marker: {
                            symbol: "circle",
                            lineWidth: 6,
                            lineColor: null // inherit from series
                        },
                        style: {
                            fontWeight: 'bold'
                        },
                    });
                    Highcharts.chart('Chart_lineclass2', {

                            title: {
                                text: 'SEM Class2 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
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
                                    // enableMouseTracking: false
                                }
                            },
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopesem2 >= 0.5 || data.slopesem2 <= -0.5 || data.dffsem2 >= 3.5) {
                    test5all++;
                    chksemclass2all++;
                    semclass2_5= '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass2_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem2 !=null)
                {
                    if (data.slopesem2 >= 0.5|| data.slopesem2 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem2 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem2 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem2 !=null)
                {
                if (data.dffsem2 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem2 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem2 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem2 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem2 + '</td>' +
                        '<td>' + (Number(data.avgsem2).toFixed(3))  + '</td>' +
                        '<td>' + data.stdsem2 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtsem2 tbody').html(textsem2);

                    //-------------------------------- end sem class2 --------------------------//

                    //-------------------------------- sem class3 --------------------------//
                    var chksemclass3all = 0;
                    var semclass3_1 = '';
                    var semclass3_2 = '';
                    var semclass3_3 = '';
                    var semclass3_4 = '';
                    $('#class3txt1').html(Getdate(data.modelname.sem_dc));
                    $('#class3txt2').html(data.modelname.sem_dr);
                    $('#class3txt3').html(data.modelname.part_number);
                    $('#class3txt4').html(data.modelname.name_model);
                    $('#class3txt5').html(data.modelname.drive_name);
                    $('#class3txt6').html(data.model[0].class3);
                    $('#class3txt7').html(data.newsemdatalab[itemdata1][0].class3);
                    $('#class3txt8').html(data.newsemdatalab[itemdata1][1].class3);
                    $('#class3txt9').html(data.newsemdatalab[itemdata1][2].class3);
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
                var datalclclass3 =  data.newsemdatalab[itemdata1][2].class3;
                var datauclclass3 =  data.newsemdatalab[itemdata1][1].class3;
                    if (data.sem3higher >= data.alarmtest2) {
                        semclass3_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                        sumalertall++;
                        test2all++
                        chksemclass3all++;
                    } else if (data.sem3below >= data.alarmtest2) {
                        semclass3_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                        sumalertall++;
                        test2all++
                        chksemclass3all++;
                    } else {
                        semclass3_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem3highervs3 >= data.alarmtest3) {
                        semclass3_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++
                        chksemclass3all++;
                    } else if (data.sem3belowvs3 >= data.alarmtest3) {
                        semclass3_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++
                        chksemclass3all++;
                    } else {
                        semclass3_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if(data.semdatalab[itemdata].last_sem_class3 !== null){
                        if (data.semdatalab[itemdata].last_sem_class3 < datalclclass3) {
                            if (nalcltest2 == 1) {
                                semclass3_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++
                            chksemclass3all++;
                        } else {
                            semclass3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    
                        } else if (data.semdatalab[itemdata].last_sem_class3 > datauclclass3) {
                            if(naucltest2 == 1){
                                semclass3_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class3 > data.is.class3) {
                            if (naistest4 == 1) {
                                semclass3_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++
                            chksemclass3all++;
                        } else {
                            semclass3_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }


                        } else {
                            semclass3_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    }else{
                        if (data.semdatalab[itemdata].sem_class3 < datalclclass3) {
                            if (nalcltest2 == 1) {
                                semclass3_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++
                            chksemclass3all++;
                        } else {
                            semclass3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    
                        } else if (data.semdatalab[itemdata].sem_class3 > datauclclass3) {
                            if(naucltest2 == 1){
                                semclass3_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass3_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++
                            chksemclass3all++;
                        } else {
                            semclass3_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }


                        } else {
                            semclass3_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;

                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass3 =  data.newsemdatalab[ckvs][2].class3;
                        var uclclass3 =  data.newsemdatalab[ckvs][1].class3;
                        ckvs++;

                        if(v.last_sem_class3 !== null){
                            if (data.sem3higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3;
                                }
                            } else if (data.sem3below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3;
                                }
                            } else {
                                if (data.sem3highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class3;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class3,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class3;
                                    }
                                } else if (data.sem3belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class3;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class3,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class3;
                                    }
                                } else {
                                    if (v.last_sem_class3 < lclclass3) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class3,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class3;
                                    }
                                
                                    } else if (v.last_sem_class3 > uclclass3) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class3,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class3;
                                    }
                                    } else {
                                        if (v.last_sem_class3 > data.is.class3) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class3,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class3;
                                        }
                        
                                        } else {
                                            textvalue = v.last_sem_class3;
                                        }
                                    }
                                }
                            }
                        }else{
                            if (data.sem3higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3;
                                }
                            } else if (data.sem3below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class3,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23'
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
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.sem_class3;
                                    }
                                } else {
                                    if (v.sem_class3 < lclclass3) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class3;
                                    }
                                
                                    } else if (v.sem_class3 > uclclass3) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });

                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineclass3', {

                            title: {
                                text: 'SEM Class3 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
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
                                    // enableMouseTracking: false
                                }
                            },
                            series: series,
                        }

                    );
                var textslopel = '';
                var textdff = '';
                if ((data.slopesem3 >= 0.5 || data.slopesem3 <= -0.5) || data.dffsem3 >= 3.5) {
                    test5all++;
                    chksemclass3all++;
                    semclass3_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                    
                }
                else
                {
                    semclass3_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem3 !=null)
                {
                    if (data.slopesem3 >= 0.5 || data.slopesem3 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem3 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem3 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem3 !=null)
                {
                if (data.dffsem3 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem3 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem3 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem3 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem3 + '</td>' +
                        '<td>' + data.avgsem3.toFixed(3) + '</td>' +
                        '<td>' + data.stdsem3 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtsem3 tbody').html(textsem3);
                    //-------------------------------- end sem class3 --------------------------//

                    //-------------------------------- sem class3_1 --------------------------//
              
                    var chksemclass31all = 0;
                    var semclass31_1 = '';
                    var semclass31_2 = '';
                    var semclass31_3 = '';
                    var semclass31_4 = '';
                    $('#class3_1txt1').html(Getdate(data.modelname.sem_dc));
                    $('#class3_1txt2').html(data.modelname.sem_dr);
                    $('#class3_1txt3').html(data.modelname.part_number);
                    $('#class3_1txt4').html(data.modelname.name_model);
                    $('#class3_1txt5').html(data.modelname.drive_name);
                    $('#class3_1txt6').html(data.model[0].class3_1);
                    $('#class3_1txt7').html(data.newsemdatalab[itemdata1][0].class3_1);
                    $('#class3_1txt8').html(data.newsemdatalab[itemdata1][1].class3_1);
                    $('#class3_1txt9').html(data.newsemdatalab[itemdata1][2].class3_1);
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
                var datalclclass3_1 =  data.newsemdatalab[itemdata1][2].class3_1;
                var datauclclass3_1 =  data.newsemdatalab[itemdata1][1].class3_1;
                    if (data.sem31higher >= data.alarmtest2) {
                        semclass31_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                         chksemclass31all++;
                    } else if (data.sem31below >= data.alarmtest2) {
                        semclass31_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                         chksemclass31all++;
                    } else {
                        semclass31_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem31highervs3 >= data.alarmtest3) {
                         semclass31_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                         chksemclass31all++;
                    } else if (data.sem31belowvs3 >= data.alarmtest3) {
                        semclass31_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass31all++;
                    } else {
                        semclass31_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if(data.semdatalab[itemdata].last_sem_class3_1 !== null){
                        if (data.semdatalab[itemdata].last_sem_class3_1 < datalclclass3_1) {
                            if (nalcltest2 == 1) {
                                semclass31_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass31all++;
                        } else {
                            semclass31_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else if (data.semdatalab[itemdata].last_sem_class3_1 > datauclclass3_1) {
                            
                        if(naucltest2 == 1){
                            semclass31_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class3_1 > data.is.class3_1) {
                            if (naistest4 == 1) {
                                semclass31_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass31all++;
                        } else {
                            semclass31_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }


                        } else {
                            semclass31_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    }else{
                        if (data.semdatalab[itemdata].sem_class3_1 < datalclclass3_1) {
                            if (nalcltest2 == 1) {
                                semclass31_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass31all++;
                        } else {
                            semclass31_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else if (data.semdatalab[itemdata].sem_class3_1 > datauclclass3_1) {
                            
                        if(naucltest2 == 1){
                            semclass31_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass31_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass31all++;
                        } else {
                            semclass31_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }


                        } else {
                            semclass31_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass3_1 =  data.newsemdatalab[ckvs][2].class3_1;
                        var uclclass3_1 =  data.newsemdatalab[ckvs][1].class3_1;
                        ckvs++;

                        if(v.last_sem_class3_1 !== null){
                            if (data.sem31higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_1;
                                }
                            } else if (data.sem31below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_1;
                                }
                            } else {
                                if (data.sem31highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class3_1;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class3_1,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class3_1;
                                    }

                                } else if (data.sem31belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class3_1;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class3_1,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class3_1;
                                    }
                                } else {
                                    if (v.last_sem_class3_1 < lclclass3_1) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class3_1,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class3_1;
                                    }

                                    } else if (v.last_sem_class3_1 > uclclass3_1) {
                                        
                                    if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class3_1,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class3_1;
                                    }
                                
                                    } else {
                                        if (v.last_sem_class3_1 > data.is.class3_1) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class3_1,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class3_1;
                                        }
                                    
                                        } else {
                                            textvalue = v.last_sem_class3_1;
                                        }
                                    }


                                }
                            }
                        }else{
                            if (data.sem31higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_1;
                                }
                            } else if (data.sem31below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class3_1,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23'
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
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.sem_class3_1;
                                    }
                                } else {
                                    if (v.sem_class3_1 < lclclass3_1) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_1,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class3_1;
                                    }

                                    } else if (v.sem_class3_1 > uclclass3_1) {
                                        
                                    if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_1,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineclass3_1', {

                            title: {
                                text: 'SEM Class3.1 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopesem3_1 >= 0.5 || data.slopesem3_1 <= -0.5 || data.dffsem3_1 >= 3.5 ) {
                    test5all++;
                    chksemclass31all++;
                    semclass31_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass31_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem3_1 !=null)
                {
                    if (data.slopesem3_1 >= 0.5 || data.slopesem3_1 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem3_1 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem3_1 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem3_1 !=null)
                {
                if (data.dffsem3_1 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem3_1 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem3_1 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem3_1 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem3_1 + '</td>' +
                        '<td>' + data.avgsem3_1.toFixed(3) + '</td>' +
                        '<td>' + data.stdsem3_1 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtsem3_1 tbody').html(textsem3_1);
                    //-------------------------------- end sem class3_1 --------------------------//

                    //-------------------------------- sem class3_2 --------------------------//
                    var chksemclass32all = 0;
                    var semclass32_1 = '';
                    var semclass32_2 = '';
                    var semclass32_3 = '';
                    var semclass32_4 = '';
                    $('#class3_2txt1').html(Getdate(data.modelname.sem_dc));
                    $('#class3_2txt2').html(data.modelname.sem_dr);
                    $('#class3_2txt3').html(data.modelname.part_number);
                    $('#class3_2txt4').html(data.modelname.name_model);
                    $('#class3_2txt5').html(data.modelname.drive_name);
                    $('#class3_2txt6').html(data.model[0].class3_2);
                    $('#class3_2txt7').html(data.newsemdatalab[itemdata1][0].class3_2);
                    $('#class3_2txt8').html(data.newsemdatalab[itemdata1][1].class3_2);
                    $('#class3_2txt9').html(data.newsemdatalab[itemdata1][2].class3_2);
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
                var datalclclass3_2 =  data.newsemdatalab[itemdata1][2].class3_2;
                var datauclclass3_2 =  data.newsemdatalab[itemdata1][1].class3_2;
                    if (data.sem32higher >= data.alarmtest2) {
                        semclass32_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass32all++;
                    } else if (data.sem32below >= data.alarmtest2) {
                        semclass32_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass32all++;
                    } else {
                        semclass32_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem32highervs3 >= data.alarmtest3) {
                        semclass32_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass32all++;
                    } else if (data.sem32belowvs3 >= data.alarmtest3) {
                        semclass32_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass32all++;
                    } else {
                        semclass32_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if(data.semdatalab[itemdata].last_sem_class3_2 !== null){
                        if (data.semdatalab[itemdata].last_sem_class3_2 < datalclclass3_2) {
                            if (nalcltest2 == 1) {
                                semclass32_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass32all++;
                        } else {
                            semclass32_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else if (data.semdatalab[itemdata].last_sem_class3_2 > datauclclass3_2) {
                            if(naucltest2 == 1){
                                semclass32_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class3_2 > data.is.class3_2) {
                            if (naistest4 == 1) {
                                semclass32_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass32all++;
                        } else {
                            semclass32_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }


                        } else {
                            semclass32_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                    }else{
                        if (data.semdatalab[itemdata].sem_class3_2 < datalclclass3_2) {
                            if (nalcltest2 == 1) {
                                semclass32_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass32all++;
                        } else {
                            semclass32_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else if (data.semdatalab[itemdata].sem_class3_2 > datauclclass3_2) {
                            if(naucltest2 == 1){
                                semclass32_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass32_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass32all++;
                        } else {
                            semclass32_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }


                        } else {
                            semclass32_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                   
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass3_2 =  data.newsemdatalab[ckvs][2].class3_2;
                        var uclclass3_2 =  data.newsemdatalab[ckvs][1].class3_2;
                        ckvs++;

                        if(v.last_sem_class3_2 !== null){
                            if (data.sem32higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_2;
                                }
                            } else if (data.sem32below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_2;
                                }
                            } else {
                                if (data.sem32highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class3_2;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class3_2,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class3_2;
                                    }

                                } else if (data.sem32belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class3_2;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class3_2,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class3_2;
                                    }
                                } else {
                                    if (v.last_sem_class3_2 < lclclass3_2) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class3_2,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class3_2;
                                    }
                                
                                    } else if (v.last_sem_class3_2 > uclclass3_2) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class3_2,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class3_2;
                                    }
                                
                                    } else {
                                        if (v.last_sem_class3_2 > data.is.class3_2) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class3_2,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class3_2;
                                        }
                        
                                        } else {
                                            textvalue = v.last_sem_class3_2;
                                        }
                                    }
                                }
                            }
                        }else{
                            if (data.sem32higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_2;
                                }
                            } else if (data.sem32below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class3_2,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23'
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
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.sem_class3_2;
                                    }
                                } else {
                                    if (v.sem_class3_2 < lclclass3_2) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_2,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class3_2;
                                    }
                                
                                    } else if (v.sem_class3_2 > uclclass3_2) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_2,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineclass3_2', {

                            title: {
                                text: 'SEM Class3.2 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopesem3_2 >= 0.5 || data.slopesem3_2 <= -0.5 || data.dffsem3_2 >= 3.5) {
                    test5all++;
                    chksemclass32all++;
                    semclass32_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass32_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem3_2 !=null)
                {
                    if (data.slopesem3_2 >= 0.5 || data.slopesem3_2 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem3_2 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem3_2 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem3_2 !=null)
                {
                if (data.dffsem3_2 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem3_2 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem3_2 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem3_2 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem3_2 + '</td>' +
                        '<td>' + data.avgsem3_2.toFixed(2) + '</td>' +
                        '<td>' + data.stdsem3_2 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtsem3_2 tbody').html(textsem3_2);
                    //-------------------------------- end sem class3_2 --------------------------//


                    //-------------------------------- end sem class3_3 --------------------------//
                    var chksemclass33all = 0;
                    var semclass33_1 = '';
                    var semclass33_2 = '';
                    var semclass33_3 = '';
                    var semclass33_4 = '';
                    $('#class3_3txt1').html(Getdate(data.modelname.sem_dc));
            $('#class3_3txt2').html(data.modelname.sem_dr);
            $('#class3_3txt3').html(data.modelname.part_number);
            $('#class3_3txt4').html(data.modelname.name_model);
            $('#class3_3txt5').html(data.modelname.drive_name);
            $('#class3_3txt6').html(data.model[0].class3_3);
            $('#class3_3txt7').html(data.newsemdatalab[itemdata1][0].class3_3);
            $('#class3_3txt8').html(data.newsemdatalab[itemdata1][1].class3_3);
            $('#class3_3txt9').html(data.newsemdatalab[itemdata1][2].class3_3);
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
                var datalclclass3_3 =  data.newsemdatalab[itemdata1][2].class3_3;
                var datauclclass3_3 =  data.newsemdatalab[itemdata1][1].class3_3;
                    if (data.sem33higher >= data.alarmtest2) {
                        semclass33_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass33all++;
                    } else if (data.sem33below >= data.alarmtest2) {
                        semclass33_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass33all++;
                    } else {
                        semclass33_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem33highervs3 >= data.alarmtest3) {
                        semclass33_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass33all++;
                    } else if (data.sem33belowvs3 >= data.alarmtest3) {
                        semclass33_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                        chksemclass33all++;
                    } else {
                        semclass33_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if(data.semdatalab[itemdata].last_sem_class3_3 !== null){
                        if (data.semdatalab[itemdata].last_sem_class3_3 < datalclclass3_3) {
                            if (nalcltest2 == 1) {
                                semclass33_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass33all++;
                        } else {
                            semclass33_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else if (data.semdatalab[itemdata].last_sem_class3_3 > datauclclass3_3) {
                            if(naucltest2 == 1){
                                semclass33_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class3_3 > data.is.class3_3) {
                            if (naistest4 == 1) {
                                semclass33_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass33all++;
                        } else {
                            semclass33_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else {
                            semclass33_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    }else{
                        if (data.semdatalab[itemdata].sem_class3_3 < datalclclass3_3) {
                            if (nalcltest2 == 1) {
                                semclass33_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass33all++;
                        } else {
                            semclass33_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else if (data.semdatalab[itemdata].sem_class3_3 > datauclclass3_3) {
                            if(naucltest2 == 1){
                                semclass33_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass33_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass33all++;
                        } else {
                            semclass33_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else {
                            semclass33_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass3_3 =  data.newsemdatalab[ckvs][2].class3_3;
                        var uclclass3_3 =  data.newsemdatalab[ckvs][1].class3_3;
                        ckvs++;

                        if(v.last_sem_class3_3 !== null){
                            if (data.sem33higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_3;
                                }
                            } else if (data.sem33below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class3_3;
                                }
                            } else {
                                if (data.sem33highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class3_3;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class3_3,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class3_3;
                                    }

                                } else if (data.sem33belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class3_3;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class3_3,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class3_3;
                                    }
                                } else {
                                    if (v.last_sem_class3_3 < lclclass3_3) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class3_3,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class3_3;
                                    }

                                    } else if (v.last_sem_class3_3 > uclclass3_3) {
                                        
                                    if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class3_3,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class3_3;
                                    }

                                    } else {
                                        if (v.last_sem_class3_3 > data.is.class3_3) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class3_3,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class3_3;
                                        }
                                    
                                        } else {
                                            textvalue = v.last_sem_class3_3;
                                        }
                                    }
                                }
                            }
                        }else{
                            if (data.sem33higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class3_3;
                                }
                            } else if (data.sem33below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class3_3,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23'
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
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.sem_class3_3;
                                    }
                                } else {
                                    if (v.sem_class3_3 < lclclass3_3) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_3,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class3_3;
                                    }

                                    } else if (v.sem_class3_3 > uclclass3_3) {
                                        
                                    if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class3_3,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineclass3_3', {
                            title: {
                                text: 'SEM Class3.3 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopesem3_3 >= 0.5 || data.slopesem3_3 <= -0.5 || data.dffsem3_3 >= 3.5) {
                    test5all++;
                    chksemclass33all++;
                    semclass33_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass33_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem3_3 !=null)
                {
                    if (data.slopesem3_3 >= 0.5 || data.slopesem3_3 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem3_3 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem3_3 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem3_3 !=null)
                {
                if (data.dffsem3_3 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem3_3 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem3_3 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem3_3 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem3_3 + '</td>' +
                        '<td>' + data.avgsem3_3.toFixed(3) + '</td>' +
                        '<td>' + data.stdsem3_3 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtsem3_3 tbody').html(textsem3_3);
                    //-------------------------------- end sem class3_3 --------------------------//
                    //--------------------------------  sem class4 --------------------------//
                    var chksemclass4all = 0;
                    var semclass4_1 = '';
                    var semclass4_2 = '';
                    var semclass4_3 = '';
                    var semclass4_4 = '';
                    $('#class4txt1').html(Getdate(data.modelname.sem_dc));
            $('#class4txt2').html(data.modelname.sem_dr);
            $('#class4txt3').html(data.modelname.part_number);
            $('#class4txt4').html(data.modelname.name_model);
            $('#class4txt5').html(data.modelname.drive_name);
            $('#class4txt6').html(data.model[0].class4);
            $('#class4txt7').html(data.newsemdatalab[itemdata1][0].class4);
            $('#class4txt8').html(data.newsemdatalab[itemdata1][1].class4);
            $('#class4txt9').html(data.newsemdatalab[itemdata1][2].class4);
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
                var datalclclass4 =  data.newsemdatalab[itemdata1][2].class4;
                var datauclclass4 =  data.newsemdatalab[itemdata1][1].class4;
                    if (data.sem4higher >= data.alarmtest2) {
                        semclass4_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                       chksemclass4all++;
                    } else if (data.sem4below >= data.alarmtest2) {
                        semclass4_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                       chksemclass4all++;
                    } else {
                        semclass4_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem4highervs3 >= data.alarmtest3) {
                        semclass4_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                       chksemclass4all++;
                    } else if (data.sem4belowvs3 >= data.alarmtest3) {
                        semclass4_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                       chksemclass4all++;
                    } else {
                        semclass4_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    
                    if(data.semdatalab[itemdata].last_sem_class4 !== null){
                        if (data.semdatalab[itemdata].last_sem_class4 < datalclclass4) {
                            if (nalcltest2 == 1) {
                                semclass4_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                        chksemclass4all++;
                        } else {
                            semclass4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else if (data.semdatalab[itemdata].last_sem_class4 > datauclclass4) {
                            if(naucltest2 == 1){
                                semclass4_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class4 > data.is.class4) {
                            if (naistest4 == 1) {
                                semclass4_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass4all++;
                        } else {
                            semclass4_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else {
                            semclass4_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    }else{
                        if (data.semdatalab[itemdata].sem_class4 < datalclclass4) {
                            if (nalcltest2 == 1) {
                                semclass4_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                        chksemclass4all++;
                        } else {
                            semclass4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else if (data.semdatalab[itemdata].sem_class4 > datauclclass4) {
                            if(naucltest2 == 1){
                                semclass4_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass4_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass4all++;
                        } else {
                            semclass4_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else {
                            semclass4_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass4 =  data.newsemdatalab[ckvs][2].class4;
                        var uclclass4 =  data.newsemdatalab[ckvs][1].class4;
                        ckvs++;

                        if(v.last_sem_class4 !== null){
                            if (data.sem4higher >= data.alarmtest2) {
                                if (ckvs > sumvs3) {
                                    textvalue = {
                                        y: v.last_sem_class4,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class4;
                                }
                            } else if (data.sem4below >= data.alarmtest2) {
                                if (ckvs > sumvs3) {
                                    textvalue = {
                                        y: v.last_sem_class4,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class4;
                                }
                            } else {
                                if (data.sem4highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class4;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class4,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class4;
                                    }

                                } else if (data.sem4belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class4;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class4,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class4;
                                    }
                                } else {
                                    if (v.last_sem_class4 < lclclass4) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class4,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class4;
                                    }
                                    
                                    } else if (v.last_sem_class4 > uclclass4) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class4,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class4;
                                    }
                                
                                    } else {
                                        if (v.last_sem_class4 > data.is.class4) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class4,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class4;
                                        }
                                
                                        } else {
                                            textvalue = v.last_sem_class4;
                                        }
                                    }


                                }
                            }
                        }else{
                            if (data.sem4higher >= data.alarmtest2) {
                                if (ckvs > sumvs3) {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class4;
                                }
                            } else if (data.sem4below >= data.alarmtest2) {
                                if (ckvs > sumvs3) {
                                    textvalue = {
                                        y: v.sem_class4,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23'
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
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.sem_class4;
                                    }
                                } else {
                                    if (v.sem_class4 < lclclass4) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class4,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class4;
                                    }
                                    
                                    } else if (v.sem_class4 > uclclass4) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class4,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });

                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineclass4', {

                            title: {
                                text: 'SEM Class4 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopesem4 >= 0.5 || data.slopesem4 <= -0.5 || data.dffsem4 >= 3.5) {
                    test5all++;
                    chksemclass4all++;
                    semclass4_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass4_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem4 !=null)
                {
                    if (data.slopesem4 >= 0.5 || data.slopesem4 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem4 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem4 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem4 !=null)
                {
                if (data.dffsem4 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem4 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem4 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem4 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem4 + '</td>' +
                        '<td>' + data.avgsem4.toFixed(3) + '</td>' +
                        '<td>' + data.stdsem4 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtsem4 tbody').html(textsem4);

                    //-------------------------------- end sem class4 --------------------------//

                    //--------------------------------  sem class5 --------------------------//
                    var chksemclass5all = 0;
                    var semclass5_1 = '';
                    var semclass5_2 = '';
                    var semclass5_3 = '';
                    var semclass5_4 = '';
                    $('#class5txt1').html(Getdate(data.modelname.sem_dc));
            $('#class5txt2').html(data.modelname.sem_dr);
            $('#class5txt3').html(data.modelname.part_number);
            $('#class5txt4').html(data.modelname.name_model);
            $('#class5txt5').html(data.modelname.drive_name);
            $('#class5txt6').html(data.model[0].class5);
            $('#class5txt7').html(data.newsemdatalab[itemdata1][0].class5);
            $('#class5txt8').html(data.newsemdatalab[itemdata1][1].class5);
            $('#class5txt9').html(data.newsemdatalab[itemdata1][2].class5);
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
                var datalclclass5 =  data.newsemdatalab[itemdata1][2].class5;
                var datauclclass5 =  data.newsemdatalab[itemdata1][1].class5;
                    if (data.sem5higher >= data.alarmtest2) {
                        semclass5_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass5all++;
                    } else if (data.sem5below >= data.alarmtest2) {
                        semclass5_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass5all++;
                    } else {
                        semclass5_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem5highervs3 >= data.alarmtest3) {
                        semclass5_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass5all++;
                    } else if (data.sem5belowvs3 >= data.alarmtest3) {
                        semclass5_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass5all++;
                    } else {
                        semclass5_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if(data.semdatalab[itemdata].last_sem_class5 !== null){
                        if (data.semdatalab[itemdata].last_sem_class5 < datalclclass5) {
                            if (nalcltest2 == 1) {
                                semclass5_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass5all++;
                        } else {
                            semclass5_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else if (data.semdatalab[itemdata].last_sem_class5 > datauclclass5) {
                            if(naucltest2 == 1){
                                semclass5_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class5 > data.is.class5) {
                            if (naistest4 == 1) {
                                semclass5_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass5all++;
                        } else {
                            semclass5_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else {
                            semclass5_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    }else{
                        if (data.semdatalab[itemdata].sem_class5 < datalclclass5) {
                            if (nalcltest2 == 1) {
                                semclass5_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass5all++;
                        } else {
                            semclass5_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else if (data.semdatalab[itemdata].sem_class5 > datauclclass5) {
                            if(naucltest2 == 1){
                                semclass5_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass5_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass5all++;
                        } else {
                            semclass5_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else {
                            semclass5_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                   
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass5 =  data.newsemdatalab[ckvs][2].class5;
                        var uclclass5 =  data.newsemdatalab[ckvs][1].class5;
                        ckvs++;

                        if(v.last_sem_class5 !== null){
                            if (data.sem5higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class5,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class5;
                                }
                            } else if (data.sem5below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class5,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class5;
                                }
                            } else {
                                if (data.sem5highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class5;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class5,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class5;
                                    }

                                } else if (data.sem5belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class5;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class5,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class5;
                                    }
                                } else {
                                    if (v.last_sem_class5 < lclclass5) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class5,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class5;
                                    }
                                    } else if (v.last_sem_class5 > uclclass5) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class5,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class5;
                                    }
                                    } else {
                                        if (v.last_sem_class5 > data.is.class5) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class5,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class5;
                                        }
                                
                                        } else {
                                            textvalue = v.last_sem_class5;
                                        }
                                    }


                                }
                            }
                        }else{
                            if (data.sem5higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class5;
                                }
                            } else if (data.sem5below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class5,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23'
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
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.sem_class5;
                                    }
                                } else {
                                    if (v.sem_class5 < lclclass5) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class5,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class5;
                                    }
                                    } else if (v.sem_class5 > uclclass5) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class5,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineclass5', {

                            title: {
                                text: 'SEM Class5 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopesem5 >= 0.5 || data.slopesem5 <= -0.5 || data.dffsem5 >= 3.5) {
                    test5all++;
                    chksemclass5all++;
                    semclass5_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass5_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem5 !=null)
                {
                    if (data.slopesem5 >= 0.5 || data.slopesem5 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem5 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem5 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem5 !=null)
                {
                if (data.dffsem5 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem5 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem5 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem5 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem5 + '</td>' +
                        '<td>' + data.avgsem5.toFixed(3) + '</td>' +
                        '<td>' + data.stdsem5 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                         textslopel +textdff+
                        '</tr>';
                    $('#dtsem5 tbody').html(textsem5);

                    //-------------------------------- end sem class5 --------------------------//

                    //-------------------------------- sem class5_1 --------------------------//
                    var chksemclass51all = 0;
                    var semclass51_1 = '';
                    var semclass51_2 = '';
                    var semclass51_3 = '';
                    var semclass51_4 = '';
                    $('#class5_1txt1').html(Getdate(data.modelname.sem_dc));
                    $('#class5_1txt2').html(data.modelname.sem_dr);
                    $('#class5_1txt3').html(data.modelname.part_number);
                    $('#class5_1txt4').html(data.modelname.name_model);
                    $('#class5_1txt5').html(data.modelname.drive_name);
                    $('#class5_1txt6').html(data.model[0].class5_1);
                    $('#class5_1txt7').html(data.newsemdatalab[itemdata1][0].class5_1);
                    $('#class5_1txt8').html(data.newsemdatalab[itemdata1][1].class5_1);
                    $('#class5_1txt9').html(data.newsemdatalab[itemdata1][2].class5_1);
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
                var datalclclass5_1 =  data.newsemdatalab[itemdata1][2].class5_1;
                var datauclclass5_1 =  data.newsemdatalab[itemdata1][1].class5_1;
                    if (data.sem51higher >= data.alarmtest2) {
                        semclass51_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass51all++;
                    } else if (data.sem51below >= data.alarmtest2) {
                        semclass51_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass51all++;
                    } else {
                        semclass51_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.sem51highervs3 >= data.alarmtest3) {
                        semclass51_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass51all++;
                    } else if (data.sem51belowvs3 >= data.alarmtest3) {
                        semclass51_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass51all++;
                    } else {
                        semclass51_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if(data.semdatalab[itemdata].last_sem_class5_1 !== null){
                        if (data.semdatalab[itemdata].last_sem_class5_1 < datalclclass5_1) {
                            if (nalcltest2 == 1) {
                                semclass51_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                sumalertall++;
                                test1all++;
                                chksemclass51all++;
                            } else {
                                semclass51_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }

                        } else if (data.semdatalab[itemdata].last_sem_class5_1 > datauclclass5_1) {
                            if(naucltest2 == 1){
                                semclass51_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class5_1 > data.is.class5_1) {
                            
                        if (naistest4 == 1) {
                                semclass51_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                sumalertall++;
                                test4all++;
                                chksemclass51all++;
                            } else {
                                semclass51_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }

                        } else {
                            semclass51_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    }else{
                        if (data.semdatalab[itemdata].sem_class5_1 < datalclclass5_1) {
                            if (nalcltest2 == 1) {
                                semclass51_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                sumalertall++;
                                test1all++;
                                chksemclass51all++;
                            } else {
                                semclass51_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }

                        } else if (data.semdatalab[itemdata].sem_class5_1 > datauclclass5_1) {
                            if(naucltest2 == 1){
                                semclass51_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass51_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                                sumalertall++;
                                test4all++;
                                chksemclass51all++;
                            } else {
                                semclass51_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                            }

                        } else {
                            semclass51_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                   
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass5_1 =  data.newsemdatalab[ckvs][2].class5_1;
                        var uclclass5_1 =  data.newsemdatalab[ckvs][1].class5_1;
                        ckvs++;

                        if(v.last_sem_class5_1 !== null){
                            if (data.sem51higher >= data.alarmtest2) {
                                if (ckvs > sumvs3) {
                                    textvalue = {
                                        y: v.last_sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class5_1;
                                }
                            } else if (data.sem51below >= data.alarmtest2) {
                                if (ckvs > sumvs3) {
                                    textvalue = {
                                        y: v.last_sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class5_1;
                                }
                            } else {
                                if (data.sem51highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class5_1;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            
                                            y: v.last_sem_class5_1,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class5_1;
                                    }

                                } else if (data.sem51belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        if(ckvs == test7 )
                                        {
                                            textvalue = v.last_sem_class5_1;
                                        }
                                        else
                                        {
                                        textvalue = {
                                            y: v.last_sem_class5_1,
                                            color: '#BF0B23'
                                        };
                                        }
                                    } else {
                                        textvalue = v.last_sem_class5_1;
                                    }
                                } else {
                                    if (v.last_sem_class5_1 < lclclass5_1) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class5_1,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class5_1;
                                    }
                            
                                    } else if (v.last_sem_class5_1 > uclclass5_1) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class5_1,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class5_1;
                                    }
                                    
                                    } else {
                                        if (v.last_sem_class5_1 > data.sp.class5_1) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class5_1,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class5_1;
                                        }
                                        
                                        } else {
                                            textvalue = v.last_sem_class5_1;
                                        }
                                    }


                                }
                            }
                        }else{
                            if (data.sem51higher >= data.alarmtest2) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.sem_class5_1,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.sem_class5_1;
                            }
                        } else if (data.sem51below >= data.alarmtest2) {
                            if (ckvs > sumvs3) {
                                textvalue = {
                                    y: v.sem_class5_1,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
                                    };
                                    }
                                } else {
                                    textvalue = v.sem_class5_1;
                                }
                            } else {
                                if (v.sem_class5_1 < lclclass5_1) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class5_1,
                                        color: '#BF0B23'
                                    };
                                }
                                else
                                {
                                    textvalue = v.sem_class5_1;
                                }
                           
                                } else if (v.sem_class5_1 > uclclass5_1) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.sem_class5_1,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineclass5_1', {

                            title: {
                                text: 'SEM Class5.1 Trend Chart'
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
                            },
                            yAxis: {
                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopesem5_1 >= 0.5 || data.slopesem5_1 <= -0.5 || data.dffsem5_1 >= 3.5) {
                    test5all++;
                    chksemclass51all++;
                    semclass51_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass51_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem5_1 !=null)
                {
                    if (data.slopesem5_1 >= 0.5 || data.slopesem5_1 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem5_1 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem5_1 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem5_1 !=null)
                {
                if (data.dffsem5_1 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem5_1 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem5_1 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem5_1 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem5_1 + '</td>' +
                        '<td>' + data.avgsem5_1.toFixed(3) + '</td>' +
                        '<td>' + data.stdsem5_1 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtsem5_1 tbody').html(textsem5_1);
                    //-------------------------------- end sem class5_1 --------------------------//

                    //-------------------------------- sem class6 --------------------------//  
                    var chksemclass6all = 0;
                    var semclass6_1 = '';
                    var semclass6_2 = '';
                    var semclass6_3 = '';
                    var semclass6_4 = '';
                    $('#class6txt1').html(Getdate(data.modelname.sem_dc));
            $('#class6txt2').html(data.modelname.sem_dr);
            $('#class6txt3').html(data.modelname.part_number);
            $('#class6txt4').html(data.modelname.name_model);
            $('#class6txt5').html(data.modelname.drive_name);
            $('#class6txt6').html(data.model[0].class6);
            $('#class6txt7').html(data.newsemdatalab[itemdata1][0].class6);
            $('#class6txt8').html(data.newsemdatalab[itemdata1][1].class6);
            $('#class6txt9').html(data.newsemdatalab[itemdata1][2].class6);
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
                var datalclclass6 =  data.newsemdatalab[itemdata1][2].class6;
                var datauclclass6 =  data.newsemdatalab[itemdata1][1].class6;
                    if (data.sem6higher >= data.alarmtest2) {
                        semclass6_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass6all++;
                    } else if (data.sem6below >= data.alarmtest2) {
                        semclass6_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chksemclass6all++;
                    } else {
                        semclass6_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sem6highervs3 >= data.alarmtest3) {
                        semclass6_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass6all++;
                    } else if (data.sem6belowvs3 >= data.alarmtest3) {
                        semclass6_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chksemclass6all++;
                    } else {
                        semclass6_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if(data.semdatalab[itemdata].last_sem_class6 !== null){
                        if (data.semdatalab[itemdata].last_sem_class6 < datalclclass6) {
                            if (nalcltest2 == 1) {
                                semclass6_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass6all++;
                        } else {
                            semclass6_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else if (data.semdatalab[itemdata].last_sem_class6 > datauclclass6) {
                            if(naucltest2 == 1){
                                semclass6_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        if (data.semdatalab[itemdata].last_sem_class6 > data.is.class6) {
                            if (naistest4 == 1) {
                                semclass6_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass6all++;
                        } else {
                            semclass6_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else {
                            semclass6_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                    }else{
                        if (data.semdatalab[itemdata].sem_class6 < datalclclass6) {
                            if (nalcltest2 == 1) {
                                semclass6_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test1all++;
                            chksemclass6all++;
                        } else {
                            semclass6_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }

                        } else if (data.semdatalab[itemdata].sem_class6 > datauclclass6) {
                            if(naucltest2 == 1){
                                semclass6_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                                semclass6_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                            sumalertall++;
                            test4all++;
                            chksemclass6all++;
                        } else {
                            semclass6_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
                        } else {
                            semclass6_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        }
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                   
                    $.each(data.semdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclclass6 =  data.newsemdatalab[ckvs][2].class6;
                        var uclclass6 =  data.newsemdatalab[ckvs][1].class6;
                        ckvs++;

                        if(v.last_sem_class6 !== null){
                            if (data.sem6higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class6,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class6;
                                }
                            } else if (data.sem6below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.last_sem_class6,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.last_sem_class6;
                                }
                            } else {
                                if (data.sem6highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        textvalue = {
                                            y: v.last_sem_class6,
                                            color: '#BF0B23'
                                        };
                                    } else {
                                        textvalue = v.last_sem_class6;
                                    }

                                } else if (data.sem6belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        textvalue = {
                                            y: v.last_sem_class6,
                                            color: '#BF0B23'
                                        };
                                    } else {
                                        textvalue = v.last_sem_class6;
                                    }
                                } else {
                                    if (v.last_sem_class6 < lclclass6) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class6,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class6;
                                    }
                                
                                    } else if (v.last_sem_class6 > uclclass6) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.last_sem_class6,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.last_sem_class6;
                                    }
                                
                                    } else {
                                        if (v.last_sem_class6 > data.is.class6) {
                                            if(naistest4 == 1)
                                        {
                                            textvalue = {
                                                y: v.last_sem_class6,
                                                color: '#BF0B23',
                                            };
                                        }
                                        else
                                        {
                                            textvalue = v.last_sem_class6;
                                        }
                                        
                                        } else {
                                            textvalue = v.last_sem_class6;
                                        }
                                    }
                                }
                            }
                        }else{
                            if (data.sem6higher >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class6,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class6;
                                }
                            } else if (data.sem6below >= data.alarmtest2) {
                                if (ckvs > sumvs2) {
                                    textvalue = {
                                        y: v.sem_class6,
                                        color: '#BF0B23'
                                    };
                                } else {
                                    textvalue = v.sem_class6;
                                }
                            } else {
                                if (data.sem6highervs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        textvalue = {
                                            y: v.sem_class6,
                                            color: '#BF0B23'
                                        };
                                    } else {
                                        textvalue = v.sem_class6;
                                    }

                                } else if (data.sem6belowvs3 >= data.alarmtest3) {
                                    if (ckvs > sumvs3) {
                                        textvalue = {
                                            y: v.sem_class6,
                                            color: '#BF0B23'
                                        };
                                    } else {
                                        textvalue = v.sem_class6;
                                    }
                                } else {
                                    if (v.sem_class6 < lclclass6) {
                                        if(nalcltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class6,
                                            color: '#BF0B23'
                                        };
                                    }
                                    else
                                    {
                                        textvalue = v.sem_class6;
                                    }
                                
                                    } else if (v.sem_class6 > uclclass6) {
                                        if(naucltest2 == 1)
                                    {
                                        textvalue = {
                                            y: v.sem_class6,
                                            color: '#BF0B23'
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
                                                color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineclass6', {

                            title: {
                                text: 'SEM Class6 Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'Particle/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopesem6 >= 0.5 || data.slopesem6 <= -0.5 || data.dffsem6 >= 3.5) {
                    test5all++;
                    chksemclass6all++;
                    semclass6_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    semclass6_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopesem6 !=null)
                {
                    if (data.slopesem6 >= 0.5 || data.slopesem6 <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopesem6 +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopesem6 + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffsem6 !=null)
                {
                if (data.dffsem6 >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffsem6 +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffsem6 + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textsem6 = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countsem6 + '</td>' +
                        '<td>' + data.avgsem6.toFixed(3) + '</td>' +
                        '<td>' + data.stdsem6 + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';
                    $('#dtsem6 tbody').html(textsem6);

                    //-------------------------------- end sem class6 --------------------------//
                } else {
                    document.getElementById("show_sem").style.display = "none";
                }

                //-------------------------------- ic anion --------------------------//
                if (data.chkicshow == 1) {
                    var chkicanionall = 0;
                    var icanion1 = '';
                    var icanion2 = '';
                    var icanion3 = '';
                    var icanion4 = '';
                    var itemdata = data.icdatalab.length-1;
                    var itemdata1 = data.newicdatalab.length-1;
                    data.icdatalaball.reverse();
            data.icdatalab.reverse();
            data.newicdatalab.reverse();
                    $('#aniontxt1').html(Getdate(data.modelname.sem_dc));
                    
            $('#aniontxt2').html(data.modelname.sem_dr);
            $('#aniontxt3').html(data.modelname.part_number);
            $('#aniontxt4').html(data.modelname.name_model);
            $('#aniontxt5').html(data.modelname.drive_name);
            $('#aniontxt6').html(data.model[0].anion);
            $('#aniontxt7').html(data.newicdatalab[itemdata1][0].anion);
            $('#aniontxt8').html(data.newicdatalab[itemdata1][1].anion);
            $('#aniontxt9').html(data.newicdatalab[itemdata1][2].anion);
           
            
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
                var datalclanion =  data.newicdatalab[itemdata1][2].anion;
                var datauclanion =  data.newicdatalab[itemdata1][1].anion;
                    if (data.icanionhigher >= data.alarmtest2) {
                        icanion1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkicanionall++;
                    } else if (data.icanionbelow >= data.alarmtest2) {
                        icanion1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkicanionall++;
                    } else {
                        icanion1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.icanionhighervs3 >= data.alarmtest3) {
                        icanion2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkicanionall++;
                    } else if (data.icanionbelowvs3 >= data.alarmtest3) {
                        icanion2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkicanionall++;
                    } else {
                        icanion2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (parseFloat(data.icdatalab[itemdata].ic_anion_up) < parseFloat(datalclanion)) {
                        if (nalcltest2 == 1) {
                            icanion3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test1all++;
                        chkicanionall++;
                       } else {
                        icanion3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else if (parseFloat(data.icdatalab[itemdata].ic_anion_up) > parseFloat(datauclanion)) {
                        if(naucltest2 == 1){
                        icanion3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                            icanion4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        datest =[],
                        len = data.model.length,
                        lenvs = data.icdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs - data.alarmtest3y;
                   
                    $.each(data.icdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclanion =  data.newicdatalab[ckvs][2].anion;
                        var uclanion =  data.newicdatalab[ckvs][1].anion;
                        ckvs++;

                        if (data.icanionhigher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_anion_up,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_anion_up;
                            }
                        } else if (data.icanionbelow >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_anion_up,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
                                    };
                                    }
                                } else {
                                    textvalue = v.ic_anion_up;
                                }
                            } else {
                                if (parseFloat(v.ic_anion_up) < parseFloat(lclanion)) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_anion_up,
                                        color: '#BF0B23'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ic_anion_up;
                                }
                            
                                } else if (parseFloat(v.ic_anion_up) > parseFloat(uclanion)) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_anion_up,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_lineicanion', {

                            title: {
                                text: 'IC Anion Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'ug/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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

                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopeic_anion >= 0.5 || data.slopeic_anion <= -0.5 || data.dffic_anion >= 3.5) {
                    test5all++;
                    chkicanionall++;
                    icanion5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    icanion5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopeic_anion !=null)
                {
                    if (data.slopeic_anion >= 0.5 || data.slopeic_anion <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopeic_anion +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopeic_anion + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffic_anion !=null)
                {
                if (data.dffic_anion >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffic_anion +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffic_anion + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textic_anion = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countic_anion + '</td>' +
                        '<td>' + (Number(data.avgic_anion).toFixed(3)) + '</td>' +
                        '<td>' + data.stdic_anion + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtic_anion tbody').html(textic_anion);
                    //-------------------------------- end ic anion --------------------------//

                    //--------------------------------  ic sulphat --------------------------//
                    var chkicsulphatall = 0;
                    var icsulphat1 = '';
                    var icsulphat2 = '';
                    var icsulphat3 = '';
                    var icsulphat4 = '';
                    $('#sulphatetxt1').html(Getdate(data.modelname.sem_dc));
            $('#sulphatetxt2').html(data.modelname.sem_dr);
            $('#sulphatetxt3').html(data.modelname.part_number);
            $('#sulphatetxt4').html(data.modelname.name_model);
            $('#sulphatetxt5').html(data.modelname.drive_name);
            $('#sulphatetxt6').html(data.model[0].sulphate);
            $('#sulphatetxt7').html(data.newicdatalab[itemdata1][0].sulphate);
            $('#sulphatetxt8').html(data.newicdatalab[itemdata1][1].sulphate);
            $('#sulphatetxt9').html(data.newicdatalab[itemdata1][2].sulphate);
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
                var datalclsulphate =  data.newicdatalab[itemdata1][2].sulphate;
                var datauclsulphate =  data.newicdatalab[itemdata1][1].sulphate;
                    if (data.sulphathigher >= data.alarmtest2) {
                        icsulphat1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkicsulphatall++;
                    } else if (data.sulphatbelow >= data.alarmtest2) {
                        icsulphat1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkicsulphatall++;
                    } else {
                        icsulphat1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.sulphathighervs3 >= data.alarmtest3) {
                        icsulphat2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkicsulphatall
                    } else if (data.sulphathighervs3 >= data.alarmtest3) {
                        icsulphat2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkicsulphatall++;
                    } else {
                        icsulphat2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (parseFloat(data.icdatalab[itemdata].ic_sulphate) < parseFloat(datalclsulphate)) {
                        if (nalcltest2 == 1) {
                            icsulphat3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test1all++;
                        chkicsulphatall++;
           
                       } else {
                        icsulphat3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
   
                    } else if (data.icdatalab[itemdata].ic_sulphate > datauclsulphate) {
                        if(naucltest2 == 1){
                            icsulphat3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                            icsulphat4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                    var sumvs3 = lenvs - data.alarmtest3y; 
                    $.each(data.icdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclsulphate =  data.newicdatalab[ckvs][2].sulphate;
                        var uclsulphate =  data.newicdatalab[ckvs][1].sulphate;
                        ckvs++;

                        if (data.sulphathigher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_sulphate,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_sulphate;
                            }
                        } else if (data.sulphatbelow >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_sulphate,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_linesulphat', {

                            title: {
                                text: 'IC Sulphat Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'ug/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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

                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopeic_sulphate >= 0.5 || data.slopeic_sulphate <= -0.5 || data.dffic_sulphate >= 3.5) {
                    test5all++;
                    chkicsulphatall++;
                    icsulphat5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    icsulphat5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopeic_sulphate !=null)
                {
                    if (data.slopeic_sulphate >= 0.5 || data.slopeic_sulphate <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopeic_sulphate +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopeic_sulphate + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffic_sulphate !=null)
                {
                if (data.dffic_sulphate >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffic_sulphate +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffic_sulphate + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textic_sulphate = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countic_sulphate + '</td>' +
                        '<td>' + (Number(data.avgic_sulphate).toFixed(3))  + '</td>' +
                        '<td>' + data.stdic_sulphate + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtic_sulphate tbody').html(textic_sulphate);

                    //-------------------------------- end ic sulphat --------------------------//

                    //-------------------------------- ic cation --------------------------//
                    var chkiccationall = 0;
                    var iccation1 = '';
                    var iccation2 = '';
                    var iccation3 = '';
                    var iccation4 = '';
                    $('#cationtxt1').html(Getdate(data.modelname.sem_dc));
            $('#cationtxt2').html(data.modelname.sem_dr);
            $('#cationtxt3').html(data.modelname.part_number);
            $('#cationtxt4').html(data.modelname.name_model);
            $('#cationtxt5').html(data.modelname.drive_name);
            $('#cationtxt6').html(data.model[0].cation);
            $('#cationtxt7').html(data.newicdatalab[itemdata1][0].cation);
            $('#cationtxt8').html(data.newicdatalab[itemdata1][1].cation);
            $('#cationtxt9').html(data.newicdatalab[itemdata1][2].cation);
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
                var datalclcation =  data.newicdatalab[itemdata1][2].cation;
                var datauclcation =  data.newicdatalab[itemdata1][1].cation;
                    if (data.cationhigher >= data.alarmtest2) {
                        iccation1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkiccationall++;
                    } else if (data.cationbelow >= data.alarmtest2) {
                        iccation1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkiccationall++;
                    } else {
                        iccation1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.cationhighervs3 >= data.alarmtest3) {
                        iccation2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkiccationall++;
                    } else if (data.cationbelowvs3 >= data.alarmtest3) {
                        iccation2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkiccationall++;
                    } else {
                        iccation2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                
                    if (data.icdatalab[itemdata].ic_cation_up < datalclcation) {
                        if (nalcltest2 == 1) {
                            iccation3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test1all++;
                        chkiccationall++;
                        //alert(0);
                       } else {
                        iccation3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                        //alert(1);
                       }
                  
                    } else if (data.icdatalab[itemdata].ic_cation_up > datauclcation) {
                        if(naucltest2 == 1){
                            iccation3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                            iccation4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                   
                    $.each(data.icdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclcation =  data.newicdatalab[ckvs][2].cation;
                        var uclcation =  data.newicdatalab[ckvs][1].cation;
                        ckvs++;

                        if (data.cationhigher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_cation_up,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ic_cation_up;
                            }
                        } else if (data.cationbelow >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ic_cation_up,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
                                    };
                                    }
                                } else {
                                    textvalue = v.ic_cation_up;
                                }
                            } else {
                                if (v.ic_cation_up < lclcation) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_cation_up,
                                        color: '#BF0B23'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ic_cation_up;
                                }
                                } else if (v.ic_cation_up > uclcation) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ic_cation_up,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[0].cation));
                            });
                        }
                        if (v.id_model_type == 3) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[1].cation));
                            });
                        }
                        if (v.id_model_type == 4) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[2].cation));
                            });
                        }
                        if (v.id_model_type == 5) {
                            $.each(data.newicdatalab, function (k, u) {
                                animals.push(parseFloat(u[3].cation));
                            });
                        }
                        if (v.id_model_type == 6) {

                            for (i = 0; i < lenvs; i++) {
                                animals.push(parseFloat(v.cation));
                            }

                        }
                        var st = null;
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });

                    Highcharts.chart('Chart_linecation', {

                            title: {
                                text: 'IC Cation Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'ug/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                    if (data.slopeic_cation >= 0.5 || data.slopeic_cation <= -0.5 || data.dffic_cation >= 3.5) {
                    test5all++;
                    chkiccationall++;
                    iccation5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    iccation5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopeic_cation !=null)
                {
                    if (data.slopeic_cation >= 0.5 || data.slopeic_cation <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopeic_cation +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopeic_cation + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffic_cation !=null)
                {
                if (data.dffic_cation >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffic_cation +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffic_cation + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
               
                    var textic_cation = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countic_cation + '</td>' +
                        '<td>' + data.avgic_cation+ '</td>' +
                        '<td>' + data.stdic_cation + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                   $('#dtic_cation tbody').html(textic_cation);
                    //-------------------------------- end ic cation --------------------------//
                } else {
                    document.getElementById("show_ic").style.display = "none";
                }

                //-------------------------------- gcms1 --------------------------//
                if (data.chkgcmsshow == 1) {
                    var chkgcms1all = 0;
                    var gcms1_1 = '';
                    var gcms1_2 = '';
                    var gcms1_3 = '';
                    var gcms1_4 = '';
                    var gcms1_6 = '';
                    var itemdata = data.gcmsdatalab.length-1;
                    var itemdata1 = data.newgcmsdatalab.length-1;
                    $('#outgassingtxt1').html(Getdate(data.modelname.sem_dc));
            $('#outgassingtxt2').html(data.modelname.sem_dr);
            $('#outgassingtxt3').html(data.modelname.part_number);
            $('#outgassingtxt4').html(data.modelname.name_model);
            $('#outgassingtxt5').html(data.modelname.drive_name);
            $('#outgassingtxt6').html(data.model[0].outgassing);
            $('#outgassingtxt7').html(data.newgcmsdatalab[itemdata1][0].outgassing);
            $('#outgassingtxt8').html(data.newgcmsdatalab[itemdata1][1].outgassing);
            $('#outgassingtxt9').html(data.newgcmsdatalab[itemdata1][2].outgassing);
            data.gcmsdatalaball.reverse();
            data.gcmsdatalab.reverse();
            data.newgcmsdatalab.reverse();
            
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
                var datalcloutgassing =  data.newgcmsdatalab[itemdata1][2].outgassing;
                var dataucloutgassing =  data.newgcmsdatalab[itemdata1][1].outgassing;
                    if (data.gcms1higher >= data.alarmtest2) {
                        gcms1_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms1all++;
                    } else if (data.gcms1below >= data.alarmtest2) {
                        gcms1_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms1all++;
                    } else {
                        gcms1_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.gcms1highervs3 >= data.alarmtest3) {
                        gcms1_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms1all++;
                    } else if (data.gcms1belowvs3 >= data.alarmtest3) {
                        gcms1_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms1all++;
                    } else {
                        gcms1_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.gcmsdatalab[itemdata].gcms_outgassing < datalcloutgassing) {
                        if (nalcltest2 == 1) {
                            gcms1_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms1all++;
                       } else {
                        gcms1_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
            
                    } else if (data.gcmsdatalab[itemdata].gcms_outgassing > dataucloutgassing) {
                        if(naucltest2 == 1){
                            gcms1_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                            gcms1_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test4all++;
                         chkgcms1all++;
                       } else {
                        gcms1_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }


                    } else {
                        gcms1_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    var lowhigh = parseFloat(data.gcmsdatalab[itemdata].gcms_low_mw) + parseFloat(data.gcmsdatalab[itemdata].gcms_high_mw);
                    
                    var outgassingtest6 = parseFloat(data.gcmsdatalab[itemdata].gcms_outgassing) - parseFloat(lowhigh);
                   // alert(outgassingtest6);
                    if (outgassingtest6 > 20 || outgassingtest6 < -20) {
                        //sumalertall++:
                        test6all++;
                        chkgcms1all++;
                        gcms1_6 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                    }
                    else
                    {
                        gcms1_4 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                
                    var array = [10, 20, 30, 40, 50];
                    var series = [],
                        animals = [],
                        datalab = [],
                        datest =[],
                        len = data.model.length,
                        lenvs = data.gcmsdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs - data.alarmtest3y;
                    
                    $.each(data.gcmsdatalaball, function (k, v) {
                        var textvalue = '';
                        var lcloutgassing =  data.newgcmsdatalab[ckvs][2].outgassing;
                        var ucloutgassing =  data.newgcmsdatalab[ckvs][1].outgassing;
                        ckvs++;

                        if (data.gcms1higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_outgassing,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_outgassing;
                            }
                        } else if (data.gcms1below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_outgassing,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_outgassing;
                                }
                            } else {
                                if (v.gcms_outgassing < lcloutgassing) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_outgassing,
                                        color: '#BF0B23'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_outgassing;
                                }
                               
                                } else if (v.gcms_outgassing > ucloutgassing) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_outgassing,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_linegcms1', {

                            title: {
                                text: 'GCMS Outgassing Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'ng/part'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                
                if (data.slopegcms_out >= 0.5 || data.slopegcms_out <= -0.5 ||  data.dffgcms_out >= 3.5) {
                    test5all++;
                    chkgcms1all++;
                   
                    gcms1_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    gcms1_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopegcms_out !=null)
                {
                    if (data.slopegcms_out >= 0.5 || data.slopegcms_out <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopegcms_out +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopegcms_out + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffgcms_out !=null)
                {
                if (data.dffgcms_out >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffgcms_out +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffgcms_out + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textgcms_out = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countgcms_out + '</td>' +
                        '<td>' + (Number(data.avggcms_out).toFixed(3))  + '</td>' +
                        '<td>' + data.stdgcms_out + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtgcms_out tbody').html(textgcms_out);
                    //--------------------------------end gcms1 --------------------------//

                    //-------------------------------- gcms2 --------------------------//
                    var chkgcms2all = 0;
                    var gcms2_1 = '';
                    var gcms2_2 = '';
                    var gcms2_3 = '';
                    var gcms2_4 = '';
                    $('#smeartxt1').html(Getdate(data.modelname.sem_dc));
                    
            $('#smeartxt2').html(data.modelname.sem_dr);
            $('#smeartxt3').html(data.modelname.part_number);
            $('#smeartxt4').html(data.modelname.name_model);
            $('#smeartxt5').html(data.modelname.drive_name);
            $('#smeartxt6').html(data.model[0].alkylacrylate);
            $('#smeartxt7').html( data.newgcmsdatalab[itemdata1][0].alkylacrylate);
            $('#smeartxt8').html( data.newgcmsdatalab[itemdata1][1].alkylacrylate);
            $('#smeartxt9').html( data.newgcmsdatalab[itemdata1][2].alkylacrylate);
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
                var datalclalkylacrylate =  data.newgcmsdatalab[itemdata1][2].alkylacrylate;
                var datauclalkylacrylate =  data.newgcmsdatalab[itemdata1][1].alkylacrylate;
                    if (data.gcms2higher >= data.alarmtest2) {
                        gcms2_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms2all++;
                    } else if (data.gcms2below >= data.alarmtest2) {
                        gcms2_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms2all++;
                    } else {
                        gcms2_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.gcms2highervs3 >= data.alarmtest3) {
                        gcms2_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms2all++;
                    } else if (data.gcms2belowvs3 >= data.alarmtest3) {
                        gcms2_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms2all++;
                    } else {
                        gcms2_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.gcmsdatalab[itemdata].gcms_smear < datalclalkylacrylate) {
                        if (nalcltest2 == 1) {
                            gcms2_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms2all++;
                       } else {
                        gcms2_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.gcmsdatalab[itemdata].gcms_smear > datauclalkylacrylate) {
                        if(naucltest2 == 1){
                            gcms2_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        gcms2_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                    var sumvs3 = lenvs - data.alarmtest3y;
            
                    $.each(data.gcmsdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclalkylacrylate =  data.newgcmsdatalab[ckvs][2].alkylacrylate;
                         var uclalkylacrylate =  data.newgcmsdatalab[ckvs][1].alkylacrylate;
                        ckvs++;

                        if (data.gcms2higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_smear,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_smear;
                            }
                        } else if (data.gcms2below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_smear,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_smear;
                                }
                            } else {
                                if (v.gcms_smear < lclalkylacrylate) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_smear,
                                        color: '#BF0B23'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_smear;
                                }
                               
                                } else if (v.gcms_smear > uclalkylacrylate) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_smear,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_linegcms2', {

                            title: {
                                text: 'GCMS Smear Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'ng/part'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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
                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopegcms_smear >= 0.5 || data.slopegcms_smear <= -0.5 || data.dffgcms_smear >= 3.5) {
                    test5all++;
                    chkgcms2all++;
                    gcms2_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    gcms2_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopegcms_smear !=null)
                {
                    if (data.slopegcms_smear >= 0.5 || data.slopegcms_smear <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopegcms_smear +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopegcms_smear + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffgcms_smear !=null)
                {
                if (data.dffgcms_smear >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffgcms_smear +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffgcms_smear + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textgcms_smear = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countgcms_smear + '</td>' +
                        '<td>' + (Number(data.avggcms_smear).toFixed(3))  + '</td>' +
                        '<td>' + data.stdgcms_smear + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtgcms_smear tbody').html(textgcms_smear);
                    //--------------------------------end gcms2 --------------------------//

                    //-------------------------------- gcms3 --------------------------//
                    var chkgcms3all = 0;
                    var gcms3_1 = '';
                    var gcms3_2 = '';
                    var gcms3_3 = '';
                    var gcms3_4 = '';
                    $('#low_mwtxt1').html(Getdate(data.modelname.sem_dc));
            $('#low_mwtxt2').html(data.modelname.sem_dr);
            $('#low_mwtxt3').html(data.modelname.part_number);
            $('#low_mwtxt4').html(data.modelname.name_model);
            $('#low_mwtxt5').html(data.modelname.drive_name);
            $('#low_mwtxt6').html(data.model[0].low_MW);
            $('#low_mwtxt7').html(data.newgcmsdatalab[itemdata1][0].low_MW);
            $('#low_mwtxt8').html(data.newgcmsdatalab[itemdata1][1].low_MW);
            $('#low_mwtxt9').html(data.newgcmsdatalab[itemdata1][2].low_MW);
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
                var datalcllow_MW =  data.newgcmsdatalab[itemdata1][2].low_MW;
                var dataucllow_MW =  data.newgcmsdatalab[itemdata1][1].low_MW;
                    if (data.gcms3higher >= data.alarmtest2) {
                        gcms3_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                         chkgcms3all++;
                    } else if (data.gcms3below >= data.alarmtest2) {
                        gcms3_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms3all++;
                    } else {
                        gcms3_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.gcms3highervs3 >= data.alarmtest3) {
                        gcms3_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms3all++;
                    } else if (data.gcms3belowvs3 >= data.alarmtest3) {
                        gcms3_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms3all++;
                    } else {
                        gcms3_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.gcmsdatalab[itemdata].gcms_low_mw < datalcllow_MW) {
                        if (nalcltest2 == 1) {
                            gcms3_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms3all++;
                       } else {
                        gcms3_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
                    } else if (data.gcmsdatalab[itemdata].gcms_low_mw > dataucllow_MW) {
                        
                       if(naucltest2 == 1){
                        gcms3_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                            gcms3_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                   
                    $.each(data.gcmsdatalaball, function (k, v) {
                        var textvalue = '';
                        var lcllow_MW =  data.newgcmsdatalab[ckvs][2].low_MW;
                        var ucllow_MW =  data.newgcmsdatalab[ckvs][1].low_MW;
                        ckvs++;

                        if (data.gcms3higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_low_mw,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_low_mw;
                            }
                        } else if (data.gcms3below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_low_mw,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_low_mw;
                                }
                            } else {
                                if (v.gcms_low_mw < lcllow_MW) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_low_mw,
                                        color: '#BF0B23'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_low_mw;
                                }
                               
                                } else if (v.gcms_low_mw > ucllow_MW) {
                                    
                                if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_low_mw,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });

                    Highcharts.chart('Chart_linegcms3', {

                            title: {
                                text: 'GCMS Low MW Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'ng/part'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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

                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopegcms_low_mw >= 0.5 || data.slopegcms_low_mw <= -0.5 || data.dffgcms_low_mw >= 3.5) {
                    test5all++;
                    chkgcms3all++;
                    gcms3_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    gcms3_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopegcms_low_mw !=null)
                {
                    if (data.slopegcms_low_mw >= 0.5 || data.slopegcms_low_mw <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopegcms_low_mw +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopegcms_low_mw + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffgcms_low_mw !=null)
                {
                if (data.dffgcms_low_mw >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffgcms_low_mw +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffgcms_low_mw + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textgcms_low_mw = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countgcms_low_mw + '</td>' +
                        '<td>' + (Number(data.avggcms_low_mw).toFixed(3)) + '</td>' +
                        '<td>' + data.stdgcms_low_mw + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtgcms_low_mw tbody').html(textgcms_low_mw);
                    //--------------------------------end gcms3 --------------------------//

                    //-------------------------------- gcms4 --------------------------//
                    var chkgcms4all = 0;
                    var gcms4_1 = '';
                    var gcms4_2 = '';
                    var gcms4_3 = '';
                    var gcms4_4 = '';
                    $('#high_mwtxt1').html(Getdate(data.modelname.sem_dc));
            $('#high_mwtxt2').html(data.modelname.sem_dr);
            $('#high_mwtxt3').html(data.modelname.part_number);
            $('#high_mwtxt4').html(data.modelname.name_model);
            $('#high_mwtxt5').html(data.modelname.drive_name);
            $('#high_mwtxt6').html(data.model[0].high_MW);
            $('#high_mwtxt7').html(data.newgcmsdatalab[itemdata1][0].high_MW);
            $('#high_mwtxt8').html(data.newgcmsdatalab[itemdata1][1].high_MW);
            $('#high_mwtxt9').html(data.newgcmsdatalab[itemdata1][2].high_MW);
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
                var datalclhigh_MW =  data.newgcmsdatalab[itemdata1][2].high_MW;
                var datauclhigh_MW =  data.newgcmsdatalab[itemdata1][1].high_MW;
                    if (data.gcms4higher >= data.alarmtest2) {
                        gcms4_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms4all++;
                    } else if (data.gcms4below >= data.alarmtest2) {
                        gcms4_1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkgcms4all++;
                    } else {
                        gcms4_1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.gcms4highervs3 >= data.alarmtest3) {
                        gcms4_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms4all++;
                    } else if (data.gcms4belowvs3 >= data.alarmtest3) {
                        gcms4_2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkgcms4all++;
                    } else {
                        gcms4_2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    
                    if (data.gcmsdatalab[itemdata].gcms_high_mw < datalclhigh_MW) {
                        if (nalcltest2 == 1) {
                            gcms4_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test1all++;
                        chkgcms4all++;
                       } else {
                        gcms4_3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }

                    } else if (data.gcmsdatalab[itemdata].gcms_high_mw > datauclhigh_MW) {
                        if(naucltest2 == 1){
                            gcms4_3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                            gcms4_4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                    var sumvs3 = lenvs - data.alarmtest3y;
                   
                    $.each(data.gcmsdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclhigh_MW =  data.newgcmsdatalab[ckvs][2].high_MW;
                        var uclhigh_MW =  data.newgcmsdatalab[ckvs][1].high_MW;
                        ckvs++;

                        if (data.gcms4higher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_high_mw,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.gcms_high_mw;
                            }
                        } else if (data.gcms4below >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.gcms_high_mw,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
                                    };
                                    }
                                } else {
                                    textvalue = v.gcms_high_mw;
                                }
                            } else {
                                if (v.gcms_high_mw < lclhigh_MW) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_high_mw,
                                        color: '#BF0B23'
                                    };
                                }
                                else
                                {
                                    textvalue = v.gcms_high_mw;
                                }
                             
                                } else if (v.gcms_high_mw > uclhigh_MW) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.gcms_high_mw,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
                name: 'Data',
                data: datalab,
                color: "#2b8a16",
                enableMouseTracking: true,
                lineWidth: 4,
                tooltip: {
                    enabled: true
                },
                label: {
                    enabled: false
                },
                marker: {
                    symbol: "circle",
                    lineWidth: 6,
                    lineColor: null // inherit from series
                },
                style: {
                    fontWeight: 'bold'
                },
            });
                    Highcharts.chart('Chart_linegcms4', {

                            title: {
                                text: 'GCMS High MW Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'ng/part'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
                                    // enableMouseTracking: false
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

                            series: series,
                        }

                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopegcms_high_mw >= 0.5 || data.slopegcms_high_mw <= -0.5 || data.dffgcms_high_mw >= 3.5) {
                    test5all++;
                    chkgcms4all++;
                    gcms4_5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    gcms4_5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopegcms_high_mw !=null)
                {
                    if (data.slopegcms_high_mw >= 0.5 || data.slopegcms_high_mw <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopegcms_high_mw +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopegcms_high_mw + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffgcms_high_mw !=null)
                {
                if (data.dffgcms_high_mw >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffgcms_high_mw +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffgcms_high_mw + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textgcms_high_mw = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countgcms_high_mw + '</td>' +
                        '<td>' + (Number(data.avggcms_high_mw).toFixed(3))  + '</td>' +
                        '<td>' + data.stdgcms_high_mw + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtgcms_high_mw tbody').html(textgcms_high_mw);
                    //--------------------------------end gcms4 --------------------------//
                } else {
                    document.getElementById("show_gcms").style.display = "none";
                }

                //-------------------------------- ftir --------------------------//
                if (data.chkftirshow == 1) {
                    var chkftirall = 0;
                    var ftir1 = '';
                    var ftir2 = '';
                    var ftir3 = '';
                    var ftir4 = '';
                    var itemdata = data.ftirdatalab.length-1;
                    var itemdata1 = data.newftirdatalab.length-1;
                    data.ftirdatalaball.reverse();
                    data.ftirdatalab.reverse();
                    data.newftirdatalab.reverse();
                    $('#ftirtxt1').html(Getdate(data.modelname.sem_dc));
                    $('#ftirtxt2').html(data.modelname.sem_dr);
                    $('#ftirtxt3').html(data.modelname.part_number);
                    $('#ftirtxt4').html(data.modelname.name_model);
                    $('#ftirtxt5').html(data.modelname.drive_name);
                    $('#ftirtxt6').html(data.model[0].ftir);
                    $('#ftirtxt7').html(data.newftirdatalab[itemdata1][0].ftir);
                    $('#ftirtxt8').html(data.newftirdatalab[itemdata1][1].ftir);
                    $('#ftirtxt9').html(data.newftirdatalab[itemdata1][2].ftir);
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
                    var datalclftir =  data.newftirdatalab[itemdata1][2].ftir;
                    var datauclftir =  data.newftirdatalab[itemdata1][1].ftir;
                    if (data.ftirhigher >= data.alarmtest2) {
                        ftir1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkftirall++;
                    } else if (data.ftirbelow >= data.alarmtest2) {
                        ftir1 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test2all++;
                        chkftirall++;
                    } else {
                        ftir1 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }

                    if (data.ftirhighervs3 >= data.alarmtest3) {
                        ftir2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkftirall++;
                    } else if (data.ftirbelowvs3 >= data.alarmtest3) {
                        ftir2 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test3all++;
                        chkftirall++;
                    } else {
                        ftir2 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                    }
                    if (data.ftirdatalab[itemdata].ftir_silicone < datalclftir) {
                        
                        if (nalcltest2 == 1) {
                            ftir3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                         sumalertall++;
                         test1all++;
                        chkftirall++;
                        
                       } else {
                        ftir3 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                       }
          
                    } else if (data.ftirdatalab[itemdata].ftir_silicone > datauclftir) {
                        if(naucltest2 == 1){
                            ftir3 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                            ftir4 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
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
                        datest =[],
                        len = data.model.length,
                        lenvs = data.ftirdatalaball.length,
                        i = 0;
                    ckvs = 0;
                    vs = 0;
                    var sumvs2 = lenvs - data.alarmtest2;
                    var sumvs3 = lenvs - data.alarmtest3y;
                    
                    $.each(data.ftirdatalaball, function (k, v) {
                        var textvalue = '';
                        var lclftir =  data.newftirdatalab[ckvs][2].ftir;
                        var uclftir =  data.newftirdatalab[ckvs][1].ftir;
                        ckvs++;

                        if (data.ftirhigher >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ftir_silicone,
                                    color: '#BF0B23'
                                };
                            } else {
                                textvalue = v.ftir_silicone;
                            }
                        } else if (data.ftirbelow >= data.alarmtest2) {
                            if (ckvs > sumvs2) {
                                textvalue = {
                                    y: v.ftir_silicone,
                                    color: '#BF0B23'
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
                                        color: '#BF0B23'
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
                                        color: '#BF0B23'
                                    };
                                    }
                                } else {
                                    textvalue = v.ftir_silicone;
                                }
                            } else {
                                if (v.ftir_silicone < lclftir) {
                                    if(nalcltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ftir_silicone,
                                        color: '#BF0B23'
                                    };
                                }
                                else
                                {
                                    textvalue = v.ftir_silicone;
                                }
                             
                                } else if (v.ftir_silicone > uclftir) {
                                    if(naucltest2 == 1)
                                {
                                    textvalue = {
                                        y: v.ftir_silicone,
                                        color: '#BF0B23'
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
                                            color: '#BF0B23',
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
                        if (v.id_model_type == 5) {

                            st = 'dot';
                        }
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
                        animals = [];
                    });
                    series.push({
    name: 'Data',
    data: datalab,
    color: "#2b8a16",
    enableMouseTracking: true,
    lineWidth: 4,
    tooltip: {
        enabled: true
    },
    label: {
        enabled: false
    },
    marker: {
        symbol: "circle",
        lineWidth: 6,
        lineColor: null // inherit from series
    },
    style: {
        fontWeight: 'bold'
    },
});
                    Highcharts.chart('Chart_lineftir', {

                            title: {
                                text: 'FTIR Trend Chart'
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
                            },
                            yAxis: {

                                title: {
                                    text: 'ng/cm2'
                                },
                                labels: {
                                    formatter: function () {
                                        return Highcharts.numberFormat(this.value, 0, '',
                                            ',');
                                    }
                                }
                            },
                            plotOptions: {
                                line: {
                                    dataLabels: {
                                        enabled: false
                                    },
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
                            series: series,
                        }
                    );
                    var textslopel = '';
                var textdff = '';
                if (data.slopeftir_sc >= 0.5 || data.slopeftir_sc <= -0.5 || data.dffftir_sc >= 3.5) {
                    test5all++;
                    chkftirall++;
                    ftir5 = '<i class="fa fa-times fa-1x" style="color:#D4020F;"></i>';
                }
                else
                {
                    ftir5 = '<i class="fa fa-check fa-1x" style="color:green;"></i>';
                }
                if(data.slopeftir_sc !=null)
                {
                    if (data.slopeftir_sc >= 0.5 || data.slopeftir_sc <= -0.5) {
                        textslopel += '<td bgcolor="red" style="color: #FFFFFF">' + data.slopeftir_sc +
                            '</td>';
                    } else {
                        textslopel += '<td>' + data.slopeftir_sc + '</td>';
                    }
                }
                else
                {
                    textslopel += '<td>-</td>';
                }
                if(data.dffftir_sc !=null)
                {
                if (data.dffftir_sc >= 3.5) {
                    textdff += '<td bgcolor="red" style="color: #FFFFFF	">' + data.dffftir_sc +
                        '</td>';
                } else {
                    textdff += '<td>' + data.dffftir_sc + '</td>';
                }
                }
                else
                {
                    textdff += '<td>-</td>';
                }
                    var textftir_sc = '<tr>' +
                        '<td>' + data.modelname.name_model + '</td>' +
                        '<td>' + data.modelname.part + '</td>' +
                        '<td>' + data.modelname.part_number + '</td>' +
                        '<td>' + data.countftir_sc + '</td>' +
                        '<td>' + (Number(data.avgftir_sc).toFixed(3))  + '</td>' +
                        '<td>' + data.stdftir_sc + '</td>' +
                        '<td>' + data.oqalot.save_date + '</td>' +
                        textslopel +textdff+
                        '</tr>';

                    $('#dtftir_sc tbody').html(textftir_sc);
                    //--------------------------------end ftir --------------------------//
                } else {
                    document.getElementById("show_ftir").style.display = "none";
                }
                if (chklpcall >= 1) {
                    var lpcshow = '<tr>' +
                        '<td>LPC</td>' +
                        '<td align="center">' + lpc3 + '</td>' +

                        '<td align="center">' + lpc1 + '</td>' +

                        '<td align="center">' + lpc2 + '</td>' +

                        '<td align="center">' + lpc4 + '</td>' +

                        '<td align="center">' + lpc5 + '</td>' +

                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var lpcshow = '';
                }
                //  alert(lpcshow);
                if (chksemclass1all >= 1) {

                    var semclass1show = '<tr>' +
                        '<td>SEM Class1</td>' +
                        '<td align="center">' + semclass1_3 + '</td>' +
                        '<td align="center">' + semclass1_1 + '</td>' +
                        '<td align="center">' + semclass1_2 + '</td>' +
                        '<td align="center">' + semclass1_4 + '</td>' +
                        '<td align="center">' + semclass1_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass1show = '';
                }
                if (chksemclass2all >= 1) {
                    var semclass2show = '<tr>' +
                        '<td>SEM Class2</td>' +
                        '<td align="center">' + semclass2_3 + '</td>' +
                        '<td align="center">' + semclass2_1 + '</td>' +
                        '<td align="center">' + semclass2_2 + '</td>' +
                        '<td align="center">' + semclass2_4 + '</td>' +
                        '<td align="center">' + semclass2_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass2show = '';
                }
                if (chksemclass3all >= 1) {
                    var semclass3show = '<tr>' +
                        '<td>SEM Class3</td>' +
                        '<td align="center">' + semclass3_3 + '</td>' +
                        '<td align="center">' + semclass3_1 + '</td>' +
                        '<td align="center">' + semclass3_2 + '</td>' +
                        '<td align="center">' + semclass3_4 + '</td>' +
                        '<td align="center">' + semclass3_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass3show = '';
                }
                if (chksemclass31all >= 1) {
                    var semclass31show = '<tr>' +
                        '<td>SEM Class3.1</td>' +
                        '<td align="center">' + semclass31_3 + '</td>' +
                        '<td align="center">' + semclass31_1 + '</td>' +
                        '<td align="center">' + semclass31_2 + '</td>' +
                        '<td align="center">' + semclass31_4 + '</td>' +
                        '<td align="center">' + semclass31_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass31show = '';
                }
                if (chksemclass32all >= 1) {
                    var semclass32show = '<tr>' +
                        '<td>SEM Class3.2</td>' +
                        '<td align="center">' + semclass32_3 + '</td>' +
                        '<td align="center">' + semclass32_1 + '</td>' +
                        '<td align="center">' + semclass32_2 + '</td>' +
                        '<td align="center">' + semclass32_4 + '</td>' +
                        '<td align="center">' + semclass32_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass32show = '';
                }
                if (chksemclass33all >= 1) {
                    var semclass33show = '<tr>' +
                        '<td>SEM Class3.3</td>' +
                        '<td align="center">' + semclass33_3 + '</td>' +
                        '<td align="center">' + semclass33_1 + '</td>' +
                        '<td align="center">' + semclass33_2 + '</td>' +
                        '<td align="center">' + semclass33_4 + '</td>' +
                        '<td align="center">' + semclass33_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass33show = '';
                }
                if (chksemclass4all >= 1) {
                    var semclass4show = '<tr>' +
                        '<td>SEM Class4</td>' +
                        '<td align="center">' + semclass4_3 + '</td>' +
                        '<td align="center">' + semclass4_1 + '</td>' +
                        '<td align="center">' + semclass4_2 + '</td>' +
                        '<td align="center">' + semclass4_4 + '</td>' +
                        '<td align="center">' + semclass4_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass4show = '';
                }
                if (chksemclass5all >= 1) {
                    var semclass5show = '<tr>' +
                        '<td>SEM Class5</td>' +
                        '<td align="center">' + semclass5_3 + '</td>' +
                        '<td align="center">' + semclass5_1 + '</td>' +
                        '<td align="center">' + semclass5_2 + '</td>' +
                        '<td align="center">' + semclass5_4 + '</td>' +
                        '<td align="center">' + semclass5_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass5show = '';
                }
                if (chksemclass51all >= 1) {
                    var semclass51show = '<tr>' +
                        '<td>SEM Class5.1</td>' +
                        '<td align="center">' + semclass51_3 + '</td>' +
                        '<td align="center">' + semclass51_1 + '</td>' +
                        '<td align="center">' + semclass51_2 + '</td>' +
                        '<td align="center">' + semclass51_4 + '</td>' +
                        '<td align="center">' + semclass51_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass51show = '';
                }
                if (chksemclass6all >= 1) {
                    var semclass6show = '<tr>' +
                        '<td>SEM Class6</td>' +
                        '<td align="center">' + semclass6_3 + '</td>' +
                        '<td align="center">' + semclass6_1 + '</td>' +
                        '<td align="center">' + semclass6_2 + '</td>' +
                        '<td align="center">' + semclass6_4 + '</td>' +
                        '<td align="center">' + semclass6_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var semclass6show = '';
                }
                if (chkicanionall >= 1) {
                    var icanionshow = '<tr>' +
                        '<td>IC Anion</td>' +
                        '<td align="center">' + icanion3 + '</td>' +
                        '<td align="center">' + icanion1 + '</td>' +
                        '<td align="center">' + icanion2 + '</td>' +
                        '<td align="center">' + icanion4 + '</td>' +
                        '<td align="center">' + icanion5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var icanionshow = '';
                }
                if (chkicsulphatall >= 1) {
                    var icsulphatshow = '<tr>' +
                        '<td>IC Sulphat</td>' +
                        '<td align="center">' + icsulphat3 + '</td>' +
                        '<td align="center">' + icsulphat1 + '</td>' +
                        '<td align="center">' + icsulphat2 + '</td>' +
                        '<td align="center">' + icsulphat4 + '</td>' +
                        '<td align="center">' + icsulphat5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var icsulphatshow = '';
                }
                if (chkiccationall >= 1) {
                    var iccationshow = '<tr>' +
                        '<td>IC Cation</td>' +
                        '<td align="center">' + iccation3 + '</td>' +
                        '<td align="center">' + iccation1 + '</td>' +
                        '<td align="center">' + iccation2 + '</td>' +
                        '<td align="center">' + iccation4 + '</td>' +
                        '<td align="center">' + iccation5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var iccationshow = '';
                }
                if (chkgcms1all >= 1) {
                    var gcms1show = '<tr>' +
                        '<td>GCMS Outgassing</td>' +
                        '<td align="center">' + gcms1_3 + '</td>' +
                        '<td align="center">' + gcms1_1 + '</td>' +
                        '<td align="center">' + gcms1_2 + '</td>' +
                        '<td align="center">' + gcms1_4 + '</td>' +
                        '<td align="center">' + gcms1_5 + '</td>' +
                        '<td align="center">' + gcms1_6 + '</td>' +
                        '</tr>';
                } else {
                    var gcms1show = '';
                }
                if (chkgcms2all >= 1) {
                    var gcms2show = '<tr>' +
                        '<td>GCMS Smear</td>' +
                        '<td align="center">' + gcms2_3 + '</td>' +
                        '<td align="center">' + gcms2_1 + '</td>' +
                        '<td align="center">' + gcms2_2 + '</td>' +
                        '<td align="center">' + gcms2_4 + '</td>' +
                        '<td align="center">' + gcms2_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var gcms2show = '';
                }
                if (chkgcms3all >= 1) {
                    var gcms3show = '<tr>' +
                        '<td>GCMS Low MW</td>' +
                        '<td align="center">' + gcms3_3 + '</td>' +
                        '<td align="center">' + gcms3_1 + '</td>' +
                        '<td align="center">' + gcms3_2 + '</td>' +
                        '<td align="center">' + gcms3_4 + '</td>' +
                        '<td align="center">' + gcms3_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var gcms3show = '';
                }
                if (chkgcms4all >= 1) {
                    var gcms4show = '<tr>' +
                        '<td>GCMS High MW</td>' +
                        '<td align="center">' + gcms4_3 + '</td>' +
                        '<td align="center">' + gcms4_1 + '</td>' +
                        '<td align="center">' + gcms4_2 + '</td>' +
                        '<td align="center">' + gcms4_4 + '</td>' +
                        '<td align="center">' + gcms4_5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +

                        '</tr>';
                } else {
                    var gcms4show = '';
                }
                if (chkftirall >= 1) {
                    var ftirshow = '<tr>' +
                        '<td>FTIR</td>' +
                        '<td align="center">' + ftir3 + '</td>' +
                        '<td align="center">' + ftir1 + '</td>' +
                        '<td align="center">' + ftir2 + '</td>' +
                        '<td align="center">' + ftir4 + '</td>' +
                        '<td align="center">' + ftir5 + '</td>' +
                        '<td align="center"><i class="fa fa-check fa-1x" style="color:green;"></i></td>' +
                        '</tr>';
                } else {
                    var ftirshow = '';
                }
                //chkgcms4all
                var modelalert = lpcshow + semclass1show + semclass2show + semclass3show +
                    semclass31show + semclass32show + semclass33show +
                    semclass4show + semclass5show + semclass51show + semclass6show + icanionshow +
                    icsulphatshow + iccationshow +
                    gcms1show + gcms2show + gcms3show + gcms4show + ftirshow;

                //alert(test1);         
                $('#modelalert tbody').html(modelalert);
                $('#sumalertall').html(sumalertall);
                $('#test1').html(test1all);
                $('#test4').html(test4all);
                $('#test2').html(test2all);
                $('#test3').html(test3all);
                $('#test5').html(test5all);
                $('#test6').html(test6all);
                var allsumpopup = test1all+test4all+test2all+test3all+test5all+test6all;
                if (test1all > 0) {
                    $("#htest1").addClass("bg-c-pink");

                } else {
                    $("#htest1").addClass("bg-c-green");
                }

                if (test2all > 0) {
                    $("#htest2").addClass("bg-c-pink");

                } else {
                    $("#htest2").addClass("bg-c-green");
                }

                if (test3all > 0) {
                    $("#htest3").addClass("bg-c-pink");

                } else {
                    $("#htest3").addClass("bg-c-green");
                }

                if (test4all > 0) {
                    $("#htest4").addClass("bg-c-pink");

                } else {
                    $("#htest4").addClass("bg-c-green");
                }
                if (test5all > 0) {
                    $("#htest5").addClass("bg-c-pink");

                } else {
                    $("#htest5").addClass("bg-c-green");
                }
                if (test6all > 0) {
                    $("#htest6").addClass("bg-c-pink");

                } else {
                    $("#htest6").addClass("bg-c-green");
                }
                //$("#htest6").addClass("bg-c-green");
                if(allsumpopup > 0)
                {
                $('#myModal').modal('show');
                mybutton.style.display = "block";
                }
                else
                {
                    $('#myModal').modal('hide');
                    mybutton.style.display = "none";
                    $("#idshowbutton").val(1);
                }
                
                
            }
        });

    });



    function editDatalpc(id) {
       var  showeditalam = $('#showeditalam').val();
        window.location.href = "../../output/editdatalpc/" + id+'/2/'+showeditalam;
    }

    function editDatasem(id) {
        var  showeditalam = 0;
        window.location.href = "../../output/editdatasem/" + id+'/2/'+showeditalam;
    }

    function editDataic(id) {
        var  showeditalam = 0;
        window.location.href = "../../output/editdataic/" + id+'/2/'+showeditalam;
    }

    function editDatagcms(id) {
        var  showeditalam = 0;
        window.location.href = "../../output/editdatagcms/" + id+'/2/'+showeditalam;
    }

    function editDataftir(id) {
        var  showeditalam = 0;
        window.location.href = "../../output/editdataftir/" + id+'/2/'+showeditalam;
    }
    function formvs() {
         $('#textdate').val('');
        $('#textdetail').val('');
    }
    function addhistory(id) {
    $('#idhistory').val(id);
    formvs();
   }
   function allhistory(id) {
    var idcreatelab = $('#idcreatelab').val();
       $.ajax({
            type: 'get',
            url: "{{ url('addsavehistoryshow') }}",
            data: {
                'idcreatelab': idcreatelab,
                'idhistory': id,
                'chk': 2
            },
            success: function (data) {

                if (data.success == 1) {
                    var lpchistory = '';
                    $.each(data.history, function (v, item) {
                        lpchistory += '<tr>' +
                            '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                            '<td>' + Getdate(item.history_date) + '</td>' +
                            '<td>' + item.history_detail + '</td>' +          
                            '</tr>';
                    });
                    $('#lpchistoryall tbody').html(lpchistory);
                   
                } else {

                }
            }
        });
   }
   
   function addsavehistory() {

    var idcreatelab = $('#idcreatelab').val();
    var idhistory = $('#idhistory').val();
    var date = $('#textdate').val();
    var detail = $('#textdetail').val();
    $.ajax({
        type: 'get',
        url: "{{ url('addsavehistory') }}",
        data: 
        {
            'idcreatelab' : idcreatelab,
            'idhistory' : idhistory,
            'date' : date,
            'detail' : detail
        },
        success: function (data) {

            if (data.success == 1) {
                $('#default-Modal').modal('hide');
                swal("Success", "Submit add history success", "success");
                setTimeout(function () {
                    window.location.reload();
                        }, 1000);
               
                //var lpchistory ='';
                // $.each(data.history, function(v, item) {
                //      lpchistory += '<tr>' +
                //         '<td><butto style="padding: 2px 5px;" class="btn btn-danger" onclick="deletehistory('+item.id_history+')"><i class="fa fa-trash"></i></button></td>' +
                //         '<td>'+Getdate(item.history_date)+'</td>' +
                //         '<td>'+item.history_detail+'</td>' +
                //         '</tr>';
                // });
                // $('#lpchistory tbody').html(lpchistory);
            } else {

            }
        }
    });
   }
   function deletehistory(id) {
       var idcreatelab = $('#idcreatelab').val();
       $('#default-Modalall').modal('hide');
       swal({
               title: "Are you sure?",
               text: "You wont be able to revert this!",
               type: "error",
               showCancelButton: true,
               confirmButtonClass: "btn-danger",
               confirmButtonText: "Delete",
               closeOnConfirm: false
           },
           function () {

               $.ajax({
                   type: 'get',
                   url: "{{ url('deletehistory') }}",
                   data: {
                       'id': id,
                       'idcreatelab': idcreatelab,
                       'chk': 1
                   },
                   success: function (data) {
                       if (data.success == 1) {
                           var lpchistory = '';
                               swal("Success", "Submit Dalete history success", "success");
                               setTimeout(function () {
                                window.location.reload();}, 1000);
                         
                       } else {

                       }
                   }
               });
           });

   }

</script>
@endsection
