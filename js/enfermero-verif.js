const datosCorrectos = {
  usuario: "developer",
  contrasena: "contradeveloper",
};

function formato(comando) {
  document.execCommand(comando, false, null);
}
const formulario = document.getElementById("formulario");

formulario.addEventListener("submit", function (event) {
  event.preventDefault();

  const usuario = document.getElementById("validationDefaultUsername").value;
  const contrasena = document.getElementById("validationDefault03").value;

  if (
    usuario === datosCorrectos.usuario &&
    contrasena === datosCorrectos.contrasena
  ) {
    window.location.href = "../html/enfermero-menu.html";
  } else {
    alert("Los datos introducidos son incorrectos.");
  }
});
