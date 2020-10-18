@extends('admin.master')
@section('content')
    <h2 style="margin-top: 12px;" class="alert alert-success">Category Crud
    </h2><br>
    <div class="row">
        <div class="col-12 text-right">
            <a href="javascript:void(0)" class="btn btn-success mb-3" id="create-new-category" onclick="addCategory()">Add Category</a>
        </div>
    </div>
    <div class="row" style="clear: both;margin-top: 18px;">
        <div class="col-12">
            <table id="laravel_crud" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach($categories as $category)
                    <tr id="row_{{$category->id}}">
                        <td>{{ $category->id  }}</td>
                        <td>{{ $category->category_name }}</td>
                        <td>{{ $category->category_description }}</td>
                       <td>{{ $category->publication_status }}</td>
                        <td>
                            <a href="javascript:void(0)" data-id="{{ $category->id }}" onclick="editCategory(event.target)" class="btn btn-info">Edit</a>
                        </td>
                        <td>
                            <a href="javascript:void(0)" data-id="{{ $category->id }}" class="btn btn-danger" onclick="deleteCategory(event.target)">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="category-modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
            <form name="categoryForm" class="form-horizontal">
               <input type="hidden" name="category_id" id="category_id">
                <div class="form-group">
                    <label for="name" class="col-sm-2">Category Name</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter Category Name" required>
                        <span class="text-danger">
                              {{ $errors->has('category_name') ? $errors->first('category_name') : ' ' }}
                        </span>

                        <span id="nameError" class="alert-message"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2">Category Description</label>
                    <div class="col-sm-12">
                        <textarea class="form-control" id="category_description" name="category_description" rows="4" cols="50" required>
                        </textarea>
                        <span class="text-danger">
                              {{ $errors->has('category_description') ? $errors->first('category_name') : ' ' }}
                        </span>
                        <span id="descriptionError" class="alert-message"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="col-sm-2">Publication Status</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="publication_status" name="publication_status" placeholder="Enter Publication Status" required>
                        <span class="text-danger">
                              {{ $errors->has('publication_status') ? $errors->first('publication_status') : ' ' }}
                        </span>
                        <span id="statusError" class="alert-message"></span>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="createCategory()">Save</button>
        </div>
    </div>
  </div>
</div>

    <script>
        $('#laravel_crud').DataTable();

        function addCategory() {
            $("#category_id").val('');
            $('#category-modal').modal('show');
        }

        function editCategory(event) {
            var id  = $(event).data("id");
            let _url = `/categories/${id}`;
            $('#nameError').text('Category is required');
            $('#descriptionError').text('');
            $('#statusError').text('');

            $.ajax({
                url: _url,
                type: "GET",
                success: function(response) {
                    if(response) {
                        $("#category_id").val(response.id);
                        $("#category_name").val(response.category_name);
                        $("#category_description").val(response.category_description);
                        $("#publication_status").val(response.publication_status);
                        $('#category-modal').modal('show');
                    }
                }
            });
        }

        function createCategory() {
            var category_name = $('#category_name').val();
            var category_description = $('#category_description').val();
            var publication_status = $('#publication_status').val();
            var id = $('#category_id').val();

            let _url     = `/categories`;
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: "POST",
                data: {
                    id: id,
                    category_name: category_name,
                    category_description: category_description,
                    publication_status: publication_status,
                    _token: _token
                },
                success: function(response) {
                    if(response.code == 200) {
                        if(id != ""){
                            $("#row_"+id+" td:nth-child(2)").html(response.data.category_name);
                            $("#row_"+id+" td:nth-child(3)").html(response.data.category_description);
                            $("#row_"+id+" td:nth-child(4)").html(response.data.publication_status);
                        } else {
                            $('table tbody').prepend('<tr id="row_'+response.data.id+'">' +
                                '<td>'+response.data.id+'</td>' +
                                '<td>'+response.data.category_name+'</td>' +
                                '<td>'+response.data.category_description+'</td>' +
                                '<td>'+response.data.publication_status+'</td>' +
                                '<td><a href="javascript:void(0)" data-id="'+response.data.id+'" onclick="editCategory(event.target)" class="btn btn-info">Edit</a></td>' +
                                '<td><a href="javascript:void(0)" data-id="'+response.data.id+'" onclick="deleteCategory(event.target)" class="btn btn-danger">Delete</a></td>' +
                                '</tr>');
                        }
                        $('#category_name').val('');
                        $('#category_description').val('');
                        $('#publication_status').val('');
                        $('#category-modal').modal('hide');
                    }
                },
                errors: function(response) {
                    $('#nameError').text('category is required');
                    $('#descriptionError').text(response.responseJSON.errors.category_description);
                    $('#statusError').text(response.responseJSON.errors.publication_status);
                }
            });
        }

        function deleteCategory(event) {
            var id  = $(event).data("id");
            let _url = `/categories/${id}`;
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: 'DELETE',
                data: {
                    _token: _token
                },
                success: function(response) {
                    $("#row_"+id).remove();
                },
                error:function (response) {
                    $("#row_"+id).text("Data is not deleted");
                }
            });
        }

    </script>
@endsection
