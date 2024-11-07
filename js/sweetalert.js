document.addEventListener("DOMContentLoaded", () => {
    const usuario = document.body.getAttribute('data-usuario');
    if (usuario) {
        Swal.fire({
            title: '¡Bienvenido!',
            text: `Hola ${usuario}, bienvenido al portal.`,
            icon: 'success',
            confirmButtonText: 'Gracias'
        });
    }
}); 