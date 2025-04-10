document.addEventListener("DOMContentLoaded", function () {
    const titleEl = document.querySelector(".site-main .container--header .section .message article .main-title");
    const refEl = document.querySelector(".site-main .container--header .section .message article .reference");
  
    const letterDelay = 100; // Tiempo entre letras en ms
    const animationDelayUnit = 0.03; // para animation-delay en segundos
  
    // Inserta el estilo embebido con duración sincronizada
    const style = document.createElement("style");
    style.textContent = `
      .word {
        white-space: nowrap;
        display: inline-block;
      }
      .letter {
        display: inline;
        opacity: 0;
        transform: translateY(10px);
        animation: jump-in ${letterDelay}ms ease forwards;
      }
      .space {
        display: inline;
        opacity: 1;
        width: 0.5em;
      }
      @keyframes jump-in {
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }
    `;
    document.head.appendChild(style);
  
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
                span.style.animationDelay = `${letterIndex * animationDelayUnit}s`;
                wordSpan.appendChild(span);
                i++;
                letterIndex++;
                setTimeout(typeLetter, letterDelay);
              } else {
                const space = document.createElement("span");
                space.classList.add("space");
                space.innerHTML = "&nbsp;";
                el.appendChild(space);
                wordIndex++;
                setTimeout(typeWord, letterDelay);
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
  