document.addEventListener("DOMContentLoaded", function () {
    const titleEl = document.querySelector(".site-main .container--header .section .message article .main-title");
    const refEl = document.querySelector(".site-main .container--header .section .message article .reference");
  
    if (titleEl && refEl) {
      const titleText = titleEl.textContent.trim();
      const refText = refEl.textContent.trim();
  
      titleEl.textContent = "";
      refEl.textContent = "";
  
      function typeAnimated(el, text, callback) {
        let i = 0;
        function type() {
          if (i < text.length) {
            const span = document.createElement("span");
            span.classList.add("letter");
            span.style.animationDelay = `${i * 0.03}s`; // Pequeño delay para que no se encimen
            span.textContent = text[i];
            el.appendChild(span);
            i++;
            setTimeout(type, 100); // velocidad por letra
          } else if (typeof callback === "function") {
            callback();
          }
        }
        type();
      }
  
      setTimeout(() => {
        typeAnimated(titleEl, titleText, () => {
          typeAnimated(refEl, refText);
        });
      }, 1000); // espera 1 segundo antes de empezar
    }
});