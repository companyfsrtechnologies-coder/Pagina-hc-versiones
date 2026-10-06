const datosCorrectos = {
  usuario: "developer",
  contrasena: "contradeveloper",
};

const formulario = document.getElementById("formulario");

formulario.addEventListener("submit", function (event) {
  event.preventDefault();

  const usuario = document.getElementById("validationDefaultUsername").value;
  const contrasena = document.getElementById("validationDefault03").value;

  if (
    usuario === datosCorrectos.usuario &&
    contrasena === datosCorrectos.contrasena
  ) {
    window.location.href = "../html/admin-menu.html";
  } else {
    alert("Los datos introducidos son incorrectos.");
  }
});
