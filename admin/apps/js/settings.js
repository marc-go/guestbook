function save() {
	var values = {
		allow_entrys: document.getElementById("allow_entrys").checked,
		new_entry_mail_admin: document.getElementById("new_entry_mail_admin").checked,
		new_user_mail_admin: document.getElementById("new_user_mail_admin").checked,
		new_entry_mail_user: document.getElementById("new_entry_mail_user").checked,
		spamblock: document.getElementById("spamblock").checked,
		delete: document.getElementById("delete").checked
	}
	
	fetch("/admin/apps/bin/change_rule.php", {
    	method: 'POST',
    	headers: {
        	'Content-Type': 'application/json'
    	},
    	body: JSON.stringify(values)
	})
		.then(response => response.json())
		.then(data => {
			if (data.status == 200) {
				window.location.reload();
			}else{
				alert(data.error);
			}
		})
		.catch(error => alert(error))
}