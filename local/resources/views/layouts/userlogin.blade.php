@if (Auth::user()->status == 3)
    @php
        header( "location:".asset('OQA/incoming') );
        exit(0);
    @endphp
@endif