document.addEventListener("DOMContentLoaded", function () {
    const titleEl = document.querySelector(".site-main .container--header .section .message article .main-title");
    const refEl = document.querySelector(".site-main .container--header .section .message article .reference");
  
    if (titleEl && refEl) {
      const titleText = titleEl.textContent.trim();
      const refText = refEl.textContent.trim();
  
      titleEl.textContent = "";
      refEl.textContent = "";
  
      function typeText(el, text, speed, callback) {
        let i = 0;
        function type() {
          if (i < text.length) {
            el.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
          } else if (typeof callback === 'function') {
            callback();
          }
        }
        type();
      }
  
      setTimeout(() => {
        typeText(titleEl, titleText, 40, () => {
          setTimeout(() => {
            typeText(refEl, refText, 120);
          }, 500);
        });
      }, 600);
    }
  });
  