<?php
require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/check.php";
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guestbook</title>
  <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
  <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
</head>
<body>
  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
    <header class="mdl-layout__header">
      <div class="mdl-layout__header-row">
        <span class="mdl-layout-title">Guestbook</span>
        <div class="mdl-layout-spacer"></div>
      </div>
    </header>
    <main class="mdl-layout__content">
      <div class="page-content" style="padding: 20px;">
  		<dialog class="mdl-dialog">
    	  <h4 class="mdl-dialog__title">Suggest a new feature</h4>
    	  <div class="mdl-dialog__content">
      		<p>
        	  Thank you that you want to suggest a new feature.
      		</p>
			<form action="/bin/suggestion.php" method="post">
			  <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                <input class="mdl-textfield__input" type="text" name="feature" id="feature" required>
                <label class="mdl-textfield__label" for="feature">Feature</label>
              </div>
              <div class="mdl-dialog__actions">
      		    <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">Send</button>
      			<button type="button" class="mdl-button close" onclick="dialog.close()">Close</button>
    	  	  </div>
			</form>
		  </div>
  		</dialog>
  		<script>
    	  var dialog = document.querySelector('dialog');
    	  var showDialogButton = document.querySelector('#show-dialog');
    	  if (! dialog.showModal) {
      		dialogPolyfill.registerDialog(dialog);
    	  }
    	  showDialogButton.addEventListener('click', function() {
      	    dialog.showModal();
    	  });
    	  dialog.querySelector('.close').addEventListener('click', function() {
      		dialog.close();
    	  });
  		</script>
        <h1>Guestbook</h1>
		<p>
			<?php
			if (isset($_GET["error"])) {
				echo $_GET["error"];
			}
			?>
		</p>
        <form action="bin/add.php" method="post">
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" name="name" id="name" required>
            <label class="mdl-textfield__label" for="name">Name</label>
          </div><br>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="mail" name="mail" required>
            <label class="mdl-textfield__label" for="mail">Email</label>
          </div><br>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <textarea class="mdl-textfield__input" id="message" rows="4" name="text" required></textarea>
            <label class="mdl-textfield__label" for="message">Meassage</label>
          </div><br><br>
          <button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored">
            Add entry
          </button>
        </form>
        <h2>Entrys</h2>
        <div id="entries">
            <div style="margin-bottom: 20px;">
				<?php
				ini_set("display_errors", 1);
				ini_set("display_startup_errors", 1);
				error_reporting(E_ALL);
				
				//Get Rules
				require "admin/bin/rules.php";
				$rules = new ruleManager();
				
				//Start Database Connection
            	require "admin/bin/db.php";
		
				//Get all allowed entrys
		    	$sql = "SELECT * FROM entrys WHERE status = 0 ORDER BY id DESC";
		    	$stmt = $conn->prepare($sql);
		    	$stmt->execute();
		    	$result = $stmt->get_result();
			
				if ($result->num_rows == 0) {
					echo '<p>They are not any entrys.</p>';
				}
				
				//Show all entrys
		    	while($row = $result->fetch_assoc()) {
			    	echo '
			     	<h3>' . $row["name"] . '</h3>
				    <p>' . $row["text"] . '</p>
				    <span>' . $row["date"] . '</span><br>';
					if ($rules->getRule("delete") == 1) {
						echo '<a onclick="remove(' . $row["id"] . ')">Delete</a>';
					}
					echo '<hr>';
		    	}
		    	?>
            </div>
        </div>
      </div>
    </main>
	<footer class="mdl-mini-footer">
  	  <div class="mdl-mini-footer__left-section">
        <div class="mdl-logo">marc-go/guestbook</div>
  	  </div>
  	  <div class="mdl-mini-footer__right-section">
    	<ul class="mdl-mini-footer__link-list">
      	  <li><a href="https://github.com/marc-go/guestbook.git">GitHub</a></li>
      	  <li><a onclick="dialog.showModal()">Suggest a new feature</a></li>
        </ul>
  	  </div>
	</footer>
  </div>
  <script src="/js/remove.js"></script>
</body>
</html>
