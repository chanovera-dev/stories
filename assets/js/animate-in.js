document.addEventListener('DOMContentLoaded', function () {
  const targets = document.querySelectorAll('.site-main .container--posts .content .posts .post, .site-main .container--posts .content aside div, .site-main .container--posts .content aside form, .site-main .post-body .content aside div, .site-main .post-body .content aside form');

  if (!targets.length) return;

  const observer = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting && entry.intersectionRatio >= 0.1) {
          entry.target.classList.add('animate-in');
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.5,
    }
  );

  targets.forEach(target => observer.observe(target));
});