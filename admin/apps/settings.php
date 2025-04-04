<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/check.php";
require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/admin.php";

$session = new loginManager();
if (!$session->checkLogin()) {
	header("Location: /admin/login.php?from=apps/settings.php");
	exit;
}
?>

<!doctype html>
<!--
  Material Design Lite
  Copyright 2015 Google Inc. All rights reserved.

  Licensed under the Apache License, Version 2.0 (the "License");
  you may not use this file except in compliance with the License.
  You may obtain a copy of the License at

      https://www.apache.org/licenses/LICENSE-2.0

  Unless required by applicable law or agreed to in writing, software
  distributed under the License is distributed on an "AS IS" BASIS,
  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
  See the License for the specific language governing permissions and
  limitations under the License
-->
<html lang="de">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <title>Gästebuch // Admin // Einstellungen</title>

    <!-- Add to homescreen for Chrome on Android -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="images/android-desktop.png">

    <!-- Add to homescreen for Safari on iOS -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="Material Design Lite">
    <link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">

    <!-- Tile icon for Win8 (144x144 + tile color) -->
    <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
    <meta name="msapplication-TileColor" content="#3372DF">

    <link rel="shortcut icon" href="images/favicon.png">

    <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
    <!--
    <link rel="canonical" href="http://www.example.com/">
    -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.cyan-light_blue.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
    #view-source {
      position: fixed;
      display: block;
      right: 0;
      bottom: 0;
      margin-right: 40px;
      margin-bottom: 40px;
      z-index: 900;
    }
    .mdl-card__actions {
      display: flex;
	}
		
	.adress_item {
	  padding: 10px;
	  background-color: #DBDBDB;
	  border: none;
	  border-radius: 20px;
	  min-width: 200px;
	  max-width: 300px;
	  height: auto;
	  text-align: center;
	}
    </style>
  </head>
  <body>
    <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
      <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
        <div class="mdl-layout__header-row">
          <span class="mdl-layout-title">Home</span>
          <div class="mdl-layout-spacer"></div>
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--expandable">
          </div>
        </div>
      </header>
      <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
        <header class="demo-drawer-header">
          <div class="demo-avatar-dropdown">
            <span><?php echo $session->getUserName(); ?></span>
            <div class="mdl-layout-spacer"></div>
				<a href="../logout.php" style="color: rgba(255, 255, 255, 0.56);">
            <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
              <i class="material-icons" role="presentation">logout</i>
              <span class="visuallyhidden">Accounts</span>
            </button>
				</a>
          </div>
        </header>
        <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
          <a class="mdl-navigation__link" href="home.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Dashboard</a>
          <a class="mdl-navigation__link" href="entrys.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">book</i>Einträge</a>
          <a class="mdl-navigation__link" href="users.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">group</i>Benutzer</a>
          <a class="mdl-navigation__link" href="settings.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">settings</i>Einstellungen</a>
          </nav>
      </div>
      <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid demo-content" style="display: block;">
		  <?php
		  require $_SERVER["DOCUMENT_ROOT"] . "/admin/bin/rules.php";
		  $rules = new ruleManager();
		  $rule[1] = $rules->getRule("allow_entrys") == 1 ? "checked" : "";
		  $rule[2] = $rules->getRule("new_entry_mail_admin") == 1 ? "checked" : "";
		  $rule[3] = $rules->getRule("new_user_mail_admin") == 1 ? "checked" : "";
		  $rule[4] = $rules->getRule("new_entry_mail_user") == 1 ? "checked" : "";
		  $rule[5] = $rules->getRule("spamblock") == 1 ? "checked" : "";
		  $rule[6] = $rules->getRule("delete") == 1 ? "checked" : "";
		  ?>
          <h1>Gästebuch // Administration</h1><br>
		  <h2>Einstellungen</h2>
		  <h3>Einträge</h3>
		  <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="allow_entrys">
  			<input type="checkbox" id="allow_entrys" class="mdl-switch__input" <?php echo $rule[1]; ?>>
  			<span class="mdl-switch__label">Einträge müssen genehmigt werden</span>
		  </label><br><br>
		  <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="delete">
  			<input type="checkbox" id="delete" class="mdl-switch__input" <?php echo $rule[6]; ?>>
  			<span class="mdl-switch__label">Ersteller darf den Eintrag löschen und bearbeiten</span>
		  </label><br><br>
		  <h3>Benachrichtigungen</h3>
		  <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="new_entry_mail_admin">
  			<input type="checkbox" id="new_entry_mail_admin" class="mdl-switch__input" <?php echo $rule[2]; ?>>
  			<span class="mdl-switch__label">Bei neuen Einträgen alle Admins benachrichtigen</span>
		  </label><br><br>
		  <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="new_user_mail_admin">
  			<input type="checkbox" id="new_user_mail_admin" class="mdl-switch__input" <?php echo $rule[3]; ?>>
  			<span class="mdl-switch__label">Bei einer erstellung einen neues Benutzers alle Admins benachrichtigen</span>
		  </label><br><br>
		  <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="new_entry_mail_user">
  			<input type="checkbox" id="new_entry_mail_user" class="mdl-switch__input" <?php echo $rule[4]; ?>>
  			<span class="mdl-switch__label">Nachricht an ersteller des Eintrags senden</span>
		  </label><br><br>
		  <label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="spamblock">
  			<input type="checkbox" id="spamblock" class="mdl-switch__input" <?php echo $rule[5]; ?>>
  			<span class="mdl-switch__label">Spamfilter</span>
		  </label><br><br><br>
		  <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--colored mdl-color-text--white" onclick="save()">Speichern</button>
		</div>
      </main>
    </div>
      <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" style="position: fixed; left: -1000px; height: -1000px;">
        <defs>
          <mask id="piemask" maskContentUnits="objectBoundingBox">
            <circle cx=0.5 cy=0.5 r=0.49 fill="white" />
            <circle cx=0.5 cy=0.5 r=0.40 fill="black" />
          </mask>
          <g id="piechart">
            <circle cx=0.5 cy=0.5 r=0.5 />
            <path d="M 0.5 0.5 0.5 0 A 0.5 0.5 0 0 1 0.95 0.28 z" stroke="none" fill="rgba(255, 255, 255, 0.75)" />
          </g>
        </defs>
      </svg>
      <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 250" style="position: fixed; left: -1000px; height: -1000px;">
        <defs>
          <g id="chart">
            <g id="Gridlines">
              <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="27.3" x2="468.3" y2="27.3" />
              <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="66.7" x2="468.3" y2="66.7" />
              <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="105.3" x2="468.3" y2="105.3" />
              <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="144.7" x2="468.3" y2="144.7" />
              <line fill="#888888" stroke="#888888" stroke-miterlimit="10" x1="0" y1="184.3" x2="468.3" y2="184.3" />
            </g>
            <g id="Numbers">
              <text transform="matrix(1 0 0 1 485 29.3333)" fill="#888888" font-family="'Roboto'" font-size="9">500</text>
              <text transform="matrix(1 0 0 1 485 69)" fill="#888888" font-family="'Roboto'" font-size="9">400</text>
              <text transform="matrix(1 0 0 1 485 109.3333)" fill="#888888" font-family="'Roboto'" font-size="9">300</text>
              <text transform="matrix(1 0 0 1 485 149)" fill="#888888" font-family="'Roboto'" font-size="9">200</text>
              <text transform="matrix(1 0 0 1 485 188.3333)" fill="#888888" font-family="'Roboto'" font-size="9">100</text>
              <text transform="matrix(1 0 0 1 0 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">1</text>
              <text transform="matrix(1 0 0 1 78 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">2</text>
              <text transform="matrix(1 0 0 1 154.6667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">3</text>
              <text transform="matrix(1 0 0 1 232.1667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">4</text>
              <text transform="matrix(1 0 0 1 309 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">5</text>
              <text transform="matrix(1 0 0 1 386.6667 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">6</text>
              <text transform="matrix(1 0 0 1 464.3333 249.0003)" fill="#888888" font-family="'Roboto'" font-size="9">7</text>
            </g>
            <g id="Layer_5">
              <polygon opacity="0.36" stroke-miterlimit="10" points="0,223.3 48,138.5 154.7,169 211,88.5
              294.5,80.5 380,165.2 437,75.5 469.5,223.3 	"/>
            </g>
            <g id="Layer_4">
              <polygon stroke-miterlimit="10" points="469.3,222.7 1,222.7 48.7,166.7 155.7,188.3 212,132.7
              296.7,128 380.7,184.3 436.7,125 	"/>
            </g>
          </g>
        </defs>
      </svg>
      <script src="https://code.getmdl.io/1.3.0/material.min.js"></script>
	  <script src="js/settings.js"></script>
  </body>
</html>