
@extends('layouts.main')
@section('title', 'Help')
@section('breadcrumb-title-href', '#')
@section('breadcrumb-title', 'Help')

@section('content')

<style>

.round{
    background-color:white;
    width:220px;
    height:auto;
    border-top-left-radius: 45px 40px;
    border-top-right-radius: 45px 40px;
    border-bottom-left-radius: 45px 40px;
    border-bottom-right-radius: 45px 40px;
}

</style>
<div class="card">
    <div class="card-header">
        <h4> SPC control</h4>
        <br> 
        <div class="card-block">
          <div class="dt-responsive table-responsive">
              <table width="100%" class="table table-striped table-bordered nowrap" id="datatables">
                <thead>
                    <tr>
                        <th rowspan="2" style="background-color: brown;color: honeydew;text-align: center" width="5%">
                            Test
                        </th>
                        <th rowspan="2" style="background-color: brown;color: honeydew;text-align: center" width="20%">
                            Rules
                        </th>
                        <th colspan="5" style="background-color: brown;color: honeydew;text-align: center" width="20%">
                          Action
                        </th>
                    </tr>
                    <tr>
                        <th style="background-color: brown;color: honeydew;text-align: center" width="15%">
                            Man
                        </th>
                        <th style="background-color: brown;color: honeydew;text-align: center" width="15%">
                            Machines
                        </th>
                        <th style="background-color: brown;color: honeydew;text-align: center" width="15%">
                            Materials
                        </th>
                        <th style="background-color: brown;color: honeydew;text-align: center" width="15%">
                            Method
                        </th>
                        <th style="background-color: brown;color: honeydew;text-align: center" width="15%">
                            Environment
                        </th>
                    </tr>
                </thead>
                <tbody>
                  <tr>
                      <td width="5%">
                          TEST #1
                      </td>
                      <td  width="20%">
                          One point is over upper control limit (UCL) or <br>lower control limit (LCL).<br><br>
                          <img class="img-fluid" width="90%"  src="{{ asset('template/assets/images/test1.jpg')}}" alt="Theme-Logo" />
                      </td>
                      <td width="15%">
                        1. New operators<br>
                        2. Inadequate training<br>
                        3. Operator interrupted <br>or distracted<br>
                        4. Operator overcompensating <br>when making process adjustments
                      </td>
                      <td width="15%">
                        1. Improper start-up<br>
                        2. Improper setup<br>
                        3. Sudden support system <br>failure (cooling, heating, <br>compressed air, vacuum, steam, etc.)<br>
                        4. Tool failure/breakage<br>
                        5. Equipment or <br>machine failure<br>
                        6. Improper equipment, <br>machine, and tooling maintenance<br>
                        7. Utility interruption<br>
                        8. Equipment has not <br>stabilized (warmed-up)<br>
                        9. Inadequate work <br>instructions<br>
                        10. Missed process step<br>
                        11. New process                      
                      </td>
                      <td width="15%">
                        1. Change in raw materials<br>
                        2. Change in components<br>
                        3. Handling damage<br>
                        4. Expired materials<br>
                      </td>
                      <td width="15%">
                        1. Inspection, measuring, <br>and testing equipment not <br>properly calibrated<br>
                        2. Damaged inspection, <br>measuring, and testing equipment<br>
                      </td>
                      <td width="15%">
                        1. Temperature suddenly <br>too low/high<br>
                        2. Humidity suddenly <br>too low/high<br>
                      </td>


                  </tr>
                  <tr>
                      <td>
                          TEST #2
                      </td>
                      <td>
                          Nine or more points are tops or<br> bottoms of the center line.
                          <br><br>
                          <img class="img-fluid" width="90%"src="{{ asset('template/assets/images/test2.jpg')}}" alt="Theme-Logo" />
                      </td>
                      <td>
                        1. New operators <br> 
                        2. Inadequate training<br>
                        3. Operator interrupted <br>or distracted<br>
                        4. Shift change
                      </td>
                      <td>
                        1. Improper setup<br>
                        2. Improper equipment,<br> machine, and tooling maintenance<br>
                        3. Damaged tooling<br>
                        4. Tool wear<br>
                        5. New process parameters<br>
                        6. Incorrect process <br>parameters<br>
                        7. Process has improved<br>
                        8. Process has degraded<br>
                      </td>
                      <td>
                        1. Change in raw materials<br>
                        2. Change in components<br>
                        3. Expired materials<br>
                      </td>
                      <td>
                        1. Inspection, measuring, <br>and testing equipment <br>not properly calibrated<br>
                        2. Damaged inspection, <br>measuring, and testing equipment<br>
                      </td>
                      <td>
                        1. Temperature suddenly <br>too low/high<br>
                        2. Humidity suddenly <br>too low/high
                      </td>

                  </tr>
                  <tr>
                      <td>
                          TEST #3
                      </td>
                      <td>
                          Six or more points are increased <br>or decreased continuously.
                          <br><br>
                          <img class="img-fluid" width="90%"src="{{ asset('template/assets/images/test3.jpg')}}" alt="Theme-Logo" />
                      </td>
                      <td>
                        1. Operator distracted
                      </td>
                      <td>
                        1. Calibration issuse<br>
                        2. Gauge drift<br>
                        3. Time related testing issues<br>
                        4. loss of sentivity in gauges<br>
                        5. Gradual support system <br>failure (cooling, heating, <br>compressed air, vacuum, steam, <br>etc.)<br>
                        6. Tool wear<br>
                        7. Process is slowly degrading<br>

                      </td>
                      <td>
                        1. Variation in the <br>raw materials<br>
                        2. Variation in the <br>components
                      </td>
                      <td>
                        1. Inspection, measuring,<br> and testing equipment not<br> adequate for the intended use<br>
                        2. Damaged inspection, <br>measuring, and testing equipment<br>
                      </td>
                      <td>
                        1. Temperature suddenly<br> too low/high<br>
                        2. Humidity suddenly <br>too low/high
                      </td>

                  </tr>
                  <tr>
                      <td>
                          TEST #4
                      </td>
                      <td>
                          One point is over the internal <br>control limits (more tighten than UCL).
                      </td>
                      <td>1. New operators<br>
                        2. Inadequate training<br>
                        3. Operator interrupted<br>
                        or distracted<br>
                        4. Operator overcompensating<br>
                        when making process adjustments</td>
                      <td>1. Improper start-up<br>
                        2. Improper setup<br>
                        3. Sudden support systemv
                        failure <br>(cooling, heating,<br>
                        compressed air,<br> vacuum, steam,
                        etc.)<br>
                        4. Tool failure/breakage<br>
                        5. Equipment or<br>
                        machine failure<br>
                        6. Improper equipment,<br>
                        machine, and tooling maintenance<br>
                        7. Utility interruption<br>
                        8. Equipment has not<br>
                        stabilized (warmed-up)<br>
                        9. Inadequate work<br>
                        instructions<br>
                        10. Missed process step<br>
                        11. New process
                        </td>
                      <td>1. Change in raw materials<br>
                        2. Change in components<br>
                        3. Handling damage<br>
                        4. Expired materials</td>
                      <td>1. Inspection, measuring,<br>
                        and testing equipment not<br>
                        properly calibrated<br>
                        2. Damaged inspection,<br>
                        measuring, and testing equipment</td>
                      <td>1. Temperature suddenly<br>
                        too low/high<br>
                        2. Humidity suddenly<br>
                        too low/high
                        </td>
                  </tr>
                  <tr>
                      <td>
                          TEST #5
                      </td>
                      <td>
                        One point is out of<br>
                        slope-diff specification.<br>
                        - Specification : Slope ≤ 0.5, Diff ≤ 3.5<br>
                        (Data Q'ty for calculation : Slope ≥ 7 data, Diff ≥ 3 data)

                      </td>
                      <td>1. New operators<br>
                        2. Inadequate training<br>
                        3. Operator interrupted<br>
                        or distracted<br>
                        4. Operator overcompensating<br>
                        when making process adjustments</td>
                      <td>1. Improper start-up<br>
                        2. Improper setup<br>
                        3. Sudden support system<br>
                        failure (cooling, heating,<br>
                        compressed air, vacuum, steam,<br>
                        etc.) 4. Tool failure/breakage<br>
                        5. Equipment or<br>
                        machine failure<br>
                        6. Improper equipment,<br>
                        machine, and tooling maintenance<br>
                        7. Utility interruption<br>
                        8. Equipment has not<br>
                        stabilized (warmed-up)<br>
                        9. Inadequate work<br>
                        instructions<br>
                        10. Missed process step<br>
                        11. New process</td>
                      <td>1. Change in raw materials<br>
                        2. Change in components<br>
                        3. Handling damage<br>
                        4. Expired materials<br>
                        5. Variation in the<br>
                        raw materials<br>
                        6. Variation in the<br>
                        components
                        </td>
                      <td>1. Inspection, measuring,<br>
                        and testing equipment not<br>
                        properly calibrated<br>
                        2. Damaged inspection,<br>
                        measuring, and testing equipment</td>
                      <td>1. Temperature suddenly<br>
                        too low/high<br>
                        2. Humidity suddenly<br>
                        too low/high
                        </td>
                  </tr>
                  <tr>
                      <td>
                          TEST #6
                      </td>
                      <td>
                        One point data of main-category and<br>
                        sub-category of outgassing from same tester are unrelate.<br>
                        Formula: <br>
                        Total low MW + Total High MW = A<br>
                        Total Outgassing = B<br>
                        *Difference A and B value ≤ 20 ng/part
                      </td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                  </tr>
                  <tr>
                    <td>
                        TEST #7
                    </td>
                    <td>
                      Sigma ratio alerts 3 criteria all together.<br>
                      Data Q'ty for calculation : ≥ 13 data<br>
                      1. One point is out of sigma ratio specification.<br>
                      - Specification : -3 < Sigma ratio < 3<br>
                      2. One point is over MDL.<br>
                      - Specification : Sum Category < MDL<br>
                      3. One point is over 5% of total outgassing.<br>
                      - Specification : Sum Category < 5% of total outgassing.<br>
                      * Health Score Percentile has range 0 - 1.<br>
                      High health scores (1) : a higher risk to the drive.<br>
                      Lower health scores (0) : a lower risk to the drive.<br><br>
                      
                      Start calculate since 1-Dec-22 onward.


                    </td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                </tr>
                </tbody>
              </table>
          </div>
        </div>
        <br> 

        <h4> About</h4>
        <div class="card-block">
          <table width="100%" class="table table-borderless " cellpadding="0" style=" font-size: 16px; ">
            <tbody>
              <tr style=" font-size: 16px; ">
                <td><b>Team members : </b></td>
                <td></td>
                <td></td>
                <td></td>
              </tr>
              <tr style="text-align: center !important;">
                @foreach ($team as $img) 
                <td>
                  <img class="round" src="{{ asset('local/storage/NHK-File/template/help/team_members')}}/{{ $img->profile_members }}">
                </td> 
                @endforeach
              </tr>
              <tr style="text-align: center !important;">
                @foreach ($team as $value) 
                <td style=" color: blue; font-size: 18px; ">
                  {{ $value->name_members }} 
                  @if($status == 1) 
                    <a href="{{ url('support/editteam') }}/{{$value->id_members}}" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-pencil" style=" color: red; "></i></a>
                  @endif
                </td>
                @endforeach
              </tr>
            <tbody>
          </table>
          <table width="100%" class="table table-borderless " cellpadding="0" style=" font-size: 16px; ">
            <tbody>
              <tr>
                <td style="width: 5%;"><b>Create date :</b></td>
                <td>May 2020</td>
                <td></td>
                <td></td>
              </tr> 
            </tbody>
          </table>
        </div>
        <br> 
     
    </div>   
</div>
<div class="row">
    
    
</div>
@endsection

@section('scripts')
<script>


  function editteam(id) {
      
  }

</script>
@endsection
