<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guestbook // Setup</title>
    <link rel="stylesheet" type="text/css" href="/css/setup.css">
</head>
<body>
    <div class="page-content">
        <div class="header">
            <h1>Welcome to the Guestbook!</h1>
            <p>Welcome to your new guestbook!<br>
            Only a few steps, then the guestbook is ready!
            </p>
        </div>
        <div class="content">
            <form action="bin/setup.php" method="post">
                <h3>Database</h3>
                <input type="text" name="db-host" placeholder="Database Host" required><br>
                <input type="text" name="db-user" placeholder="Database User" required><br>
                <input type="password" name="db-pw" placeholder="Database Password" required><br>
                <input type="text" name="db-name" placeholder="Database Name" required><br>
                <span style="color: #808080; font-size: small;">If the given database does not exeist,<br> then create new.</span>
                <h3>Admin User</h3>
				<input type="text" name="ad-user" placeholder="Admin Username" required><br>
				<input type="mail" name="ad-mail" placeholder="Admin Email Adress" required><br>
				<input type="password" name="ad-pw" placeholder="Password" required><br>
				<input type="password" name="ad-pw2" placeholder="Retype password" required><br>
				
				<br>
				
				<button type="submit">Next</button>
            </form>
        </div>
    </div>
</body>
</html>