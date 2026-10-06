const menuToggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.primary-nav');
const navigationLinks = document.querySelectorAll('.nav-link');
const placeholderActions = document.querySelectorAll('[data-placeholder-action]');
const toast = document.querySelector('.toast');
const year = document.querySelector('#current-year');
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

let toastTimer;

function closeMenu() {
  if (!menuToggle || !navigation) return;

  menuToggle.setAttribute('aria-expanded', 'false');
  menuToggle.setAttribute('aria-label', 'Открыть меню');
  navigation.classList.remove('is-open');
  document.body.classList.remove('menu-open');
}

function showToast() {
  if (!toast) return;

  window.clearTimeout(toastTimer);
  toast.classList.add('is-visible');
  toastTimer = window.setTimeout(() => {
    toast.classList.remove('is-visible');
  }, 2600);
}

if (menuToggle && navigation) {
  menuToggle.addEventListener('click', () => {
    const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
    menuToggle.setAttribute('aria-expanded', String(!isOpen));
    menuToggle.setAttribute('aria-label', isOpen ? 'Открыть меню' : 'Закрыть меню');
    navigation.classList.toggle('is-open', !isOpen);
    document.body.classList.toggle('menu-open', !isOpen);
  });

  navigationLinks.forEach((link) => {
    link.addEventListener('click', closeMenu);
  });

  window.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') closeMenu();
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 760) closeMenu();
  });
}

placeholderActions.forEach((control) => {
  control.addEventListener('click', showToast);
});

if (year) {
  year.textContent = new Date().getFullYear();
}

const revealElements = document.querySelectorAll('.reveal');

if (reduceMotion || !('IntersectionObserver' in window)) {
  revealElements.forEach((element) => element.classList.add('is-visible'));
} else {
  const revealObserver = new IntersectionObserver(
    (entries, observer) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      });
    },
    {
      threshold: 0.12,
      rootMargin: '0px 0px -4% 0px',
    },
  );

  revealElements.forEach((element) => revealObserver.observe(element));
}
