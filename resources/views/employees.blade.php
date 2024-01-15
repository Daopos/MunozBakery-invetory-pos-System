@extends('layouts.master')
@section('content')


<div class="container-text">
    <h4>Inventory / Employees</h4>
</div>

<div class="container-activity">
    <div class="activity">
        <h1>EMPLOYEE ACTIVITY</h1>
            <div class="activities">
                <div>
                    <h1>{{ $totalemployee }}</h1>
                    <h5>total of employee</h5>
                </div>
            </div>
    </div>
    <div class="summary">
        <h1>EMPLOYEE SUMMARY</h1>
            <div class="summaries">
                <div >
                    <h1>{{ $totalbaker }}</h1>
                    <h5>baker</h5>
                </div>
                <div class="line2"></div>
                <div>
                    <h1>{{ $totalbagger }}</h1>
                    <h5>bagger</h5>
                </div>
            </div>
    </div>
</div>

<div class="one-stock">
    <div class="stock-add">
        <h1>All Employees</h1>
        <Button id="addEmployeeModal" data-bs-toggle="modal" data-bs-target="#employeeModal">+ New Employee</Button>
    </div>

    <div class="container-stock">
        <table class="stock-table">
            <tr>
              <th></th>
              <th>name</th>
              <th>address</th>
              <th>username</th>
              <th>type</th>
              <th>Action</th>
            </tr>
            @foreach ($employees as $employee)
            <tr>
                <td><img src="{{ asset('Image/' . $employee->image) }}" alt=""></td>
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->address }}</td>
                <td>{{ $employee->username }}</td>
                <td>{{ $employee->employee_type }}</td>
                <td>
                  <div class="action">
                      <button class="editemployee" data-bs-toggle="modal" data-bs-target="#editemployeeModal{{ $employee->id }}" data-product-id="{{ $employee->id }}">Update</button>
                      <button  data-bs-toggle="modal" data-bs-target="#deleteModal{{ $employee->id }}" data-product-id="{{ $employee->id }}">Delete</button>
                  </div>
              </td>
              </tr>


     <div class="modal fade" id="editemployeeModal{{ $employee->id }}" tabindex="-1" aria-labelledby="editemployeeModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editemployeeModalLabel">Edit Employee</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('editemployee', ['id' => $employee->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Use PUT method for editing -->

                        <div class="form-group ">
                            <label for="employee_image">Image</label>
                            <input type="file" class="form-control " name="employee_image" id="edit_employee_image{{ $employee->id }}" value="{{ old('employee_image') }}">
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" id="edit_employee_username{{ $employee->id }}" value="{{ old('username') }}">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="text" class="form-control" name="password" id="edit_employee_password{{ $employee->id }}" value="{{ old('password') }}">
                        </div>
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="name" id="edit_employee_name{{ $employee->id }}" value="{{ old('name') }}">
                        </div>
                        <div class="form-group">
                            <label for="password">Address</label>
                            <input type="text" class="form-control" name="address" id="edit_employee_address{{ $employee->id }}" value="{{ old('address') }}">
                        </div>
                        <div class="form-group">
                            <label for="employee_type">employee_type</label>
                            <select id="edit_employee_type{{ $employee->id }}" value="{{ old('employee_type') }}"  name="employee_type" class="form-select" aria-label="Default select example" >
                                @if (old('employee_type') == 'baker') {
                                    <option value="baker">BAKER</option>
                                    <option value="bagger">BAGGER</option>
                                }
                                @else {
                                    <option value="bagger">BAGGER</option>
                                    <option value="baker">BAKER</option>
                                }
                                @endif
                              </select>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Update</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal{{ $employee->id }}" tabindex="-1" aria-labelledby="exampleModalLabel{{ $employee->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Delete Employee</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deleteform{{ $employee->id }}" method="POST" action="{{ route('deleteemployee', ['id' => $employee->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('DELETE') <!-- Add this line to indicate that it's a DELETE request -->
                    <h5>Are you sure want to Delete <span class="text-info">{{ $employee->name }}</span>?</h5>
                    <h5 class="mt-3">It will delete all the sales that are related to this employee</h5>
                    <h6 class="mt-3 text-danger">You cannot undo this action</h6>
            </div>
            <div class="modal-footer">
              <input id="deleteModalEmployee" type="submit" value="Delete" class="btn btn-danger">
                </form>
            </div>
          </div>
        </div>
      </div>
            @endforeach


        </table>
    </div>
</div>




  <div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Add Employee</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('addemployee') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group ">
                    <label for="item_image">Image</label>
                    <input type="file" class="form-control " name="item_image" id="item_image" required>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" name="username" id="item_name" autocomplete="off" placeholder="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="text" class="form-control" name="password" id="item_name" autocomplete="off" placeholder="password" required>
                </div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="qty" autocomplete="off" placeholder="name" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="qty" autocomplete="off" placeholder="address" required>
                </div>
                <div class="form-group">
                    <label for="employee_type">employee_type</label>
                    <select name="employee_type" class="form-select" aria-label="Default select example" >
                        <option value="baker">BAKER</option>
                        <option value="bagger">BAGGER</option>
                      </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>




  @if (session('success'))
  <script>
    // Display Toastr notification for success
    toastr.success('{{ session('success') }}', 'Success!', {"iconClass": 'customer-info'});
</script>
@endif

  <script>
    document.querySelectorAll('.editemployee').forEach(function (editButton) {
      editButton.addEventListener('click', function () {
          const employeeId = editButton.getAttribute('data-product-id');

          // Make an AJAX request to the server to get the stock item data
          $.ajax({
              url: `/getEmployee/${employeeId}`,
              type: 'GET',
              dataType: 'json',
              success: function (data) {
                  // Populate the hidden input with the stock ID

                  $('#edit_employee_id').val(data.id);
                  // Populate the form fields with the retrieved data, using the correct IDs
                  $('#edit_employee_image' + employeeId).val(data.item_image);
                  $('#edit_employee_username' + employeeId).val(data.username);
                  $('#edit_employee_password' + employeeId).val(data.password);
                  $('#edit_employee_name' + employeeId).val(data.name);
                  $('#edit_employee_address' + employeeId).val(data.address);
                  $('#edit_employee_type' + employeeId).val(data.employee_type);

              },
              error: function (error) {
                  console.error('Error fetching stock item data:', error);
              }
          });
      });
  });



  </script>

@endsection
