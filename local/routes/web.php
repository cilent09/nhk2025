
<?php

        use Illuminate\Support\Facades\File;
        use Illuminate\Support\Facades\Storage;
        use App\Mail\Mailnhk;
        use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */



Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return('สำเร็จ');
});

Auth::routes();
Route::get('/home','HomeController@index')->name('home');
Route::get('/', 'HomeController@index');
Route::get('setlanding/{type}', 'HomeController@setlanding');

// Route::get('/home', 'IncominglabController@index')->name('index');
// Route::get('/', 'IncominglabController@index');
//----------------------ModelController------------------------------//
Route::get('master/model', 'ModelController@index');
Route::get('master/modeldata', 'ModelController@indexdata');
Route::post('add_model', 'ModelController@add_model')->name('add_model');
Route::post('updatealarm', 'ModelController@updatealarm')->name('updatealarm');
Route::post('editmodelSubmit', 'ModelController@editmodelSubmit')->name('editmodelSubmit');
Route::get('master/manageralarm', 'ModelController@manageralarm');
Route::get('master/datatables', 'ModelController@datatable');
Route::get('master/datatableswd', 'ModelController@datatablewd');
Route::get('master/historydatatable', 'ModelController@historydatatable');
Route::get('master/addmodel', 'ModelController@addmodel')->name('addmodel');
Route::get('master/addmodeldata/{id}', 'ModelController@addmodeldata')->name('addmodeldata');
Route::get('master/editmodel/{id}/{chk}', 'ModelController@editmodel');
Route::get('master/editmodeldata/{id}/{idmodel}', 'ModelController@editmodeldata');
Route::get('master/viewmodel/{id}', 'ModelController@viewmodel');
Route::get('master/viewmodeldata/{id}/{idmodel}', 'ModelController@viewmodeldata');
Route::get('master/indexhistory/{id}', 'ModelController@indexhistory');
Route::post('deletemodelhtSubmit', 'ModelController@deletemodelhtSubmit');
Route::post('deletemodelSubmit', 'ModelController@deletemodelSubmit');
Route::post('addmodeldatasave', 'ModelController@addmodeldatasave');
Route::post('editmodeldatasave', 'ModelController@editmodeldatasave');

Route::get('master/tgahistory', 'ModelController@tgahistory');
Route::post('upload_tga', 'ExcelChartController@upload_tga')->name('upload_tga');
Route::get('reporttgadate', 'ExcelChartController@reporttgadate');

//-------- update Control limit 2024 -----
Route::get('master/newmodeldata', 'ModelController@newmodeldata');
Route::get('master/downloadcontrolfile/{type}', 'ModelController@downloadcontrolfile');
Route::get('master/createcontrol/{type}', 'ModelController@createcontrol');
Route::get('master/contollimittables/{type}', 'ModelController@contollimittables');
Route::post('getdateControllimit', 'ModelController@getdateControllimit');
Route::post('uplaodControlfile', 'ModelController@uplaodControlfile')->name('uplaodControlfile');
Route::post('updateControlfile', 'ModelController@updateControlfile')->name('updateControlfile');
Route::post('changestatuscontrol', 'ModelController@changestatuscontrol')->name('changestatuscontrol');
Route::post('deleteControllimit', 'ModelController@deleteControllimit');
Route::get('master/viewcontrollimit/{id}', 'ModelController@viewcontrollimit');
Route::get('master/controllimittable', 'ModelController@controllimittable');

Route::post('getmodelControllimit', 'ModelController@getmodelControllimit'); // NHK2 14/03/24

Route::get('master/createdate/{type}', 'ModelController@createdate');
Route::post('addmodelcreatedate', 'ModelController@addmodelcreatedate');

//--------- begin Dailycheck set up 2024 ---------
Route::get('master/dailycheckset', 'ModelController@dailycheckset');
Route::get('dailychecksetcreate', 'ModelController@dailychecksetcreate');
Route::post('createDailycheckset', 'ModelController@createDailycheckset')->name('createDailycheckset');
Route::get('dailychecksettable', 'ModelController@dailychecksettable');
Route::get('dailychecksetedit/{id}', 'ModelController@dailychecksetedit')->name('dailychecksetedit');
Route::post('updateDailycheckset', 'ModelController@updateDailycheckset')->name('updateDailycheckset');
Route::post('deleteDailycheckset', 'ModelController@deleteDailycheckset')->name('deleteDailycheckset');
//--------- end Dailycheck set up 2024 ---------


//--------- begin Monitoring set up 2024 ---------
Route::get('master/monitorset', 'ModelController@monitorset');
Route::get('monitorsetcreate', 'ModelController@monitorsetcreate');
Route::post('createMonitorset', 'ModelController@createMonitorset')->name('createMonitorset');
Route::get('monitorsettable', 'ModelController@monitorsettable');
Route::get('monitorsetedit/{id}', 'ModelController@monitorsetedit')->name('monitorsetedit');
Route::post('updateMonitorset', 'ModelController@updateMonitorset')->name('updateMonitorset');
Route::post('deleteMonitorset', 'ModelController@deleteMonitorset')->name('deleteMonitorset');

//--------- end Monitoring set up 2024 ---------

Route::get('master/calculateoption', 'ModelController@calculateoption')->name('calculateoption');
Route::get('master/combinedata', 'ModelController@combinedata')->name('combinedata');
Route::get('datatablecombine', 'CalculationController@datatablecombine');
Route::get('showcombinedata', 'CalculationController@showcombinedata');
Route::post('lpccombineexport', 'CalculationController@lpccombineexport')->name('lpccombineexport');
Route::post('semcombineexport', 'CalculationController@semcombineexport')->name('semcombineexport');
Route::post('iccombineexport', 'CalculationController@iccombineexport')->name('iccombineexport');
Route::post('gcmscombinesexport', 'CalculationController@gcmscombinesexport')->name('gcmscombinesexport');
Route::post('ftircombinesexport', 'CalculationController@ftircombinesexport')->name('ftircombinesexport');
Route::post('combinesexportall', 'CalculationController@combinesexportall')->name('combinesexportall');


//----------- Begin WD -------------
Route::get('master/addmodelWD', 'ModelController@addmodelWD')->name('addmodelWD');
Route::post('add_modelWD', 'ModelController@add_modelWD')->name('add_modelWD');
Route::get('master/viewmodelWD/{id}', 'ModelController@viewmodelWD');
Route::get('master/editmodelWD/{id}/{chk}', 'ModelController@editmodelWD');
Route::post('editmodelWDSubmit', 'ModelController@editmodelWDSubmit')->name('editmodelWDSubmit');
Route::post('createlabWDSubmit', 'IncominglabController@createlabWDSubmit')->name('createlabWDSubmit');

Route::get('output/rawWD', 'SCCController@raw');
Route::post('report_scc_raw', 'SCCController@report_scc_raw')->name('report_scc_raw');
Route::get('row/datatablesWD', 'SCCController@datatableWD');
Route::get('output/customerWD', 'SCCController@customer');
Route::post('report_scc_customer', 'SCCController@report_scc_customer')->name('report_scc_customer');

//------------ End WD --------------

//----------------------IncominglabController------------------------------//
Route::get('OQA/incoming', 'IncominglabController@index')->name('index');
Route::get('OQA/Createlab', 'IncominglabController@createlab')->name('createlab');
Route::get('chkmodel', 'IncominglabController@chkmodel');
Route::get('chklognumber', 'IncominglabController@chklognumber');
Route::post('chkdaterun', 'IncominglabController@chkdaterun');
Route::post('createlabSubmit', 'IncominglabController@createlabSubmit')->name('createlabSubmit');
Route::post('createlabSubmitsub', 'IncominglabController@createlabSubmitsub')->name('createlabSubmitsub');
Route::post('OQA/datatables', 'IncominglabController@datatable');
Route::get('OQA/generate_pdf', 'IncominglabController@generate_pdf')->name('generate_pdf');
Route::get('OQA/split/{id}', 'IncominglabController@split')->name('split');
Route::get('OQA/oqafile/{id}', 'IncominglabController@oqafile')->name('oqafile');
Route::get('OQA/uploadoqafile/{id}/{labname}/', 'IncominglabController@uploadoqafile')->name('uploadoqafile');
Route::post('uplaodoqaSubmit', 'IncominglabController@uplaodoqaSubmit')->name('uplaodoqaSubmit');
Route::get('chkpartnumber', 'IncominglabController@chkpartnumber')->name('chkpartnumber');
Route::get('chkconfirmlpc', 'IncominglabController@chkconfirmlpc');
Route::get('chkconfirmsem', 'IncominglabController@chkconfirmsem');
Route::get('chkconfirmic', 'IncominglabController@chkconfirmic');
Route::get('chkconfirmgcms', 'IncominglabController@chkconfirmgcms');
Route::get('chkconfirmftir', 'IncominglabController@chkconfirmftir');
Route::get('chkconfirmsemtest', 'IncominglabController@chkconfirmsemtest');
Route::post('deleteoqaSubmit', 'IncominglabController@deleteoqaSubmit');
Route::post('deleteoqaall', 'IncominglabController@deleteoqaall');
Route::post('deleteoqaallwd', 'IncominglabController@deleteoqaallwd');
Route::get('newchkconfirmgcms', 'IncominglabController@newchkconfirmgcms'); // new route chkconfirmgcms
Route::post('upload_lpc_jnhk', 'IncominglabController@upload_lpc_jnhk')->name('upload_lpc_jnhk');
Route::post('upload_gcms_jnhk', 'IncominglabController@upload_gcms_jnhk')->name('upload_gcms_jnhk');

Route::post('log_scanbarcode', 'IncominglabController@log_scanbarcode')->name('log_scanbarcode');


//----------- Begin WD -------------
Route::post('getModalList', 'IncominglabController@getModalList')->name('getModalList');
Route::post('OQA/datatables_wd', 'IncominglabController@datatables_wd');
Route::get('OQA/oqafilewd/{id}', 'IncominglabController@oqafilewd')->name('oqafilewd');
Route::get('OQA/uploadoqafileWD/{id}/{labname}/', 'IncominglabController@uploadoqafileWD')->name('uploadoqafileWD');
Route::post('uplaodoqaWDSubmit', 'IncominglabController@uplaodoqaWDSubmit')->name('uplaodoqaWDSubmit');

Route::get('chkconfirmlpcWD', 'IncominglabController@chkconfirmlpcWD');
Route::get('chkconfirmsemWD', 'IncominglabController@chkconfirmsemWD');
Route::get('chkconfirmicWD', 'IncominglabController@chkconfirmicWD');
Route::get('chkconfirmgcmsWD', 'IncominglabController@chkconfirmgcmsWD');
Route::get('chkconfirmftirWD', 'IncominglabController@chkconfirmftirWD');
Route::get('chkconfirmsemtestWD', 'IncominglabController@chkconfirmsemtestWD');
//------------ End WD --------------


//-----------------------OutputController------------------------------//
Route::get('output/results', 'OutputController@results');
Route::get('output/raw', 'OutputController@raw');
Route::get('output/calculation', 'OutputController@calculation');
Route::get('output/viewccp', 'OutputController@viewccp');
Route::get('output/customer', 'OutputController@customer');
Route::get('outputgraph', 'ResultsControlle@outputgraph');
Route::get('outputgraphratio', 'ResultsControlle@outputgraphratio');
Route::get('output/elementsem', 'OutputController@elementsem');

//--------------- Revise TEST#7 CAS2024 ---------------
Route::get('output/resultstest7', 'OutputController@resultstest7');     // 13/03/24
Route::get('outputgraphtest7', 'ResultsControlle@outputgraphtest7');    // 13/03/24
Route::get('newoutputgraphratio5', 'ResultsControlle@newoutputgraphratio5');    // 13/03/24
Route::get('output/viewdatatest7/{id}', 'OutputController@viewdatatest7')->name('viewdatatest7');       // 13/03/24
//--------------- Revise TEST#7 CAS2024 ---------------


//------------------------ test result ------------------------
Route::get('output/resultsnewversion', 'OutputController@resultsnewversion');
Route::get('outputgraphtest_test', 'TestController@outputgraphtest_test');


Route::get('newoutputgraphratio2', 'ResultsControlle@newoutputgraphratio2');
Route::get('newoutputgraphratio3', 'ResultsControlle@newoutputgraphratio3');
Route::get('newoutputgraphratio4', 'ResultsControlle@newoutputgraphratio4');

Route::get('outputgraphpdf', 'OutputController@outputgraphpdf');
Route::get('row/datatables', 'OutputController@datatable');
Route::get('customer/datatables', 'OutputController@datatablecustomer');
Route::get('outputrawgraph', 'ResultsrawControlle@outputrawgraph');
Route::get('slopediff_sem/{idcreatelab}/{class_index}', 'ResultsControlle@slopediff_sem')->name('slopediff_sem');


Route::get('output/editdatalpc/{id}/{chk}/{showeditalam}', 'OutputController@editdatalpc')->name('editdatalpc');
Route::get('output/editdatasem/{id}/{chk}/{showeditalam}', 'OutputController@editdatasem')->name('editdatasem');
Route::get('output/editdataic/{id}/{chk}/{showeditalam}', 'OutputController@editdataic')->name('editdataic');
Route::get('output/editdatagcms/{id}/{chk}/{showeditalam}', 'OutputController@editdatagcms')->name('editdatagcms');
Route::get('output/editdataftir/{id}/{chk}/{showeditalam}', 'OutputController@editdataftir')->name('editdataftir');
Route::get('output/editdatasemtest/{id}', 'OutputController@editdatasemtest')->name('editdatasemtest');
Route::post('editdatalpcsave', 'OutputController@editdatalpcsave');
Route::post('editdatasemsave', 'OutputController@editdatasemsave');
// 4.SGT AML return
Route::get('output/editdataamllpc/{id}', 'OutputController@editdataamllpc')->name('editdataamllpc');
Route::post('uploade_aml_lpc', 'OutputController@uploade_aml_lpc')->name('uploade_aml_lpc');

Route::get('output/editdataamlgcms/{id}', 'OutputController@editdataamlgcms')->name('editdataamlgcms');
Route::post('uploade_aml_gcms', 'OutputController@uploade_aml_gcms')->name('uploade_aml_gcms');
// 4.SGT AML return

Route::post('editdataicsave', 'OutputController@editdataicsave');
Route::post('editdatagcmssave', 'OutputController@editdatagcmssave');
Route::post('editdataftirsave', 'OutputController@editdataftirsave');
Route::post('editdatasemtestsave', 'OutputController@editdatasemtestsave');
Route::get('output/viewdata/{id}', 'OutputController@viewdata')->name('viewdata');
Route::get('output/viewdatacustomerall/{id}', 'OutputController@viewdataall')->name('viewdataall');
Route::get('output/viewdatarawall/{id}', 'OutputController@viewdatarawall')->name('viewdatarawall');
Route::get('output/export_pdf', 'OutputController@export_pdf')->name('export_pdf');
Route::post('output/raw_exportpdf', 'OutputController@raw_exportpdf')->name('raw_exportpdf');
Route::get('addsavehistory', 'OutputController@addsavehistory')->name('addsavehistory');
Route::get('addsavehistoryshow', 'OutputController@addsavehistoryshow')->name('addsavehistoryshow');
Route::get('pdfhistoryshow', 'OutputController@pdfhistoryshow')->name('pdfhistoryshow');
Route::get('deletehistory', 'OutputController@deletehistory')->name('deletehistory');
Route::get('showcountdata', 'OutputController@showcountdata')->name('showcountdata');
Route::get('showcountdatacustomer', 'OutputController@showcountdatacustomer')->name('showcountdatacustomer');

Route::post('raw_exportexcel', 'OutputController@rawexportexcel')->name('rawexportexcel');
Route::post('customer_exportexcel', 'OutputController@customerexportexcel')->name('customerexportexcel');
Route::post('raw_csvexcel', 'OutputController@raw_csvexcel')->name('raw_csvexcel');
Route::post('customer_csvexcel', 'OutputController@customer_csvexcel')->name('customer_csvexcel');
Route::post('raw_chkexportpdf', 'OutputController@raw_chkexportpdf')->name('raw_chkexportpdf');
Route::post('customer_chkexportpdf', 'OutputController@customer_chkexportpdf')->name('customer_chkexportpdf');
Route::post('output/customer_exportpdf', 'OutputController@customer_exportpdf')->name('customer_exportpdf');

Route::post('raw_excelratio', 'ExcelChartController@raw_excelratio')->name('raw_excelratio');
Route::post('customer_excelratio', 'ExcelChartController@customer_excelratio')->name('customer_excelratio');
Route::post('excelelementsem', 'ExcelChartController@excelelementsem')->name('excelelementsem');
Route::post('customer_elementsem', 'ExcelChartController@customer_elementsem')->name('customer_elementsem');


Route::post('test__customerexportexcel', 'OutputController@test__customerexportexcel')->name('test__customerexportexcel');
Route::post('test__raw_exportexcel', 'OutputController@test__raw_exportexcel')->name('test__raw_exportexcel');
Route::post('test__excelraw_exportexcel', 'ExcelChartController@test__excelraw_exportexcel')->name('test__excelraw_exportexcel');
Route::post('raw_overlaygraph', 'ExcelChartController@raw_overlaygraph')->name('raw_overlaygraph');


Route::post('calculationexport', 'CalculationController@calculationexport')->name('calculationexport');
Route::post('lpccalculationexport', 'CalculationController@lpccalculationexport')->name('lpccalculationexport');
Route::post('semcalculationexport', 'CalculationController@semcalculationexport')->name('semcalculationexport');
Route::post('iccalculationexport', 'CalculationController@iccalculationexport')->name('iccalculationexport');
Route::post('gcmscalculationexport', 'CalculationController@gcmscalculationexport')->name('gcmscalculationexport');
Route::post('ftircalculationexport', 'CalculationController@ftircalculationexport')->name('ftircalculationexport');
Route::get('row/datatablecalculation', 'CalculationController@datatablecalculation');
Route::get('showcalculation', 'CalculationController@showcalculation')->name('showcalculation');

Route::get('output/elementgcms', 'OutputController@elementgcms');
Route::post('gcmspossible', 'OutputController@gcmspossible');
Route::post('createCompoundgcms', 'OutputController@createCompoundgcms');
Route::post('createPossible', 'OutputController@createPossible');
Route::post('viewcompoundgcms', 'OutputController@viewcompoundgcms');
Route::post('deletepossible', 'OutputController@deletepossible');
Route::post('viewdatapossible', 'OutputController@viewdatapossible');
Route::post('updatePossible', 'OutputController@updatePossible');

Route::get('output/measure', 'OutputController@measure');
Route::get('viewmeasures','OutputController@viewmeasures')->name('viewmeasures');
Route::post('updateMeasures', 'OutputController@updateMeasures');
Route::get('measureedit/{id}/{year}/{status}', 'OutputController@measureedit');
Route::post('updateMeasuresedit', 'OutputController@updateMeasuresedit');
Route::get('reportmeasure', 'OutputController@reportmeasure');
Route::get('measuredatatables', 'OutputController@measuredatatables');
Route::post('excel_measure', 'OutputController@excel_measure')->name('excel_measure');


//--------- begin dailycheck 2024 ---------
Route::get('output/dailycheck', 'OutputController@dailycheck')->name('dailycheck');
Route::get('dailychecktable', 'OutputController@dailychecktable');
Route::get('dailycheckedit/{id}', 'OutputController@dailycheckedit');
Route::post('updateDailycheck', 'OutputController@updateDailycheck')->name('updateDailycheck');
Route::post('resetDailycheck', 'OutputController@resetDailycheck')->name('resetDailycheck');
Route::get('dailycheckview/{id}', 'OutputController@dailycheckview');
Route::get('output/reportdailycheck', 'OutputController@reportdailycheck');


Route::get('dailydatatables', 'OutputController@dailydatatables')->name('dailydatatables');
Route::post('excel_dayilycheckall', 'OutputController@excel_dayilycheckall');
Route::post('excel_dayilycheck', 'OutputController@excel_dayilycheck');
Route::post('table_dayilycheck', 'OutputController@table_dayilycheck');
Route::post('dailycheck_export', 'ExcelChartController@dailycheck_export')->name('dailycheck_export');

//--------- end dailycheck 2024 ---------

//--------- begin monitoring 2024 ---------
Route::get('output/monitoring', 'OutputController@monitoring')->name('monitoring');
Route::get('monitoringtable', 'OutputController@monitoringtable')->name('monitoringtable');
Route::get('monitoringedit/{id}', 'OutputController@monitoringedit');
Route::get('monitoringview/{id}', 'OutputController@monitoringview');
Route::post('updateMonitoring', 'OutputController@updateMonitoring')->name('updateMonitoring');

Route::get('output/reportmonitor', 'OutputController@reportmonitor');
Route::get('monitortables', 'OutputController@monitortables')->name('monitortables');
Route::post('table_monitor', 'OutputController@table_monitor');
Route::post('resetMonitoring', 'OutputController@resetMonitoring');
Route::post('excel_monitoringall', 'OutputController@excel_monitoringall');
Route::post('excel_monitoring', 'OutputController@excel_monitoring');
Route::post('monitoring_export', 'ExcelChartController@monitoring_export')->name('monitoring_export');
//--------- end monitoring 2024 ---------

// 5.Daily check and monitoring graph
Route::post('dailycheck_sear_graph', 'OutputController@dailycheck_sear_graph')->name('dailycheck_sear_graph');
Route::post('monitoring_sear_graph', 'OutputController@monitoring_sear_graph')->name('monitoring_sear_graph');
// 5.Daily check and monitoring graph

//----------------------ResultsControlle------------------------------//
Route::get('Results/datatables', 'ResultsControlle@datatable');
Route::get('ccpfileexcel','ResultsControlle@ccpfileexcel')->name('ccpfileexcel');

Route::get('newccpfileexcel','ResultsControlle@newccpfileexcel')->name('newccpfileexcel');
Route::get('newccpexcelsem','ResultsControlle@newccpexcelsem')->name('newccpexcelsem');
Route::get('newccpexcelic','ResultsControlle@newccpexcelic')->name('newccpexcelic');
Route::get('newccpexcelgcms','ResultsControlle@newccpexcelgcms')->name('newccpexcelgcms');
Route::get('newccpexcelftir','ResultsControlle@newccpexcelftir')->name('newccpexcelftir');
Route::get('newccpfileexcelghost','ResultsControlle@newccpfileexcelghost')->name('newccpfileexcelghost');
Route::get('newccpfileexcelall','ResultsControlle@newccpfileexcelall')->name('newccpfileexcelall');

Route::get('ccpfileexcelall','ResultsControlle@ccpfileexcelall')->name('ccpfileexcelall');
Route::get('ghostfileexcelall','ResultsControlle@ghostfileexcelall')->name('ghostfileexcelall');
Route::get('ccpexcelftir','ResultsControlle@ccpexcelftir')->name('ccpexcelftir');
Route::get('ccpexcelgcms','ResultsControlle@ccpexcelgcms')->name('ccpexcelgcms');
Route::get('ccpexcelsem','ResultsControlle@ccpexcelsem')->name('ccpexcelsem');
Route::get('ccpexcelic','ResultsControlle@ccpexcelic')->name('ccpexcelic');
Route::get('ccpfileexcelghost','ResultsControlle@ccpfileexcelghost')->name('ccpfileexcelghost');
Route::get('viewpara','ResultsControlle@viewpara')->name('viewpara');

//----------------------UserControlle------------------------------//
Route::get('logoutuser', 'UserController@logout');
Route::get('user','UserController@index');
Route::get('user/datatables', 'UserController@datatable');
Route::get('user/adduser', 'UserController@adduser');
Route::post('user/saveadduser', 'UserController@saveadduser');
Route::post('user/saveedituser', 'UserController@saveedituser');
Route::get('user/chkemail', 'UserController@chkemail');
Route::get('user/chkeditemail', 'UserController@chkeditemail');
Route::get('user/edituser/{id}', 'UserController@edituser');
Route::get('user/viewuser/{id}', 'UserController@viewuser');
Route::get('user/userdelete', 'UserController@userdelete');

//----------------------SupportControlle------------------------------//
Route::get('support/help', 'SupportController@help');
Route::get('support/instruction', 'SupportController@instruction');
Route::get('support/needsupport', 'SupportController@needsupport');
Route::post('excelraw_exportexcel', 'ExcelChartController@excelraw_exportexcel')->name('excelraw_exportexcel');
Route::post('excelc_exportexcel', 'ExcelChartController@excelc_exportexcel')->name('excelc_exportexcel');
Route::get('support/editteam/{id}', 'SupportController@editteam');
Route::post('support/saveeditteam', 'SupportController@saveeditteam')->name('saveeditteam');

//----------------------QueueingController------------------------------//
Route::get('queue', 'QueueController@index')->name('queue');
Route::post('datatablesqueue', 'QueueController@datatablesqueue');
Route::get('queue-view/{id}', 'QueueController@view');
Route::get('queue-edit/{id}', 'QueueController@edit');
Route::post('acceptanceQueue', 'QueueController@acceptanceQueue')->name('acceptanceQueue');
Route::post('sempleQueue', 'QueueController@sempleQueue')->name('sempleQueue');
Route::post('queue_edit_deadline', 'QueueController@queue_edit_deadline')->name('queue_edit_deadline');
Route::post('queue-save-measure', 'QueueController@save_measure')->name('save_measure');
Route::post('queue-measure-complete', 'QueueController@save_measure_complete')->name('save_measure_complete');
Route::post('queue-save-summary', 'QueueController@save_summary')->name('save_summary');
Route::post('queue_search_top', 'QueueController@queue_search_top')->name('queue_search_top');
Route::post('select_info_queue', 'QueueController@select_info_queue')->name('select_info_queue');

Route::get('queue/add', 'QueueController@add');
Route::post('createQueue', 'QueueController@createQueue')->name('createQueue');
Route::post('updateQueue', 'QueueController@updateQueue')->name('updateQueue');
Route::post('deletequeue', 'QueueController@deletequeue')->name('deletequeue');

//----------------------SCCController------------------------------//

Route::post('scc_add_file', 'SCCController@scc_add_file')->name('scc_add_file');
Route::post('get_scc_file_template', 'SCCController@get_scc_file_template')->name('get_scc_file_template');

//----------------------TestlabController------------------------------//
Route::get('test/addlpcfile', 'TestlabController@addlpcfile');

Route::get('mail/mailoqa',function()
{


});

Route::get('testlpclp', 'ResultsControlle@testlpclp');
Route::get('Testratio4', 'ResultsControlle@Testratio4');

Route::get('testchkconfirmgcms', 'TestController@testchkconfirmgcms');
Route::get('testgcms/{createlab}/{model}', 'TestController@testgcms');

Route::get('testcode/{id}', 'TestController@testcode');
Route::post('save_chart', 'TestController@save_chart');
Route::get('testcode2', 'TestController@testcode2');


Route::get('update_lt_manual_lpc', 'TestController@update_lt_manual_lpc');
Route::get('update_lt_manual_sem', 'TestController@update_lt_manual_sem');
Route::get('update_lt_manual_ic', 'TestController@update_lt_manual_ic');
Route::get('update_lt_manual_gcms', 'TestController@update_lt_manual_gcms');
Route::get('update_lt_manual_ftir', 'TestController@update_lt_manual_ftir');
Route::get('update_lt_manual_ghost', 'TestController@update_lt_manual_ghost');










