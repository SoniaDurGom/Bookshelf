window.onload = function() {
    const cambiarContrasena = document.getElementById("cambiar-contrasena");
    const passwordFields = document.getElementById("password-fields");
    cambiarContrasena.addEventListener("click", () => {
      passwordFields.style.display = passwordFields.style.display === "none" ? "block" : "none";
    });
  };
  