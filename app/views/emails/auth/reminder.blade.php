<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Password Reset Notification</h2>

		<div>
            {{ $firstname }} {{ $lastname }},
            <br><br>
            You have requested to reset the login password for our {{ $username }} account.
            Click the following link to activate the new password before you attempt to log in.
            <br><br>
            If you din not request your pasword to be reset, please ignore this email and your password will retain its previous value.
            <br><br>

            Your new temporary login password is:<br>
            <b>{{ $password }}</b>
            <br><br>
            Clic next link to activate the password:
            <br>
            {{ $link }}

            Thanks.
			This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.
		</div>
	</body>
</html>
