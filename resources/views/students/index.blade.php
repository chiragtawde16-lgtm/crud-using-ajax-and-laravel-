<!doctype html>
<html>
<head>
    <title>Students</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light" style="background-color: #e68663; padding: 30px;">

<div class="container mt-5">

    <div class="d-flex justify-content-between" style="background-color: #6924e8; padding: 20px; border-radius: 10px;">
        <h2 class="text-white">Student List</h2>
        

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#studentModal">
            Add Student
        </button>
        <button class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#excelModal">
    Upload Excel
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

<!-- MODAL -->
<div class="modal fade" id="studentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="text-white d-flex justify-content-between align-items-center p-3"
                 style="background-color: #6924e8;">
                <h5>Add / Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- ✅ GLOBAL ERROR BOX (TOP OF MODAL) -->
                <div id="errorBox"></div>

                <form id="studentForm">

                    <label>Name:</label>
                    <input type="text" id="name" class="form-control mb-2" placeholder="Enter Name">

                    <label>Email:</label>
                    <input type="email" id="email" class="form-control mb-2" placeholder="Enter Email">

                    <label>Phone:</label>
                    <input type="text" id="phone" class="form-control mb-2" placeholder="Enter Phone">

                    <input type="hidden" id="student_id">

                    <button type="submit" class="btn btn-primary">
                        Save Student
                    </button> <br>
                    <button type="button" class="btn btn-secondary mt-2" data-bs-dismiss="modal">
                       ← Back
                     </button>

                </form>

            </div>
        </div>
    </div>
</div>

</div>
<!-- 😄 ADD KIYA -->
<div class="modal fade" id="excelModal">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5>Upload Excel File</h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">

                <input type="file"
                       id="excelFile"
                       class="form-control">

            </div>

            <div class="modal-footer">

                <button id="uploadExcel"
                        class="btn btn-primary">
                    Upload
                </button>

            </div>

        </div>

    </div>

</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function(){

    let editId = null;
     $("button[data-bs-target='#studentModal']").on("click", function(){
        editId = null;
        $("#studentForm")[0].reset();
        $("#errorBox").html("");
    });

    loadData();

    function loadData()
{
    $.get("/students/fetch", function(data){

        let rows = "";

        data.forEach(function(student){
            rows += `
                <tr id="row_${student.id}">
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.email}</td>
                    <td>${student.phone}</td>
                    <td>
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

        // UI se remove
        $("#row_" + id).remove();

    });
}
    window.editStudent = function(id)
    {
        $.get("/students/show/" + id, function(data){

            $("#name").val(data.name);
            $("#email").val(data.email);
            $("#phone").val(data.phone);

            editId = id;

            new bootstrap.Modal(document.getElementById('studentModal')).show();
        });
    }

    $("#studentForm").submit(function(e){

        e.preventDefault();

        $("#errorBox").html(""); // clear old errors

        let phone = $("#phone").val();
        let email = $("#email").val();

        let phoneRegex = /^[0-9]{10}$/;
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if(!phoneRegex.test(phone))
        {
            $("#errorBox").html(`<div class="alert alert-danger">Phone must be 10 digits</div>`);
            return;
        }

        if(!emailRegex.test(email))
        {
            $("#errorBox").html(`<div class="alert alert-danger">Enter valid email</div>`);
            return;
        }

        let url = "/students/store";

        if(editId != null){
            url = "/students/update/" + editId;
        }

        $.ajax({
            url: url,
            type: "POST",
            data: {
                name: $("#name").val(),
                email: email,
                phone: phone,
                _token: $("meta[name='csrf-token']").attr('content')
            },

            success: function(response){

                $("#errorBox").html(`<div class="alert alert-success">Saved Successfully</div>`);

                $("#studentForm")[0].reset();
                $("#errorBox").html("");

                editId = null;

                bootstrap.Modal.getInstance(
                    document.getElementById('studentModal')
                ).hide();

                loadData();
            },

            error: function(xhr){

                let errors = xhr.responseJSON.errors;

                let errorHtml = `<div class="alert alert-danger"><ul class="mb-0">`;

                if(errors.name){
                    errorHtml += `<li>${errors.name[0]}</li>`;
                }

                if(errors.email){
                    errorHtml += `<li>${errors.email[0]}</li>`;
                }

                if(errors.phone){
                    errorHtml += `<li>${errors.phone[0]}</li>`;
                }

                errorHtml += `</ul></div>`;

                $("#errorBox").html(errorHtml);
            }
        });
    });

    $("#name, #email, #phone").on("input", function(){
        $("#errorBox").html("");
    });

    $('#studentModal').on('hidden.bs.modal', function () {

        $("#studentForm")[0].reset();
        $("#errorBox").html("");
        editId = null;
    });

     // 😄 EXCEL FILE CHECK

     $("#uploadExcel").click(function(){

    let file = $("#excelFile")[0].files[0];

    // check file
    if(!file){
        alert("Please select a file");
        return;
    }

    // create form data
    let formData = new FormData();
    formData.append("file", file);

    // CSRF token (important for Laravel)
    formData.append("_token", $("meta[name='csrf-token']").attr('content'));

    $.ajax({
        url: "/upload-excel",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        success: function(res){

            alert("File uploaded successfully");

            // clear file input
            $("#excelFile").val("");

            // close modal
            let modalEl = document.getElementById('excelModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
        },

        error: function(xhr){

    if(xhr.responseJSON && xhr.responseJSON.message){
        alert(xhr.responseJSON.message);
    }
    else{
        alert("Upload failed");
    }
}
    });

});


// reset file when modal is closed
$('#excelModal').on('hidden.bs.modal', function () {
    $("#excelFile").val("");
});
});
</script>

</body>
</html>