document.addEventListener("DOMContentLoaded", function () {
  const titleEl = document.querySelector("h1.main-title");

  if (titleEl) {
    const text = titleEl.textContent.trim();
    const words = text.split(" ");

    titleEl.textContent = ""; 
    titleEl.setAttribute("aria-label", text); 

    let charIndex = 0;

    words.forEach((word, wordIndex) => {
      const wordSpan = document.createElement("span");
      wordSpan.classList.add("word"); 
      wordSpan.style.display = "inline-block";
      wordSpan.style.whiteSpace = "nowrap"; 

      word.split("").forEach((char) => {
        const charSpan = document.createElement("span");
        charSpan.textContent = char;
        charSpan.setAttribute("aria-hidden", "true");
        charSpan.style.display = "inline-block";
        charSpan.style.opacity = "0";
        charSpan.style.filter = "blur(20px)";
        charSpan.style.transition = "filter 0.3s ease-out, opacity 0.1s ease-out";
        charSpan.style.transitionDelay = `${charIndex * 0.1}s`;
        wordSpan.appendChild(charSpan);

        charIndex++;
      });

      // Añadimos un espacio después de cada palabra, menos en la última
      if (wordIndex < words.length - 1) {
        const spaceSpan = document.createElement("span");
        spaceSpan.innerHTML = "&nbsp;";
        spaceSpan.setAttribute("aria-hidden", "true");
        spaceSpan.style.display = "inline-block";
        spaceSpan.style.opacity = "0";
        spaceSpan.style.filter = "blur(20px)";
        spaceSpan.style.transition = "filter 0.3s ease-out, opacity 0.1s ease-out";
        spaceSpan.style.transitionDelay = `${charIndex * 0.075}s`;
        wordSpan.appendChild(spaceSpan);

        charIndex++;
      }

      titleEl.appendChild(wordSpan);
    });

    requestAnimationFrame(() => {
      const spans = titleEl.querySelectorAll("span span");
      spans.forEach((span) => {
        span.style.opacity = "1";
        span.style.filter = "blur(0)";
      });
    });
  }
});