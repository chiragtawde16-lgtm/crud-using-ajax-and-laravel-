<!DOCTYPE html>
<html>
<head>

    <title>Register</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            margin:0;
     background:linear-gradient(135deg,#4e73df,#224abe);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:Arial, Helvetica, sans-serif;
    padding:20px;
}

       .register-card{
    width:400px;
    background:#ffffff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.2);
}

       .company-header{
    display:flex;
    align-items:center;
    justify-content:center;
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
    color:#000;
}

.company-header small{
    color:#666;
}

    </style>

</head>

<body>
    <div class="register-card">

<div class="company-header">

    <img src="/images/logo.png" alt="Logo">

    <div>
        <h4>C.T Housing Finance Limited</h4>
        <small>Student Management System</small>
    </div>

</div>

    <h3 class="title">
        Create New Account
    </h3>

    <form id="registerForm">

        <div class="mb-2">

            <input type="text"
                   id="name"
                   class="form-control"
                   placeholder="Enter Full Name">

        </div>

        <div class="mb-2">

            <input type="email"
                   id="email"
                   class="form-control"
                   placeholder="Enter Email">

        </div>

       <div class="mb-2">
    <div class="input-group">

        <input
            type="password"
            id="password"
            class="form-control"
            placeholder="Enter Password">

        <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
            <i class="bi bi-eye-slash-fill"></i>
        </span>

    </div>
</div>
<div class="input-group mb-2">

    <input type="password"
           id="confirm_password"
           class="form-control"
           placeholder="Confirm Password">

    <span class="input-group-text" id="toggleConfirmPassword" style="cursor:pointer;">
        <i class="bi bi-eye-fill"></i>
    </span>

</div>

        <button type="submit"
                class="btn btn-success btn-register w-100">

            <i class="bi bi-person-check-fill"></i>
            Create Account

        </button>

    </form>

    <div class="bottom-link">

        Already have an account?

        <br><br>

        <a href="/login"
           class="btn btn-outline-primary w-100">

            <i class="bi bi-box-arrow-in-right"></i>
            Login Here

        </a>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

$(document).ready(function(){

      $("#togglePassword").click(function(){

        let password = $("#password");

        if(password.attr("type") === "password"){

            password.attr("type","text");

            $(this).html('<i class="bi bi-eye-fill"></i>');

        }else{

            password.attr("type","password");

            $(this).html('<i class="bi bi-eye-slash-fill"></i>');

        }

    });
      
     $("#toggleConfirmPassword").click(function(){

    let confirmPassword = $("#confirm_password");

    if(confirmPassword.attr("type") === "password"){

        confirmPassword.attr("type","text");

        $(this).html('<i class="bi bi-eye-fill"></i>');

    }else{

        confirmPassword.attr("type","password");

        $(this).html('<i class="bi bi-eye-slash-fill"></i>');

    }

});

    $("#registerForm").submit(function(e){

    e.preventDefault();

    let password = $("#password").val();
    let confirmPassword = $("#confirm_password").val();

    // ✅ NEW CHECK
    if(password !== confirmPassword){
        alert("please check your password its not matching!");
        return; // form submit stop
    }

    $.ajax({
        url:"/register",
        type:"POST",
        data:{
            name:$("#name").val(),
            email:$("#email").val(),
            password:password,
            password_confirmation:$("#confirm_password").val(), 
            _token:$("meta[name='csrf-token']").attr("content")
        },

        success:function(response){
            alert(response.message);
            $("#registerForm")[0].reset();
            window.location.href="/login";
        },

        error:function(xhr){
            if(xhr.responseJSON.errors){
                let message="";
                $.each(xhr.responseJSON.errors,function(key,value){
                    message+=value[0]+"\n";
                });
                alert(message);
            }
            else if(xhr.responseJSON.message){
                alert(xhr.responseJSON.message);
            }
        }
    });

});

});

</script>
</div>
</body>
</html>