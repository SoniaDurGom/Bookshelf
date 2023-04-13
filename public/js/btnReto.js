window.onload = function() {
    const objetivoInput = document.getElementById('libros_objetivo');
    const guardarBtn = document.getElementById('guardarBtn');

    objetivoInput.addEventListener('input', function() {
        guardarBtn.disabled = (objetivoInput.value < 1);
    });
};