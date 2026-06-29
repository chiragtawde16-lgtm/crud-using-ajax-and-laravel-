<!doctype html>
<html>
<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="card p-4">

        <h2 class="text-center mb-4">
            Login
        </h2>

        <form id="loginForm">

            <input type="email"
                   id="email"
                   class="form-control mb-3"
                   placeholder="Enter Email">

            <input type="password"
                   id="password"
                   class="form-control mb-3"
                   placeholder="Enter Password">

            <button type="submit" class="btn btn-success w-100">
                Login
            </button>

        </form>

        <br>

        <a href="/register" class="btn btn-primary w-100">
            Create New Account
        </a>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function(){

    $("#loginForm").submit(function(e){

        e.preventDefault();

        $.ajax({

            url: "/login",

            type: "POST",

            data: {

                email: $("#email").val(),

                password: $("#password").val(),

                _token: $("meta[name='csrf-token']").attr("content")

            },

            success: function(response){

                alert(response.message);

                window.location.href="/students";

            },

            error: function(xhr){

                alert(xhr.responseJSON.message);

            }

        });

    });

});
</script>
</body>
</html>