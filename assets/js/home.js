document.addEventListener("DOMContentLoaded", function() {
    const titleElement = document.querySelector(".site-main .container--header .section .message article .main-title");
  
    if (titleElement) {
      const originalText = titleElement.textContent.trim();
      titleElement.textContent = ""; // Limpiamos el contenido
  
      let index = 0;
  
      function typeLetterByLetter() {
        if (index < originalText.length) {
          titleElement.textContent += originalText.charAt(index);
          index++;
          setTimeout(typeLetterByLetter, 100); // Velocidad de escritura (ms por letra)
        }
      }
  
      typeLetterByLetter();
    }
  });