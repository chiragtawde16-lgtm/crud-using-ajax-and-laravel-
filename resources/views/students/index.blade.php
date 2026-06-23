<!doctype html>
<html>
<head>
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light" style="background-color: #e68663; padding: 30px;">

<div class="container mt-5">

    <div class="d-flex justify-content-between" style="background-color: #6924e8; padding: 20px; border-radius: 10px;">
        <h2>Student List</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#studentModal">
    Add Student
</button>
    </div>

    <table class="table table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="studentTable"></tbody>
    </table>
<!-- Add Student Modal -->
 <html>
 <head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">      
 <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
 <div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="text-white d-flex justify-content-between align-items-center p-3" style="background-color: #6924e8; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                <h5 class="align-items-center">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="studentForm">

                    <input type="text" id="name" class="form-control mb-2" placeholder="Enter Name">
                    <small class="text-danger" id="name_error"></small>

                    <input type="email" id="email" class="form-control mb-2" placeholder="Enter Email">
                    <small class="text-danger" id="email_error"></small>

                    <input type="text" id="phone" class="form-control mb-2" placeholder="Enter Phone">
                    <small class="text-danger" id="phone_error"></small>

                    <button type="submit" class="btn btn-primary">
                        Save Student
                    </button>

                </form>

            </div>

        </div>
    </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function(){

    loadData();

    function loadData()
    {
        $.get("/students/fetch", function(data){

            let rows = "";

            data.forEach(function(student){
                rows += `
                    <tr>
                        <td>${student.id}</td>
                        <td>${student.name}</td>
                        <td>${student.email}</td>
                        <td>${student.phone}</td>
                        <td>
                            <button onclick="deleteStudent(${student.id})" class="btn btn-danger btn-sm">
                                Delete
                            </button>
                        </td>
                    </tr>
                `;
            });

            $("#studentTable").html(rows);
        });
    }

    window.deleteStudent = function(id)
    {
        $.get("/students/delete/" + id, function(){
            alert("Deleted Successfully");
            loadData();
        });
    }
    $("#studentForm").submit(function(e){

    e.preventDefault();

    $.ajax({
        url: "/students/store",
        type: "POST",
        data: {
            name: $("#name").val(),
            email: $("#email").val(),
            phone: $("#phone").val(),
            _token: $("meta[name='csrf-token']").attr('content')
        },
        success: function(response){

            alert("Student Added Successfully");

            $("#studentForm")[0].reset();

            var modal = bootstrap.Modal.getInstance(
                document.getElementById('studentModal')
            );

            modal.hide();

            loadData();
            
        },
        error: function(xhr){

        let errors = xhr.responseJSON.errors;

        if(errors.name){
            $("#name_error").text(errors.name[0]);
        }

        if(errors.email){
            $("#email_error").text(errors.email[0]);
        }

        if(errors.phone){
            $("#phone_error").text(errors.phone[0]);
        }
    }

    });
    
});

     $("#name").on("input", function(){
        $("#name_error").text("");   
        });

    $("#email").on("input", function(){
        $("#email_error").text(""); 
    });
    $("#phone").on("input", function(){
        $("#phone_error").text("");  
    });

    
    $('#studentModal').on('hidden.bs.modal', function () {

        $("#studentForm")[0].reset();

        $("#name_error").text("");
        $("#email_error").text("");
        $("#phone_error").text("");

    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>