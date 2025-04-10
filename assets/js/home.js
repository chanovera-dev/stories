document.addEventListener("DOMContentLoaded", function () {
    const titleEl = document.querySelector(".site-main .container--header .section .message article .main-title");
    const refEl = document.querySelector(".site-main .container--header .section .message article .reference");
  
    if (titleEl && refEl) {
      const titleText = titleEl.textContent.trim();
      const refText = refEl.textContent.trim();
  
      titleEl.textContent = "";
      refEl.textContent = "";
  
      function typeAnimated(el, text, callback) {
        const words = text.split(" ");
        let wordIndex = 0;
        let letterIndex = 0;
  
        function typeWord() {
          if (wordIndex < words.length) {
            const wordSpan = document.createElement("span");
            wordSpan.classList.add("word");
            el.appendChild(wordSpan);
  
            const word = words[wordIndex];
            let i = 0;
  
            function typeLetter() {
              if (i < word.length) {
                const char = word[i];
                const span = document.createElement("span");
                span.classList.add("letter");
                span.textContent = char;
                span.style.animationDelay = `${letterIndex * 0.03}s`;
                wordSpan.appendChild(span);
                i++;
                letterIndex++;
                setTimeout(typeLetter, 100);
              } else {
                // Añadir espacio después de la palabra
                const space = document.createElement("span");
                space.classList.add("space");
                space.innerHTML = "&nbsp;";
                el.appendChild(space);
                wordIndex++;
                setTimeout(typeWord, 100);
              }
            }
  
            typeLetter();
          } else if (typeof callback === "function") {
            callback();
          }
        }
  
        typeWord();
      }
  
      setTimeout(() => {
        typeAnimated(titleEl, titleText, () => {
          typeAnimated(refEl, refText);
        });
      }, 1000);
    }
  });