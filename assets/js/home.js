document.addEventListener("DOMContentLoaded", function () {
  const titleEl = document.querySelector(".site-main .container--header .section .message article .main-title");
  const refEl = document.querySelector(".site-main .container--header .section .message article .reference");

  if (titleEl && refEl) {
    const titleText = titleEl.textContent.trim();
    const refText = refEl.textContent.trim();

    titleEl.textContent = "";
    refEl.textContent = "";

    function typeText(el, text, callback) {
      let i = 0;
      function type() {
        if (i < text.length) {
          el.textContent += text.charAt(i);
          i++;
          setTimeout(type, 100); // velocidad por letra
        } else if (typeof callback === 'function') {
          callback();
        }
      }
      type();
    }

    setTimeout(() => {
      typeText(titleEl, titleText, () => {
        typeText(refEl, refText);
      });
    }, 1000); // espera 1 segundo antes de empezar
  }
});