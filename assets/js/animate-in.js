document.addEventListener('DOMContentLoaded', function () {
  const targets = document.querySelectorAll('.site-main .container--posts .content .posts .post, .site-main .container--posts .content aside div, .site-main .container--posts .content aside form, .site-main .post-body .content aside div, .site-main .post-body .content aside form');

  if (!targets.length) return;

  const observer = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry, index) => {
        if (entry.isIntersecting && entry.intersectionRatio >= 0.1) {
          setTimeout(() => {
            entry.target.classList.add('animate-in');
            observer.unobserve(entry.target);
          }, index * 500); // Escalonar con un retraso de 0.5s por elemento
        }
      });
    },
    {
      threshold: 0.1,
    }
  );

  targets.forEach(target => observer.observe(target));
});