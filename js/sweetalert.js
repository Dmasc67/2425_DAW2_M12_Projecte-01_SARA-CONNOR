document.addEventListener("DOMContentLoaded", () => {
    const usuario = document.body.getAttribute('data-usuario');
    const sweetalertMostrado = document.body.getAttribute('data-sweetalert') === 'true';

    if (usuario && !sweetalertMostrado) {
        Swal.fire({
            title: '¡Bienvenido!',
            text: `Hola ${usuario}, bienvenido al portal.`,
            icon: 'success',
            confirmButtonText: 'Gracias'
        }).then(() => {
            // Establecer la variable de sesión para indicar que el SweetAlert ya se mostró
            fetch('./php/marcar_sweetalert_mostrado.php');
        });
    }
}); 