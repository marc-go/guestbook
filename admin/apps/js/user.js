function edit(id) {
	const url = '/admin/apps/bin/edit_user.php';
	const daten = {
		id: id,
    	name: document.getElementById("name-i").value,
    	mail: document.getElementById("mail-i").value,
		pw: document.getElementById("pw-i").value,
		pw2: document.getElementById("pw2-i").value
	};

	fetch(url, {
    	method: 'POST',
    	headers: {
        	'Content-Type': 'application/x-www-form-urlencoded'
    	},
    	body: JSON.stringify(daten)
	})
		.then(response => response.json())
		.then(data => {
    		if (data.status == 200) {
				window.location.href = "/admin/apps/users.php";
			}else{
				alert(data.error);
			}
		})
		.catch(error => alert(error))
}

function remove(id) {
	if (confirm("Möchten sie diesen Benutzer wirklich löschen?")) {
		fetch("/admin/apps/bin/remove_user.php?id=" + id)
			.then(response => response.json())
			.then(data => {
				if (data.status == 200) {
					document.getElementById(id).remove();
				}else{
					alert(data.error);
				}
			})
			.catch(error => alert(error))
	}
}

function addUser() {
	const url = "/admin/apps/bin/add_user.php";
	
	const name = document.getElementById("name-i2").value;
	const mail = document.getElementById("mail-i2").value;
	const pw = document.getElementById("pw-i2").value;
	const pw2 = document.getElementById("pw2-i2").value;
	
	const daten = {
		name: name,
		mail: mail,
		pw: pw,
		pw2: pw2
	};
	
	fetch(url, {
    	method: 'POST',
    	headers: {
        	'Content-Type': 'application/x-www-form-urlencoded'
    	},
    	body: JSON.stringify(daten)
	})
		.then(response => response.json())
		.then(data => {
			if (data.status == 200) {
				window.location.href = "/admin/apps/users.php";
			}else{
				alert(data.error);
			}
		})
		.catch(error => alert(error))
}