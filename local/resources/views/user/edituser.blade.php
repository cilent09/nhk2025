@extends('layouts.userlogin')
@extends('layouts.main')
@section('title', 'Edit user')
@section('breadcrumb-title', 'User')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-block" style="margin-left: 5%;margin-right: 5%">
                <br>
                <br>
                <form id="mainform" method="post"  novalidate>
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name <span style="color: red">*</span></label>
                        <input type="hidden" id="userid" name="userid" value="{{$users->id}}">
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="name" id="name" value="{{$users->name}}">
                            <span class="messages"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email <span style="color: red">*</span></label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" id="email" name="email" value="{{$users->email}}">
                            <span class="messages"></span>
                        </div>
                    </div>
                 
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Password </label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" name="password">
                            <span class="messages"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Repeat Password </label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="repeat-password" name="repeat-password" >
                            <span class="messages"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="phone" id="phone" value="{{$users->phone}}">
                            <span class="messages"></span>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-2 col-form-label">Status <span style="color: red">*</span></label>
                        <div class="col-sm-10">
                           
                            <select id="status" name="status" class="required form-control valid">
                                <option value="">Select Status</option>
                                <option value="1" {{($users->status == 1 ? "selected" : "")}}>ADMIN</option>
                                <option value="2" {{($users->status == 2 ? "selected" : "")}}>QA</option>
                                <option value="3" {{($users->status == 3 ? "selected" : "")}}>MFG</option>
                            </select>
                        </div>
                    </div>
                    <br>
                        <br>
                    <div class="form-group row">
                        
                        <label class="col-sm-2"></label>
                        <div class="col-sm-10">
                            <button id="saveuser" type="button" class="btn btn-primary m-b-0">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

@endsection

@section('scripts')

<script>

  $('#saveuser').click(function () {
      var userid = $('#userid').val();
      var name = $('#name').val();
      var email = $('#email').val();
      var password = $('#password').val();
      var repassword = $('#repeat-password').val();
      var status = $('#status').val();
      var emailFilter = /^.+@.+\..{2,3}$/;
      if (name == "" || email == "" || status == "") {
          swal("warning", "This field is required !", "warning");
          return false;
      }
      if (password != repassword) {
          swal("warning", "Password Discrepant !", "warning");
          return false;
      }
      if (!(emailFilter.test(email))) {
          swal("warning", "Pattern Email Improper", "warning");
          return false;
      }
      $.get('{!! url("user/chkeditemail") !!}', {
          'email': email,
          'id': userid
      }, function (data) {
          if (data > 0) {
              swal("warning", "Email Duplicate !", "warning");
              return false;

          } else {
              var forms = $('#mainform')[0];
              var data = new FormData(forms);
              swal({
                      html: true,
                      title: "Are you sure?",
                      text: "Update User!",
                      type: "info",
                      showCancelButton: true,
                      closeOnConfirm: false,
                      showLoaderOnConfirm: true
                  },
                  function () {
                      $.ajax({
                          type: 'POST',
                          url: "{{ url('user/saveedituser') }}",
                          data: data,
                          processData: false,
                          contentType: false,
                          cache: false,
                          success: function (data) {

                              if (data.success == 1) {
                                  swal("Success", "Submit save data success", "success");
                                  setTimeout(function () {
                                      window.location.href =
                                          "{{ url('user') }}";
                                  }, 1000);
                              } else {

                              }
                          }
                      });
                  });
          }
      });

  });

</script>

@endsection
