@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Monitoring update')
@section('breadcrumb-title', 'Monitoring update')

@section('content')
 
<style>
#dailyGraph {
    width: 100% !important; /* ให้ขนาดเต็ม */
    height: 260px !important; /* กำหนดความสูง */
}
</style>

<div class="row">
    <div class="col-sm-4">
        <div class="card">
            <form method="post" id="my_form" target="_blank">
            {{ csrf_field() }}
                <div class="card-block">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Start date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>End date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control">
                            </div>
                            <div class="form-group text-center">
                                <button type="button" id="chk" class="btn btn-success btnSearch"><i class="fa fa-search"></i> Search</button>
                                <button type="button" class="btn btn-success btnExport"><i class="fa fa-file-excel-o"></i> Export</button>
                            </div>
                        </div> 
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-3">
        <div class="card">
            <div class="card-block">
                <div class="row">
                    <div class="col-12">
                        <canvas id="dailyGraph" width="223" height="223"></canvas>
                    </div> 
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-5">
        <div class="card" style="max-height: 300px;overflow: auto;">
            <div class="card-block">
                <div class="row">
                    <div class="col-12">
                        Machine status
                    </div> 
                    <div class="col-12 mt-2">
                       <div id="forprogressbar">
                            @foreach($progress as $val)
                            <div class="form-group">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{$val['percent']}}%;" aria-valuenow="{{$val['percent']}}" aria-valuemin="0" aria-valuemax="100">{{$val['name']}} {{$val['percent']}}%</div>
                                </div>
                            </div> 
                            @endforeach
                       </div> 
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="totalJobs" name="totalJobs" value="{{ $allJobday }}">
<input type="hidden" id="doneJobs" name="doneJobs" value="{{ $doneJobs }}">
<input type="hidden" id="ongoingJobs" name="ongoingJobs" value="{{ $ongoingJobs }}">


<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block">
                <div class="dt-responsive table-responsive">
                    <div style="text-align: right">
                        @if(Auth::user()->status == 1)
                        <button type="button" id="deleteinfo" class="btn btn-danger btn-sm">Delete</button>
                        @endif
                    </div>
                    <br>

                    @if(Auth::user()->status == 1)
                        <table valign="bottom" id="dt" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center"><input type="checkbox" name="checkAll" id="checkAll"></th>
                                    <th class="text-center">Setting</th>
                                    <th class="text-center">Tester</th>
                                    <th class="text-center">Topic</th>
                                    <th class="text-center">Due date</th>
                                    <th class="text-center">Record time</th>
                                    <th class="text-center">Operator</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Result</th>
                                    <th class="text-center">Problem</th>
                                    <th class="text-center">Root cause</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    @else
                        <table valign="bottom" id="dt" class="table table-striped table-bordered nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Setting</th>
                                    <th class="text-center">Tester</th>
                                    <th class="text-center">Topic</th>
                                    <th class="text-center">Due date</th>
                                    <th class="text-center">Record time</th>
                                    <th class="text-center">Operator</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Result</th>
                                    <th class="text-center">Problem</th>
                                    <th class="text-center">Root cause</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    @endif


                </div>
            </div>

        </div>
    </div>
</div>


@endsection
@section('scripts')

<script src="{{ asset('template/bower_components/chart.js/js/chartjs.js') }}"></script>
<script src="{{ asset('template/bower_components/chart.js/js/chartjs-plugin-datalabels.js') }}"></script>

<script>

    $(document).ready(function() {
        @if(Auth::user()->status == 1)
            var oTable = $('#dt').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                lengthChange: true,
                ajax: {
                    url: "{{ url('monitoringtable') }}",
                    "data": function (d) {
                        d.date_start = $("#start_date").val();
                        d.date_end = $("#end_date").val();
                    }
                },
                columns: [
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'name_tester', name: 'name_tester'},
                    {'className': "text-center", data: 'topic', name: 'topic'},
                    {'className': "text-center", data: 'due_date', name: 'due_date'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                ],
                "columnDefs": [
                    { "bSortable": false, "aTargets": [ 0 ] }, 
                    { "bSearchable": false, "aTargets": [ 0 ] }
                ],
                rowCallback: function (row, data, index) {
                    var button = '';
                    
                    var problem = [];
                    var rootcause = [];
                    var action = [];
                        
                    $('td:eq(0)', row).html('<input type="checkbox" name="checkbox[]" value="'+data.id_mnf+'">');
                    $('td:eq(5)', row).html('-');
                    $('td:eq(6)', row).html('-');
                    $('td:eq(7)', row).html('-');
                    $('td:eq(8)', row).html('-');
                    $('td:eq(9)', row).html('-');
                    $('td:eq(10)', row).html('-');
                    $('td:eq(11)', row).html('-');

                    @if(Auth::user()->status != 2)
                        button += '<a href="../monitoringedit/'+data.id_mnf+'"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    @else
                        button += '<a href="../monitoringview/'+data.id_mnf+'"><button class="btn btn-info btn-sm"><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                    @endif

                    $('td:eq(1)', row).html(button);

                    // Check status Column
                    if(data.recoredtime == null){
                        $('td:eq(7)', row).html('<button class="btn btn-warning btn-sm">On Going</button>');
                    }else{

                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            $('td:eq(7)', row).html('<button class="btn btn-primary btn-sm">DONE<br>(Delay)</button>');
                        }else{
                            $('td:eq(7)', row).html('<button class="btn btn-success btn-sm">DONE</button>');
                        }
                        
                    }

                    if(data.recoredtime != null){

                        // Check operation
                        $('td:eq(6)', row).html(data.operation);

                        // Check Result Column
                        if(data.results == "PASS"){
                            $('td:eq(8)', row).html('<button class="btn btn-success btn-sm">PASS</button>');
                        }else if(data.results == "NOT_PASS"){
                            $('td:eq(8)', row).html('<button class="btn btn-danger btn-sm">NOT PASS</button>');
                        }else if(data.results == "DONOTUSE"){
                            $('td:eq(8)', row).html("<button class='btn btn-secondary btn-sm'>DON'T USE</button>");
                        }

                        // Check Recored time Column
                        if(data.recoredtime != null){
                            $('td:eq(5)', row).html(data.recoredtime);
                        }

                        // Check Recored time Column
                        if(data.recoredtime != null){
                            $('td:eq(5)', row).html(data.recoredtime);
                        }

                        // // Check problem Column
                        // if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                        //     problem.push(data.problem);
                        // }
                        // if(data.results != "PASS"){
                        //     problem.push(data.problem_result);
                        // }
                        var txt_problem = data.problem;
                        if (txt_problem) {
                            problem.push(data.problem);
                        }
                        var txt_problem_result = data.problem_result;
                        if (txt_problem_result) {
                            problem.push(data.problem_result);
                        }

                        // // Check Root cause Column
                        // if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                        //     rootcause.push(data.rootcause);
                        // }
                        // if(data.results != "PASS"){
                        //     rootcause.push(data.rootcause_result);
                        // }
                        var txt_rootcause = data.rootcause;
                        if (txt_rootcause) {
                            rootcause.push(data.rootcause);
                        }
                        var txt_rootcause_result = data.rootcause_result;
                        if (txt_rootcause_result) {
                            rootcause.push(data.rootcause_result);
                        }

                        // // Check action Column
                        // if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                        //     action.push(data.action);
                        // }
                        // if(data.results != "PASS"){
                        //     action.push(data.action_result);
                        // }
                        var txt_action = data.action;
                        if (txt_action) {
                            action.push(data.action);
                        }
                        var txt_action_result = data.action_result;
                        if (txt_action_result) {
                            action.push(data.action_result);
                        }
                        

                        if(problem.length > 0){
                            $('td:eq(9)', row).html( problem.join(' <br />| ') );
                        }
                        if(rootcause.length > 0){
                            $('td:eq(10)', row).html( rootcause.join(' <br />| ') );
                        }
                        if(action.length > 0){
                            $('td:eq(11)', row).html( action.join(' <br />| ') );
                        }

                    }

                }
            }); 
        @else
            var oTable = $('#dt').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                lengthChange: true,
                ajax: {
                    url: "{{ url('monitoringtable') }}",
                    "data": function (d) {
                        d.date_start = $("#start_date").val();
                        d.date_end = $("#end_date").val();
                    }
                },
                columns: [
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'name_tester', name: 'name_tester'},
                    {'className': "text-center", data: 'topic', name: 'topic'},
                    {'className': "text-center", data: 'due_date', name: 'due_date'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                    {'className': "text-center", data: 'id_mnf', name: 'id_mnf'},
                ],
                rowCallback: function (row, data, index) {
                    var button = '';
                    
                    var problem = [];
                    var rootcause = [];
                    var action = [];
                        
                    $('td:eq(4)', row).html('-');
                    $('td:eq(5)', row).html('-');
                    $('td:eq(6)', row).html('-');
                    $('td:eq(7)', row).html('-');
                    $('td:eq(8)', row).html('-');
                    $('td:eq(9)', row).html('-');
                    $('td:eq(10)', row).html('-');

                    if(data.countqccheck < 2){
                        button += '<a href="../monitoringedit/'+data.id_mnf+'"><button class="btn btn-warning btn-sm"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    }else{
                        button += '<a href="#"><button class="btn btn-secondary btn-sm"><i class="fa fa-pencil" style="font-size:18px;"></i></button></a>&nbsp;';
                    }
                    // button += '<a href="../monitoringview/'+data.id_mnf+'"><button class="btn btn-info btn-sm"><i class="fa fa-search" style="font-size:18px;"></i></button></a>&nbsp;';
                     
                    $('td:eq(0)', row).html(button);

                    // Check status Column
                    if(data.recoredtime == null){
                        $('td:eq(6)', row).html('<button class="btn btn-warning btn-sm">On Going</button>');
                    }else{

                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            $('td:eq(6)', row).html('<button class="btn btn-primary btn-sm">DONE<br>(Delay)</button>');
                        }else{
                            $('td:eq(6)', row).html('<button class="btn btn-success btn-sm">DONE</button>');
                        }
                        
                    }

 
                    if(data.recoredtime != null){

                        // Check operation
                        $('td:eq(5)', row).html(data.operation);

                        // Check Result Column
                        if(data.results == "PASS"){
                            $('td:eq(7)', row).html('<button class="btn btn-success btn-sm">PASS</button>');
                        }else if(data.results == "NOT_PASS"){
                            $('td:eq(7)', row).html('<button class="btn btn-danger btn-sm">NOT PASS</button>');
                        }else if(data.results == "DONOTUSE"){
                            $('td:eq(7)', row).html("<button class='btn btn-secondary btn-sm'>DON'T USE</button>");
                        }

                        // Check Recored time Column
                        if(data.recoredtime != null){
                            $('td:eq(4)', row).html(data.recoredtime);
                        }

                        // Check Recored time Column
                        if(data.recoredtime != null){
                            $('td:eq(4)', row).html(data.recoredtime);
                        }

                        // Check problem Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            problem.push(data.problem);
                        }
                        if (data.problem_result != null) {
                            problem.push(data.problem_result);
                        }

                        // Check Root cause Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            rootcause.push(data.rootcause);
                        }
                        if (data.rootcause_result != null) {
                            rootcause.push(data.rootcause_result);
                        }

                        // Check action Column
                        if(data.recoredtime > data.due_date){ // ส่งล่าช้า
                            action.push(data.action);
                        }
                        if (data.action_result != null) {
                            action.push(data.action_result);
                        }

                        if(problem.length > 0){
                            $('td:eq(8)', row).html( problem.join(' <br />| ') );
                        }
                        if(rootcause.length > 0){
                            $('td:eq(9)', row).html( rootcause.join(' <br />| ') );
                        }
                        if(action.length > 0){
                            $('td:eq(10)', row).html( action.join(' <br />| ') );
                        }

                    }

                }
            }); 
        @endif
        $('.btnSearch').click(function(){
            oTable.ajax.reload();
        });

        $("#checkAll").click(function(){
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });


    $('.btnExport').click(function(){ 

        swal({
            title: "",
            text: "Please wait..",
            imageUrl: "{{ url('local/img_g/loadexcel.gif') }}",
            showConfirmButton: false
        });

        var forms = $('#my_form')[0];
        var data = new FormData(forms);

        $.ajax({
            type: 'post',
            url: "{{ url('monitoring_export')}}",
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function (data) {
                    
                if (data.success == 1) { 
                    swal.close();
                    window.location.href = "../local/storage/NHK-File/report/monitoring/"+data.filename;  
                }
                        
                        
            }
        });

        });    
    

    $('#deleteinfo').click(function () {

        var idv = [];
        $('input:checked').each(function() {
           idv.push(this.value); 
        });

        console.log(idv);

        try {
          
            swal({
                title: "Are you sure?",
                text: "Confirm delete ?",
                background: 'red',
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true
            },
            function () {
                $.ajax({
                    url: "{{ url('resetMonitoring') }}",
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        "id": idv,
                        "_token": '{{ csrf_token() }}',
                    },
                    success: function(data) {

                        if (data.success == 1) {
                            swal("Successed", "reset data successed", "success");
                            setTimeout(function () {
                                location.reload();
                            }, 1000);
                        } else {

                        }

                    }
                });
            });

        } catch (e) {
          // จัดการกับข้อผิดพลาด
          console.log(e.message);
        }

    });

</script>

<script>
    // ดึงค่า
    const totalJobs = $('#totalJobs').val();
    const doneJobs = $('#doneJobs').val();
    const ongoingJobs = $('#ongoingJobs').val(); 
 
    $(document).ready(function () {
        // ✅ Plugin เพื่อแสดงค่า Total ตรงกลางของ Doughnut Chart
        Chart.register({
            id: "centerText",
            beforeDraw: function (chart) {
                if (chart.config.type === "doughnut") {
                    var width = chart.width,
                        height = chart.height,
                        ctx = chart.ctx;

                    ctx.restore();
                    var fontSize = (height / 210).toFixed(2);
                    ctx.font = fontSize + "em Arial";
                    ctx.textBaseline = "middle";
                    var total = chart.data.datasets[0].data.reduce((a, b) => parseInt(a) + parseInt(b), 0);
                    var text = "Total: " + total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    var textX = Math.round((width - ctx.measureText(text).width) / 2);
                    var textY = height / 2;

                    ctx.fillStyle = "#000"; // สีของข้อความ
                    ctx.fillText(text, textX, textY);
                    ctx.save();
                }
            }
        });

        // ดึง reference ไปที่ <canvas> และสร้างตัวแปรสำหรับกราฟ
        var areaChartCanvas = document.getElementById("dailyGraph");
        var myChart = new Chart(areaChartCanvas, {
            type: 'doughnut',
            data: {
                labels: ["Done", "On Going"],
                datasets: [{
                    data: [doneJobs, ongoingJobs],
                    backgroundColor: ["#00a65a", "#f39c12"],
                    hoverBackgroundColor: ["#00a65a", "#f39c12"]
                }]
            },
            options: {
                responsive: true, 
                maintainAspectRatio: false, // ✅ ปิดการคงอัตราส่วน
                cutoutPercentage: 40,
                plugins: {  // ✅ ใช้ plugins ภายใต้ options
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        enabled: true
                    },
                    datalabels: {  // ✅ ตรวจสอบว่ามี ChartDataLabels
                        color: 'white',
                        anchor: 'center',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value, context) {

                            var dataset = context.dataset;
                            console.log("dataset.data:", dataset.data);
                            var total = dataset.data.reduce(function(prev, curr) {
                                return parseInt(prev) + parseInt(curr);
                            }, 0);
                            // console.log("Total:", total); // ✅ เช็กค่า total
                            var percentage = Math.round((value / total) * 100);
                            // console.log("Value:", value, "Percentage:", percentage); // เช็กค่าก่อน
                            return value + ' (' + percentage + '%)';
                        }
                    }
                }
            },
            plugins: [ChartDataLabels, "centerText"]  // ✅ เพิ่ม plugin แสดง Total
        });

        // ฟังก์ชันอัปเดตข้อมูลกราฟ
        function updateChart(newData) {
            myChart.data.datasets[0].data = newData; // อัปเดตข้อมูล
            myChart.update(); // รีเฟรชกราฟ
        }

        // กดปุ่มแล้วโหลดข้อมูลใหม่จาก AJAX
        $("#chk").on("click", function () {
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            
            $.ajax({
                type: 'POST',
                url: `{{ url('monitoring_sear_graph') }}`,
                dataType: 'json',
                data : { 
                    start_date : start_date,
                    end_date : end_date,
                    "_token" : "{{ csrf_token() }}",
                },
                success: function (response) {
                    if (response.data) {
                        updateChart(response.data);

                        $('#forprogressbar').empty();

                        var table = '';
                        $.each(response.progress, function (i, item) {
                            table += '<div class="form-group">';
                                table += '<div class="progress">';
                                    table += '<div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: '+item.percent+'%;" aria-valuenow="'+item.percent+'" aria-valuemin="0" aria-valuemax="100">'+item.name+' '+item.percent+'%</div>';
                                table += '</div>';
                            table += '</div>'; 
                        }); 
                        $("#forprogressbar").append(table);

                    } else {
                        alert("ไม่พบข้อมูลใหม่");
                    }
                },
                error: function () {
                    alert("เกิดข้อผิดพลาดในการดึงข้อมูล");
                }
            });
        });
    });


</script>
@endsection
