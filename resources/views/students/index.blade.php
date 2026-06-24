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
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="text-white d-flex justify-content-between align-items-center p-3" style="background-color: #6924e8;">
                <h5 class="text-center">Add / Edit Student</h5> <!-- 😄 EDIT: title changed -->
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <form id="studentForm">
                    <label for="name">Name:</label>
                    <input type="text" id="name" class="form-control mb-2" placeholder="Enter Name">
                    <small class="text-danger" id="name_error"></small>

                    <label for="email">Email:</label>
                    <input type="email" id="email" class="form-control mb-2" placeholder="Enter Email">
                    <small class="text-danger" id="email_error"></small>

                    <label for="phone">Phone:</label>
                    <input type="text" id="phone" class="form-control mb-2" placeholder="Enter Phone">
                    <small class="text-danger" id="phone_error"></small>

                    <!-- 😄 EDIT: hidden input for ID -->
                    <input type="hidden" id="student_id">

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

    let editId = null; // 😄 EDIT: store update ID

    loadData();

    function loadData()
    {
        $.get("/students/fetch", function(data){

            let rows = "";

            data.forEach(function(student){
                rows += `
                    <tr>
                        <td>${ student.id}</td>
                        <td>${student.name}</td>
                        <td>${student.email}</td>
                        <td>${student.phone}</td>
                        <td>

                            <!-- 😄 EDIT: Edit button added -->
                            <button onclick="editStudent(${student.id})" class="btn btn-warning btn-sm">
    Edit
</button>
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

    // 😄 EDIT FUNCTION ADDED
    window.editStudent = function(id){

    $.get("/students/show/" + id, function(data){

        $("#name").val(data.name);
        $("#email").val(data.email);
        $("#phone").val(data.phone);

        editId = id; // update mode

        var modal = new bootstrap.Modal(document.getElementById('studentModal'));
        modal.show();

    });

}
    $("#studentForm").submit(function(e){

        e.preventDefault();
        let phone = $("#phone").val();

           let phoneRegex = /^[0-9]{10}$/;

             if(!phoneRegex.test(phone)) 
          {
              $("#phone_error").text("Phone number must be 10 digits");
               return;
        }
         let email = $("#email").val();

         let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

           if(!emailRegex.test(email))
           {
              $("#email_error").text("Please enter a valid email address");
                return;
             }

        let url = "/students/store";

        // 😄 EDIT: if edit mode then update URL
        if(editId != null){
            url = "/students/update/" + editId;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                name: $("#name").val(),
                email: $("#email").val(),
                phone: $("#phone").val(),
                _token: $("meta[name='csrf-token']").attr('content')
            },
            success: function(response){

                alert(editId ? "Updated Successfully" : "Student Added Successfully");

                $("#studentForm")[0].reset();

                editId = null; // 😄 reset edit mode

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

        editId = null; // 😄 reset when modal closes
    });

});


</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>