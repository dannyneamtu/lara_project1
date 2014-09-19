<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    {{ HTML::style('/css/bootstrap.min.css') }}
    {{ HTML::style('/css/bootstrap-theme.css') }}
    <style>
        @import url(//fonts.googleapis.com/css?family=Lato:700);

        body {
            margin:0;
            font-family:'Lato', sans-serif;
            text-align:center;
            color: #999;
        }

        .welcome {
            width: 300px;
            height: 200px;
            position: absolute;
            left: 50%;
            top: 50%;
            margin-left: -150px;
            margin-top: -100px;
        }

        a, a:visited {
            text-decoration:none;
        }

        h1 {
            font-size: 32px;
            margin: 16px 0 0 0;
        }
    </style>
</head>
<body>
<div id="nav">
    <div class="navbar navbar-inverse">
        <a class="navbar-brand">Home</a>
    </div>
</div>
@if(Session::has('message'))
{{ Session::get('message') }}
<br>
@endif
<h2 style="text-align: center">Reset user password</h2>


@if($errors->any())
<ul>
    {{ implode ('', $errors->all('<li>:message</li>')) }}
</ul>
@endif
<div class="well col-md-4 col-md-offset-4">
    <?php echo '<form method="post" action="'.URL::to('forgotpassword').'">'; ?>
    <label>Please type in your registred email address:</label><br>
    <input type="text" id="email" name="email" placeholder="Email">
    <br><br>

    <button class="btn btn-success"  type="sumbit" value="forgotpassword">Submit</button>

    </form>
</div>

<script src="/js/bootstrap.min.js"></script>
</body>
</html>
