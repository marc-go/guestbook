<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gästebuch // Setup</title>
    <link rel="stylesheet" type="text/css" href="/css/setup.css">
</head>
<body>
    <div class="page-content">
        <div class="header">
            <h1>Willkommen beim Gästebuch!</h1>
            <p>Willkommen bei deinem neuen Gästebuch!<br>
            Nur noch wenige Schritte, dann ist dein Gästebuch funktionsfähig!
            </p>
        </div>
        <div class="content">
            <form action="bin/setup.php" method="post">
                <h3>Datenbank</h3>
                <input type="text" name="db-host" placeholder="Datenbank Host" required><br>
                <input type="text" name="db-user" placeholder="Datenbank Benutzer" required><br>
                <input type="password" name="db-pw" placeholder="Datenbank Passwort" required><br>
                <input type="text" name="db-name" placeholder="Datenbank Name" required><br>
                <span style="color: #808080; font-size: small;">Falls die angegebene Datenbank nicht exestiert, <br> wird sie neu erstellt.</span>

                <h3>Benutzer</h3>
				<input type="text" name="ad-user" placeholder="Admin Benutzername" required><br>
				<input type="mail" name="ad-mail" placeholder="Admin Email Adresse" required><br>
				<input type="password" name="ad-pw" placeholder="Passwort" required><br>
				<input type="password" name="ad-pw2" placeholder="Passwort wiederholen" required><br>
				
				<br>
				
				<button type="submit">Weiter</button>
            </form>
        </div>
    </div>
</body>
</html>