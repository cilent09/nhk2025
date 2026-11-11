
<nav class="pcoded-navbar">

    <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>

    <div class="pcoded-inner-navbar main-menu">

        @if(session('namemenu') == "oqa")
            <div class="pcoded-navigation-label"> <br>New</div>
            <ul class="pcoded-item pcoded-left-item">

                <li class="">
                    <a href="{{ url('/OQA/incoming') }}">
                        <span class="pcoded-micon"><i class="fa fa-desktop"></i><b>R</b></span>
                        <span class="pcoded-mtext">New OQA lot</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li> 
            </ul> 
        
            <div class="pcoded-navigation-label">Report OQA</div>
        
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/output/results') }}">
                        <span class="pcoded-micon"><i class="fa fa-flask"></i><b>R</b></span>
                        <span class="pcoded-mtext">Results</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('/output/raw') }}">
                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>RR</b></span>
                        <span class="pcoded-mtext">Report Raw</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('/output/customer') }}">
                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>RC</b></span>
                        <span class="pcoded-mtext">Report Customer</span>
                        <span class="pcoded-mcaret"></span>                                                      
                    </a>
                </li> 
                @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                <li class="">
                    <a href="{{ url('/reportmeasure') }}">
                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>RC</b></span>
                        <span class="pcoded-mtext">Measurement L/T</span>
                        <span class="pcoded-mcaret"></span>                                                      
                    </a>
                </li>
                @endif  

            </ul>
 
            <div class="pcoded-navigation-label"> Compound</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/output/elementsem') }}">
                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>S</b></span>
                        <span class="pcoded-mtext">SEM</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('/output/elementgcms') }}">
                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>S</b></span>
                        <span class="pcoded-mtext">GCMS </span>
                        <span class="pcoded-mcaret"></span> 
                    </a>
                </li>
            </ul>


            @if (Auth::user()->status == 1 || Auth::user()->status == 2)
            <div class="pcoded-navigation-label">Setting</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/master/model') }}">
                        <span class="pcoded-micon"><i class="fa fa-database"></i><b>M</b></span>
                        <span class="pcoded-mtext">Model</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('/master/newmodeldata') }}">
                        <span class="pcoded-micon"><i class="fa fa-align-left"></i><b>M</b></span>
                        <span class="pcoded-mtext">Control limit</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>  

                <li class="">
                    <a href="{{ url('/master/manageralarm') }}">
                        <span class="pcoded-micon"><i class="fa fa-exclamation-triangle"></i><b>M</b></span>
                        <span class="pcoded-mtext">Alarm point</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('/output/measure') }}">
                        <span class="pcoded-micon"><i class="fa fa-align-left"></i><b>M</b></span>
                        <span class="pcoded-mtext">Measurement L/T setting</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li> 

                @if (Auth::user()->status == 1)
                <li class="">
                    <a href="{{ url('/master/tgahistory') }}">
                        <span class="pcoded-micon"><i class="fa fa-history"></i><b>M</b></span>
                        <span class="pcoded-mtext"> TGA date code (History)</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @endif

            </ul>
            @endif
            <div class="pcoded-navigation-label">Support</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/support/instruction') }}">
                        <span class="pcoded-micon"><i class="ti-file"></i><b>D</b></span>
                        <span class="pcoded-mtext">Manual</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                <li class="">
                    <a href="{{ url('/support/needsupport') }}">
                        <span class="pcoded-micon"><i class="ti-layout-list-post"></i><b>SI</b></span>
                        <span class="pcoded-mtext">Help</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @endif
                <li class="">
                    <a href="{{ url('/support/help') }}">
                        <span class="pcoded-micon"><i class="ti-help-alt"></i><b>SI</b></span>
                        <span class="pcoded-mtext">SPC control</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li> 
            </ul>
        
        @endif

        @if(session('namemenu') == "oqa2")
            <div class="pcoded-navigation-label"> <br>New</div>
            <ul class="pcoded-item pcoded-left-item">

                <li class="">
                    <a href="{{ url('/OQA/incoming') }}">
                        <span class="pcoded-micon"><i class="fa fa-desktop"></i><b>R</b></span>
                        <span class="pcoded-mtext">New OQA lot</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li> 
            </ul> 

            <div class="pcoded-navigation-label">Report OQA</div>
        
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/output/rawWD') }}">
                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>RR</b></span>
                        <span class="pcoded-mtext">Report Raw</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ url('/output/customerWD') }}">
                        <span class="pcoded-micon"><i class="fa fa-file-text-o"></i><b>RC</b></span>
                        <span class="pcoded-mtext">Report Customer</span>
                        <span class="pcoded-mcaret"></span>                                                      
                    </a>
                </li> 
            </ul>

            @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                <div class="pcoded-navigation-label">Setting</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="">
                        <a href="{{ url('/master/model') }}">
                            <span class="pcoded-micon"><i class="fa fa-database"></i><b>M</b></span>
                            <span class="pcoded-mtext">Model</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul> 
            @endif
        
            <div class="pcoded-navigation-label">Support</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/support/instruction') }}">
                        <span class="pcoded-micon"><i class="ti-file"></i><b>D</b></span>
                        <span class="pcoded-mtext">Manual</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                <li class="">
                    <a href="{{ url('/support/needsupport') }}">
                        <span class="pcoded-micon"><i class="ti-layout-list-post"></i><b>SI</b></span>
                        <span class="pcoded-mtext">Help</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @endif
                <li class="">
                    <a href="{{ url('/support/help') }}">
                        <span class="pcoded-micon"><i class="ti-help-alt"></i><b>SI</b></span>
                        <span class="pcoded-mtext">SPC control</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>  
            </ul>

        @endif

        @if(session('namemenu') == "queue")
            <div class="pcoded-navigation-label"> <br>Queuing</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/queue') }}">
                        <span class="pcoded-micon"><i class="fa fa-minus"></i><b>R</b></span>
                        <span class="pcoded-mtext">Queuing</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
            </ul> 
        @endif


        @if(session('namemenu') == "monitor")
            <div class="pcoded-navigation-label"> <br>New</div>
            <ul class="pcoded-item pcoded-left-item">

                @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                <li class="">
                    <a href="{{ url('/output/monitoring') }}">
                        <span class="pcoded-micon"><i class="fa fa-desktop"></i><b>R</b></span>
                        <span class="pcoded-mcaret">New Monitoring</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li> 
                @endif
            </ul> 

            @if (Auth::user()->status == 1)
                <div class="pcoded-navigation-label">Setting</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="">
                        <a href="{{ url('/master/monitorset') }}">
                            <span class="pcoded-micon"><i class="fa fa-flask"></i><b>M</b></span>
                            <span class="pcoded-mtext">Monitoring set up </span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul> 
            @endif

            <div class="pcoded-navigation-label">Support</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/support/instruction') }}">
                        <span class="pcoded-micon"><i class="ti-file"></i><b>D</b></span>
                        <span class="pcoded-mtext">Manual</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                <li class="">
                    <a href="{{ url('/support/needsupport') }}">
                        <span class="pcoded-micon"><i class="ti-layout-list-post"></i><b>SI</b></span>
                        <span class="pcoded-mtext">Help</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @endif
                <li class="">
                    <a href="{{ url('/support/help') }}">
                        <span class="pcoded-micon"><i class="ti-help-alt"></i><b>SI</b></span>
                        <span class="pcoded-mtext">SPC control</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li> 
            </ul>


        @endif

        @if(session('namemenu') == "dailycheck")
            <div class="pcoded-navigation-label"> <br>New</div>
            <ul class="pcoded-item pcoded-left-item">

                @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                <li class="">
                    <a href="{{ url('/output/dailycheck') }}">
                        <span class="pcoded-micon"><i class="fa fa-desktop"></i><b>R</b></span>
                        <span class="pcoded-mcaret">New Daily check</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @endif
            </ul> 

            @if (Auth::user()->status == 1)
                <div class="pcoded-navigation-label">Setting</div>
                <ul class="pcoded-item pcoded-left-item">
                    <li class="">
                        <a href="{{ url('/master/dailycheckset') }}">
                            <span class="pcoded-micon"><i class="fa fa-flask"></i><b>M</b></span>
                            <span class="pcoded-mtext">Daily check set up</span>
                            <span class="pcoded-mcaret"></span>
                        </a>
                    </li>
                </ul> 
            @endif

            <div class="pcoded-navigation-label">Support</div>
            <ul class="pcoded-item pcoded-left-item">
                <li class="">
                    <a href="{{ url('/support/instruction') }}">
                        <span class="pcoded-micon"><i class="ti-file"></i><b>D</b></span>
                        <span class="pcoded-mtext">Manual</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @if (Auth::user()->status == 1 || Auth::user()->status == 2)
                <li class="">
                    <a href="{{ url('/support/needsupport') }}">
                        <span class="pcoded-micon"><i class="ti-layout-list-post"></i><b>SI</b></span>
                        <span class="pcoded-mtext">Help</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
                @endif
                <li class="">
                    <a href="{{ url('/support/help') }}">
                        <span class="pcoded-micon"><i class="ti-help-alt"></i><b>SI</b></span>
                        <span class="pcoded-mtext">SPC control</span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li> 
            </ul>

        @endif

                    
        
    </div>
</nav>
