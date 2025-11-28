function save() {
    var rules = {};

    fetch("/admin/apps/bin/settings.json")
        .then(response => response.json())
        .then(data => {
            console.log(data);

            // Fülle das rules-Objekt
            Object.entries(data).forEach(([rule_id, rule_n]) => {
                var rule_v = document.getElementById(rule_id);
                if (rule_v) {
                    rules[rule_id] = rule_v.checked;
                } else {
                    console.log("error:: undefined for id", rule_id);
                }
            });

            console.log("Rules to send:", JSON.stringify(rules));

            return fetch("/admin/apps/bin/change_rule.php", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(rules)
            });
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 200) {
                var snackbarContainer = document.querySelector('#demo-snackbar-example');
                if (snackbarContainer && snackbarContainer.MaterialSnackbar) {
                    var dataSnackbar = {
                        message: 'Successfully saved',
                        timeout: 2000
                    };
                    snackbarContainer.MaterialSnackbar.showSnackbar(dataSnackbar);
                } else {
                    console.log("Snackbar-Element oder MaterialSnackbar nicht gefunden");
                }
            } else {
                alert(data.error || "Unknown error");
            }
        })
        .catch(error => alert("Fetch error: " + error));
}