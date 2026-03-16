(() => {
  const root = document.documentElement;

  if (root) {
    root.classList.add("hb-enhanced");
  }

  const setupSharedMenu = () => {
    const toggle = document.querySelector(".hb-menu-toggle");
    const panel = document.querySelector(".hb-mobile-menu-panel");

    if (!toggle || !panel) {
      return;
    }

    const closeMenu = () => {
      toggle.setAttribute("aria-expanded", "false");
      panel.hidden = true;
    };

    const openMenu = () => {
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

  const enhanceExternalLinks = () => {
    const links = document.querySelectorAll('a[target="_blank"]');

    links.forEach((link) => {
      const rel = new Set((link.getAttribute("rel") || "").split(/\s+/).filter(Boolean));
      rel.add("noopener");
      rel.add("noreferrer");
      link.setAttribute("rel", Array.from(rel).join(" "));
    });
  };

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", setupSharedMenu, { once: true });
    document.addEventListener("DOMContentLoaded", enhanceExternalLinks, { once: true });
  } else {
    setupSharedMenu();
    enhanceExternalLinks();
  }
})();
