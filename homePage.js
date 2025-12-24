function navigateTo(path) {
    window.location.href = path;
}

function logout() {
    fetch("http://localhost:8000/api/logout", {
        method: "POST",
        credentials: "include"
    })
    .then(response => {
        if (response.ok) {
            window.location.href = "login.php";
        } else {
            console.error("Logout failed");
        }
    })
    .catch(error => console.error("Error:", error));
}