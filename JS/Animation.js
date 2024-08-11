document.addEventListener('DOMContentLoaded', () => {
  gsap.registerPlugin(ScrollTrigger);

  gsap.from('.marketing .row > div', {
    scrollTrigger: {
      trigger: '.marketing',
      start: 'top 80%',
      end: 'bottom top',
    },
    opacity: 0,
    y: 50,
    duration: 1,
    stagger: 0.3
  });

  gsap.from('.featurette', {
    scrollTrigger: {
      trigger: '.featurette',
      start: 'top 80%',
      end: 'bottom top',
    },
    opacity: 0,
    y: 100,
    duration: 1,
    stagger: 0.5
  });

  gsap.from('.featurette-2', {
    scrollTrigger: {
      trigger: '.featurette-2',
      start: 'top 80%',
      end: 'bottom top',
    },
    opacity: 0,
    y: 100,
    duration: 1,
    stagger: 0.5
  });

  gsap.from('.featurette-3', {
    scrollTrigger: {
      trigger: '.featurette-3',
      start: 'top 80%',
      end: 'bottom top',
    },
    opacity: 0,
    y: 100,
    duration: 1,
    stagger: 0.5
  });
});
