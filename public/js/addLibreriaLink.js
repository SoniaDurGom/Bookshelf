window.onload = function() {
    const agregarLibreriaBtn = document.getElementById('agregarLibreria');
    const formNuevaLibreria = document.getElementById('formNuevaLibreria');
    const nuevaLibreriaInput = document.getElementById('nuevaLibreria');

    agregarLibreriaBtn.addEventListener('click', () => {
        formNuevaLibreria.style.display = 'block';
        nuevaLibreriaInput.focus();
    });
};

