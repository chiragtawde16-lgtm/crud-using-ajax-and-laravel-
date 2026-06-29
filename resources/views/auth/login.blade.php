<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

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

        .login-card{
            width:420px;
            background:#fff;
            padding:30px;
            border-radius:20px;
            box-shadow:0 15px 35px rgba(0,0,0,.2);
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
            font-size:20px;
            font-weight:bold;
        }

        .company-header small{
            color:#666;
        }

        .form-control{
            height:45px;
            border-radius:10px;
        }

        .btn-login{
            height:48px;
            border-radius:10px;
            font-weight:bold;
        }

        .bottom-link{
            text-align:center;
            margin-top:15px;
        }

    </style>

</head>

<body>

<div class="login-card">

    <div class="company-header">

        <img src="/images/logo.png" alt="Company Logo">

        <div>
            <h4>C.T Housing Finance Limited</h4>
            <small>Student Management System</small>
        </div>

    </div>

    <h3 class="text-center mb-4">
        Login
    </h3>

    <form id="loginForm">

        <div class="mb-3">

            <input
                type="email"
                id="email"
                class="form-control"
                placeholder="Enter Email">

        </div>

        <div class="mb-3">

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

        <button
            type="submit"
            class="btn btn-primary btn-login w-100">

            <i class="bi bi-box-arrow-in-right"></i>
            Login

        </button>

    </form>

    <div class="bottom-link">

        Don't have an account?

        <br><br>

        <a href="/register" class="btn btn-outline-primary w-100">

            Create Account

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

    $("#loginForm").submit(function(e){

        e.preventDefault();

        $.ajax({

            url:"/login",

            type:"POST",

            data:{

                email:$("#email").val(),
                password:$("#password").val(),
                _token:$("meta[name='csrf-token']").attr("content")

            },

            success:function(res){

                alert(res.message);

                window.location.href="/students";

            },

            error:function(xhr){

                if(xhr.responseJSON.message){

                    alert(xhr.responseJSON.message);

                }

            }

        });

    });

});

</script>

</body>
</html>