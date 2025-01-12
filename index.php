<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gästebuch</title>
    <link rel="stylesheet" href="/css/main.css">
	<script src="https://www.google.com/recaptcha/api.js"></script>
</head>
<body>
    <header>
        <h1>Gästebuch</h1>
		<?php
		if (isset($_GET["error"])) {
			echo "<p>" . $_GET["error"] . "</p>";
		}
		?>
    </header>
    <main>
        <section class="entry-form">
            <h2>Neuer Eintrag</h2><br>
            <form action="bin/add.php" method="post">
                <input type="text" name="name" placeholder="Name" required><br>
				<input type="mail" name="mail" placeholder="Email" required><br>
                <textarea name="text" placeholder="Text" required></textarea>
				<div class="g-recaptcha" data-sitekey="6LcEG5AqAAAAACvFjzYM3Zo_Gf3Kk_QMsfa21Rws"></div>
                <button type="submit">Eintragen!</button><br>
				<small style="color: grey;">Deine Email Adresse wird nicht öffentlich angezeigt.</small>
            </form>
        </section>
        <section class="entries">
            <h2>Einträge</h2>
			<?php
            ini_set("display_errors", 1);
            ini_set("display_startup_errors", 1);
		    error_reporting(E_ALL);
		
		    require "admin/bin/db.php";
		
		    $sql = "SELECT * FROM entrys WHERE status = 0 ORDER BY id DESC";
		    $stmt = $conn->prepare($sql);
		    $stmt->execute();
		    $result = $stmt->get_result();
			
			if ($result->num_rows == 0) {
				echo '<p>Es gibt keine Einträge</p>';
			}
		
		    while($row = $result->fetch_assoc()) {
			    echo '
			    <article class="entry">
				    <h3>' . $row["name"] . '</h3>
				    <p>' . $row["text"] . '</p>
				    <span>' . $row["date"] . '</span><br>
					<a onclick="remove(' . $row["id"] . ')">Löschen</a>
			    </article><hr>';
		    }
		    ?>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Marc Goering</p>
		<a href="https://github.com/marc-go/guestbook.git">GitHub</a>
    </footer>
	<script src="js/remove.js"></script>
</body>
</html>