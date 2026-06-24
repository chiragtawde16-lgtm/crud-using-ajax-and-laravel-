<!doctype html>
<html>
<head>
    <title>Add Student</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light" style="background-color: #e68663; padding: 30px;">

<div class="container mt-5">

    <div class="rounded-shadow-lg p-4"
         style="background-color: #26baf5; border-radius: 10px;">

        <h2 class="text-center">Add Student</h2>
       

        <div id="errorBox" class="alert alert-danger" style="display:none;"></div>

        <form id="studentForm">

            <label>Name:</label>
            <input type="text" id="name" class="form-control mb-2" placeholder="Name">
            <small class="text-danger" id="name_error"></small>

            <label>Email:</label>
            <input type="text" id="email" class="form-control mb-2" placeholder="Email">
            <small class="text-danger" id="email_error"></small>

            <label>Phone:</label>
            <input type="text" id="phone" class="form-control mb-2" placeholder="Phone">
            <small class="text-danger" id="phone_error"></small>

            <br>

            <button type="submit" class="btn btn-success">
                Save
            </button>

        </form>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function(){

    $("#studentForm").submit(function(e){

        e.preventDefault();
        $("#errorBox").hide().html("");

        // 😄 Clear old errors
        $("#name_error").text("");
        $("#email_error").text("");
        $("#phone_error").text("");

        // 😄 Email Regex
        let email = $("#email").val();
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

       if(!emailRegex.test(email))
{
    $("#errorBox").show().html("Please enter valid email");
    return;
}
        // 😄 Phone Regex
        let phone = $("#phone").val();
        let phoneRegex = /^[0-9]{10}$/;

        if(!phoneRegex.test(phone))
{
    $("#errorBox").show().html("Phone number must be 10 digits");
    return;
}
        $.ajax({

            url: "/students/store",

            type: "POST",

            data: {
                name: $("#name").val(),
                email: $("#email").val(),
                phone: $("#phone").val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },

            success: function(response){

                alert("Student Added Successfully");

                $("#studentForm")[0].reset();

                window.location.href = "/students";
            },

            error: function(xhr){

                let errors = xhr.responseJSON.errors;

                let msg = "";

             if(errors.name){
             msg += errors.name[0] + "<br>";
             }

if(errors.email){
    msg += errors.email[0] + "<br>";
}

if(errors.phone){
    msg += errors.phone[0] + "<br>";
}

$("#errorBox").show().html(msg);
            }

        });

    });

    // 😄 Remove error while typing

    $("#name").on("input", function(){
        $("#name_error").text("");
    });

    $("#email").on("input", function(){
        $("#email_error").text("");
    });

    $("#phone").on("input", function(){
        $("#phone_error").text("");
    });

});
</script>

</body>
</html>