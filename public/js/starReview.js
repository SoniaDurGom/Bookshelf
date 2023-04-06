window.onload = function() {
    const stars = document.querySelectorAll("#stars i");
    const puntuacionInput = document.querySelector('#puntuacion');

   
    // Cambiar el color de las estrellas al pasar el cursor por encima
    stars.forEach(star => {
        star.addEventListener("mouseover", function() {
            const value = this.getAttribute('data-value');
            for (let i = 0; i < stars.length; i++) {
                if (i < value) {
                    stars[i].classList.add("fas");
                    stars[i].classList.remove("far");
                } else {
                    stars[i].classList.add("far");
                    stars[i].classList.remove("fas");
                }
            }
        });
    });

    // Mantener las estrellas pintadas después de hacer clic
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = star.getAttribute('data-value');
            puntuacionInput.value = value;
            stars.forEach(star => {
                if (star.getAttribute('data-value') <= value) {
                    star.classList.remove('far');
                    star.classList.add('fas');
                } else {
                    star.classList.remove('fas');
                    star.classList.add('far');
                }
            });
        });
    });

    // document.getElementById("btn-valorar").addEventListener("click", function() {
    //     if (rating === 0) {
    //         alert("Selecciona al menos una estrella antes de valorar.");
    //         return;
    //     }
        
        
    // });


 













  };




  
