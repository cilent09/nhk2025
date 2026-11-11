@extends('layouts.userlogin')
<html>
    <head>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;
            }
            @font-face {
            font-family: 'Secular One';
            font-style: normal;
            font-weight: 400;
            font-display: swap;
            src: local('Secular One'), local('SecularOne-Regular'), url(https://fonts.gstatic.com/s/secularone/v5/8QINdiTajsj_87rMuMdKyqDsOOhLLA.woff2) format('woff2');
            unicode-range: U+0100-024F, U+0259, U+1E00-1EFF, U+2020, U+20A0-20AB, U+20AD-20CF, U+2113, U+2C60-2C7F, U+A720-A7FF;
            }      
            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 3cm;
                margin-left: 2cm;
                margin-right: 2cm;
                margin-bottom: 2cm;
                font-family: "Secular One", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;     
            }
            /** Define the header rules **/
            header {
                position: fixed;
                top: 0cm;
                left: 0cm;
                right: 0cm;
                height: 2cm;
                background-color: white;
                color: #000000;
                text-align: center;
                line-height: 1.5cm;
            }
            /** Define the footer rules **/
            footer {
                position: fixed; 
                bottom: 0cm; 
                left: 0cm; 
                right: 0cm;
                height: 1.5cm;
                background-color: white;
                color: #000000;
                text-align: left;
                line-height: 1cm;
            }
            hr.new4 {
  border: 1px solid #EE6303;
}
p.ex1 {
    margin-left: 600px;

}
p.ex2 {
    color: #FF0000;
}

        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <p class="ex1"><img class="img-fluid" src="{{ asset('template/assets/images/logonhkh.png')}}" alt="Theme-Logo" /></p>
            <hr width="97%" class="new4">
            <p class="ex1"><div>&nbsp;</div> <p class="ex1">
            
        </header>    
        {{-- <footer>
            <hr width="97%" class="new4">
            &nbsp;&nbsp;&nbsp; xxx &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;<font style="color: #FF0000" size="2">TNHK CONFIDENTIAL</font>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  
            xxx
     
        </footer> --}}
        <footer>
            <hr width="97%" class="new4">
            <div style="clear:both; position:relative;">
                <div style="position:absolute; left:10pt; width:192pt;">
                    <font size="13px" style="color: #FF0000">Model</font>
                </div>
                <div style="margin-left:250pt;">
                    <font size="13px" style="color: #FF0000">TNHK CONFIDENTIAL</font>
                </div>
            </div>
     
        </footer>
        <!-- Wrap the content of your PDF inside a main tag -->
        {{-- <main>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1001.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="105%" src="{{ asset('local/img_g/1002.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1003.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1004.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1005.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1006.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1007.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1008.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1009.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1010.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1011.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1012.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1013.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1014.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1015.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1016.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="105%" src="{{ asset('local/img_g/1017.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1018.png')}}" alt="Theme-Logo" /> </center>
            xxxxx
            <center><img class="img-fluid"  width="105%" src="{{ asset('local/img_g/1019.png')}}" alt="Theme-Logo" /> </center>
        </main> --}}
        <main>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1001.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1002.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1003.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1004.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1005.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1006.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1007.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1008.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1009.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1010.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1011.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1012.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1013.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1014.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1015.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1016.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1017.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="110%" src="{{ asset('local/img_g/1018.png')}}" alt="Theme-Logo" /> </center>
            <center><img class="img-fluid"  width="105%" src="{{ asset('local/img_g/1019.png')}}" alt="Theme-Logo" /> </center>
        </main>
    </body>
</html>


