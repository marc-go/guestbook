function remove(id) {
	fetch("/bin/remove.php?id=" + id)
		.then(response => response.json())
		.then(data => {
			if (data.status == 200) {
				alert("Bitte überprüfe dein Postfach.");
			}else{
				alert(data.error);
			}
		})
		.catch(error => alert(error))
}