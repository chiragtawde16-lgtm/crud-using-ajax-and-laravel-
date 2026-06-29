<!doctype html>
<html>
<head>
    <title>Students</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
<style>

    .company-header{
        display:flex;
        align-items:center;
        gap:12px;
        margin-bottom:20px;
    }

    .company-header img{
        width:60px;
        height:60px;
        border-radius:50%;
        object-fit:cover;
    }

    .company-header h4{
        margin:0;
        font-size:22px;
        font-weight:bold;
    }

    .company-header small{
        color:gray;
    }

    </style>

</head>


<body class="bg-light" style="background-color: #e68663; padding: 30px;">

<div class="container mt-5">
    <div class="company-header">

    <img src="/images/logo.png" alt="Company Logo">

    <div>
        <h4>C.T Housing Finance Limited</h4>
        <small>Student Management System</small>
    </div>

</div>

    <div class="d-flex justify-content-between" style="background-color: #6924e8; padding: 20px; border-radius: 10px;">

    <!-- Left Side Empty -->
    <div style="width:250px;"></div>

    <!-- Center Heading -->
    <h2 class="text-white m-0 text-center flex-grow-1">
        Student List
    </h2>

    <!-- Right Side Buttons -->
    <div class="d-flex gap-2">

        <!-- Add Student -->
        <button class="btn btn-primary px-4"
                data-bs-toggle="modal"
                data-bs-target="#studentModal">
            + Add Student
        </button>

        <!-- Upload Excel -->
        <button class="btn btn-success px-4"
                data-bs-toggle="modal"
                data-bs-target="#excelModal">
            Upload Excel
        </button>

        <!-- Logout -->
        <a href="/logout"
           class="btn btn-danger px-3">
            Logout
        </a>

    </div>

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

    let uploadedFiles = []; // NEW 👉 important: store uploaded file names

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

        $("#errorBox").html("");

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

    // =========================
    // 😄 EXCEL FILE UPLOAD
    // =========================

    $("#uploadExcel").click(function(){

        let file = $("#excelFile")[0].files[0];

        if(!file){
            alert("Please select a file");
            return;
        }

        let fileName = file.name; // NEW 👉 file name capture

        // NEW 👉 duplicate check (frontend only)
        if(uploadedFiles.includes(fileName)){
            alert("This file is already uploaded");

            // NEW 👉 clear input (important UX fix)
            $("#excelFile").val("");

            return;
        }

        let allowedTypes = [
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            "application/vnd.ms-excel",
            "text/csv"
        ];

        if(!allowedTypes.includes(file.type))
        {
            alert("Only Excel Files Allowed (.xlsx, .xls, .csv)");
            return;
        }

        if(file.size === 0)
        {
            alert("Empty File Cannot Be Uploaded");
            return;
        }

        let formData = new FormData();
        formData.append("file", file);
        formData.append("_token", $("meta[name='csrf-token']").attr('content'));

        $.ajax({
            url: "/upload-excel",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,

            success: function(res){

                alert("File uploaded successfully");

                // NEW 👉 store uploaded file name after success
                uploadedFiles.push(fileName);

                $("#excelFile").val("");

                let modalEl = document.getElementById('excelModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();

                loadData();
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
      
    setTimeout(function(){

        alert("Your login time is Expired. Please Login Again.");

        window.location.href = "/logout";

    }, 300000);

});
</script>

</body>
</html>