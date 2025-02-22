function allow(id) {
	fetch("/admin/apps/bin/status.php?id=" + id + "&mode=allow")
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

function block(id) {
	fetch("/admin/apps/bin/status.php?id=" + id + "&mode=block")
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

function remove(id) {
	fetch("/admin/apps/bin/remove.php?id=" + id)
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