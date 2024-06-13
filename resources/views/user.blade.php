@extends('layouts.master')
@section('title', 'User')
<!-- section CSS -->
<style>
  .boldFont{
    font-weight: bold;
  }

  .textCenter{
    text-align: center;
    vertical-align: middle;
  }

  .textLeft{
    text-align: left;
    vertical-align: middle;
  }

  .frame {
    border: solid 1px grey;
    padding: 10px 10px 10px 10px;
    border-radius: 10px;
  }
</style>
<!-- end section CSS -->

<!-- section Javascript -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script>
  $(document).ready(function () {
    searchIt();
    $('#save').on('click',function(e){
      e.preventDefault();
      Swal.fire({
        title: "Are you sure?",
        text: "Are you sure to Transfer Data?",
        icon: 'warning',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "Cancel",
        allowOutsideClick: false
      }).then(function(x) {
        if(x.value === true){
          $.ajax({
            url         : "{{ url('/peserta') }}/addData",
            method      : "POST",
            data        : {
              "merchant"  : document.getElementById("inputMerchant") == null ? '' : document.getElementById("inputMerchant").value,
              "role"      : document.getElementById("inputRole").value,
              "name"      : document.getElementById("inputName").value,
              "email"     : document.getElementById("inputEmail").value,
            },
            headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            success     : function (data) {
              if(data){
                swal.fire("Info!",data, "info");
              }else{
                $('#modal_addUser').modal('hide');
                swal.fire("Success!","Your data is successfully saved.","success");
              }
            }
          });
        }
      });
    });
  });

  function searchIt(){
    $.ajax({
      type    : 'POST',
      dataType: 'JSON',
      url   	: "{{ url('peserta') }}/getData",
      data    : {},
      headers : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      success : function(result){

        $('#tblUserHTML').html('');
        $('#tblUserHTML').append(''+

        '<div class="table-responsive">'+
          '<table class="table" style="width:100%" id="tblUser">'+
            '<thead>'+
              '<tr>'+
                '<th>No</th>'+
                '<th>Merchant</th>'+
                '<th>Name</th>'+        
                '<th>Email</th>'+
                '<th>Role</th>'+      
                '<th>Created At</th>'+             
                '<th>Action</th>'+   
              '</tr>'+
            '</thead>'+
            '<tbody>');

        var i = 0;
        $.each(result, function(x, y) {
          i++; 
          $('#tblUser').append(''+
          '<tr>'+
            '<td>'+i+'</td>'+
            '<td>'+y.des_merchant+'</td>'+
            '<td>'+y.name+'</td>'+
            '<td>'+y.email+'</td>'+
            '<td>'+y.des_role+'</td>'+
            '<td>'+y.created_at+'</td>'+
            '<td>'+
              '<button type="button" class="btn btn-warning btn-sm" data-bs-toggle="tooltip" title="Edit" onclick="editIt('+y.id+');"><i class="bi bi-pencil-square"></i></button>'+
              '&nbsp;'+
              '<button type="button" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Delete" onclick="deleteIt('+y.id+');"><i class="bi bi-trash"></i></button>'+
            '</td>'+
          '</tr>'+
          '');
         
        });

        $('#table_article').append(''+
        '</tbody></table></div>'+ 
        '');

        $('#tblUser').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
      },
      error : function(xhr){

      }
    });
  }

  function editIt($idUser){
    $('#modal_editUser').modal('show');
    $.ajax({
      url         : "{{ url('/peserta') }}/getDataDetail",
      method      : "POST",
      data        : {
        "id_user"  : $idUser
      },
      headers : { "X-CSRF-TOKEN": "{{ csrf_token() }}" },
      success     : function (data) {
        console.log(data);
        document.getElementById("editID").value = $idUser;
        document.getElementById("editMerchant").innerHTML = data.des_merchant;
        document.getElementById("editRole").innerHTML = data.des_role;
        document.getElementById("editName").innerHTML = data.name;
        document.getElementById("editEmail").innerHTML = data.email;
        document.getElementById("editStatus").value = data.active;
      }
    });
  }

</script>
<!-- end section Javascript -->

@section('content')
<div class="container">
  <h1>User</h1>
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal_addUser">
        Create
      </button>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
          <div id="tblUserHTML">
            <!-- javascript -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

<!-- Modal -->
<div class="modal fade" id="modal_addUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Create User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createForm" name="createForm" enctype="multipart/form-data">
          @if (Auth::user()->role == 'SA')
          <div class="mb-3" id="bagianMerchant">
            <label for="inputMerchant" class="col-sm-2 col-form-label">Merchant</label>
            <select id="inputMerchant" class="form-select form-select-sm" aria-label=".form-select-sm example">
              <option selected>-- Select one --</option>
              @foreach ($data_merchant as $item)
                <option value="{{ $item->code }}">{{ $item->description }}</option>
              @endforeach
            </select>
          </div>
          @endif
          <div class="mb-3">
            <label for="inputRole" class="col-sm-2 col-form-label">Role</label>
            <select id="inputRole" class="form-select form-select-sm" aria-label=".form-select-sm example">
              <option selected>-- Select one --</option>
              @foreach ($data_role as $item)
                <option value="{{ $item->id_role }}">{{ $item->name_role }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="inputName" class="col-sm-2 col-form-label">Name</label>
            <input type="text" class="form-control form-control-sm" id="inputName" required>
          </div>
          <div class="mb-3">
            <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
            <input type="email" class="form-control form-control-sm" id="inputEmail" placeholder="name@example.com" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save">Save</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_editUser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="createForm" name="createForm" enctype="multipart/form-data">
          @if (Auth::user()->role == 'SA')
          <div class="mb-0">
            <label for="editMerchant" class="col-sm-2 col-form-label">Merchant</label>
            <label for="editMerchant" class="col-sm-2 col-form-label">:</label>
            <span for="editMerchant" class="col-sm-2 col-form-label" id="editMerchant"></span>
            <input type="text" class="form-control form-control-sm" id="editID" hidden>
          </div>
          @endif
          <div class="mb-0">
            <label for="editRole" class="col-sm-2 col-form-label">Role</label>
            <label for="editRole" class="col-sm-2 col-form-label">:</label>
            <span for="editRole" class="col-sm-2 col-form-label" id="editRole"></span>
          </div>
          <div class="mb-0">
            <label for="editName" class="col-sm-2 col-form-label">Name</label>
            <label for="editName" class="col-sm-2 col-form-label">:</label>
            <span for="editName" class="col-sm-2 col-form-label" id="editName"></span>
          </div>
          <div class="mb-3">
            <label for="editEmail" class="col-sm-2 col-form-label">Email</label>
            <label for="editEmail" class="col-sm-2 col-form-label">:</label>
            <span for="editEmail" class="col-sm-2 col-form-label" id="editEmail"></span>
          </div>
          <div class="frame">
            <div class="mb-3">
              <label for="editStatus" class="col-sm-2 col-form-label">Status</label>
              <select id="editStatus" class="form-select form-select-sm" aria-label=".form-select-sm example">
                <option selected>-- Select one --</option>
                <option value="1">Active</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="editResetPwd" class="col-sm-3 col-form-label">Reset Password</label>
              <label for="editResetPwd" class="col-sm-3 col-form-label">:</label>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="editResetPwd" id="resetYes" value="option1">
                <label class="form-check-label" for="resetYes">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="editResetPwd" id="resetNo" value="option2">
                <label class="form-check-label" for="resetNo">No</label>
              </div>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save">Update</button>
      </div>
        {{ csrf_field() }}
        </form>
    </div>
  </div>
</div>