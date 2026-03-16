import "../components/enhancements";

const root = document.documentElement;

if (root) {
  root.classList.add("hb-enhanced");
}

const setupSharedMenu = (): void => {
  const toggle = document.querySelector<HTMLButtonElement>(".hb-menu-toggle");
  const panel = document.querySelector<HTMLElement>(".hb-mobile-menu-panel");

  if (!toggle || !panel) {
    return;
  }

  const closeMenu = (): void => {
    toggle.setAttribute("aria-expanded", "false");
    panel.hidden = true;
  };

  const openMenu = (): void => {
    toggle.setAttribute("aria-expanded", "true");
    panel.hidden = false;
  };

  toggle.addEventListener("click", () => {
    const expanded = toggle.getAttribute("aria-expanded") === "true";

    if (expanded) {
      closeMenu();
      return;
    }

    openMenu();
  });

  document.addEventListener("click", (event) => {
    const target = event.target;

    if (!(target instanceof Node)) {
      return;
    }

    if (!panel.contains(target) && !toggle.contains(target)) {
      closeMenu();
    }
  });

  window.addEventListener("resize", () => {
    if (window.innerWidth >= 782) {
      closeMenu();
    }
  });
};

const setupDesktopDropdowns = (): void => {
  const items = document.querySelectorAll<HTMLElement>(".hb-menu--desktop .menu-item-has-children");

  items.forEach((item) => {
    item.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        const link = item.querySelector<HTMLAnchorElement>("a");
        link?.focus();
      }
    });
  });
};

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", setupSharedMenu, { once: true });
  document.addEventListener("DOMContentLoaded", setupDesktopDropdowns, { once: true });
} else {
  setupSharedMenu();
  setupDesktopDropdowns();
}
