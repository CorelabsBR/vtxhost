document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    document.getElementById("success").innerText = "";
    document.getElementById("error-geral").innerText = "";

    const dados = {
        email: document.getElementById("email").value,
        senha: document.getElementById("password").value
    };

    fetch("/auth/login", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(dados)
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            document.getElementById("success").innerText = data.message;
            window.location.href = "/areacliente";
            return;
        }

        document.getElementById("error-geral").innerText = data.message;
    })
    .catch(() => {
        document.getElementById("error-geral").innerText = "Ocorreu um erro ao fazer login. Tente novamente.";
    });
});