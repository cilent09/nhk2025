<!DOCTYPE html>

<html>

<head>

    <title>Laravel 5.7 Ajax Request example</title>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script
        src="https://code.jquery.com/jquery-3.2.1.js"
        integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous">
    </script>

    <meta name="csrf-token" content="{{ csrf_token() }}" />

</head>

<body>



<div class="container">

    <h1>Laravel 5.7 Ajax Request example</h1>



    <form >



        <div class="form-group">

            <label>Name:</label>

            <input type="text" name="name" class="form-control" placeholder="Name" required="">

        </div>



        <div class="form-group">

            <label>Password:</label>

            <input type="password" name="password" class="form-control" placeholder="Password" required="">

        </div>



        <div class="form-group">

            <strong>Email:</strong>

            <input type="email" name="email" class="form-control" placeholder="Email" required="">

        </div>



        <div class="form-group">

            <button class="btn btn-success btn-submit">Submit</button>

        </div>



    </form>

</div>



</body>

<script type="text/javascript">



    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });



    $(".btn-submit").click(function(e){



        e.preventDefault();



        var name = $("input[name=name]").val();

        var password = $("input[name=password]").val();

        var email = $("input[name=email]").val();

        $.ajax({

            type:'POST',

            url:'{{url("ajaxRequest")}}',

            data:{name:name, password:password, email:email},

            success:function(data){

                alert(data.success);

            }

        });



    });

</script>



</html>
