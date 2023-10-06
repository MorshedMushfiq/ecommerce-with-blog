<x-adminheader title="Trash Data"/>
      <div class="main-panel">
        <div class="content-wrapper">

          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <a class='btn btn-warning btn-sm my-2' href="{{route('team.page')}}">Back</a>
                    <p class="card-title mb-0">Trash Team</p>
                    @if(Session::has("success"))
                    <p class='alert alert-success my-2'>{{Session::get("success")}} <button class='close' data-dismiss="alert">&times;</button> </p>

                    @endif
                  

                  <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Image</th>
                          <th>Role</th>
                          <th>Cell</th>
                          <th>Action</th>
                        </tr>  
                      </thead>
                      <tbody>
                        @php
                            $i=0;
                        @endphp
                        @foreach($all_trash_team as $trash_team)
                        @php
                            $i++;
                        @endphp
                        <tr>
                          <td>{{$i}}</td>
                          <td>{{$trash_team->name}}</td>
                          <td><img src="{{URL::asset('uploads/teams/'.$trash_team->image)}}" alt=""></td>
                          <td class="font-weight-bold">${{$trash_team->role}}</td>
                          <td class="font-weight-medium">{{$trash_team->cell}}<td>
                            <a href="{{route('team.restore', $trash_team->id)}}" class='btn btn-warning btn-sm'>Restore</a>
                            <a href="{{route('team.delete', $trash_team->id)}}" class='btn btn-danger btn-sm'>Delete Permanently</a>
                            </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->


<x-adminfooter />

