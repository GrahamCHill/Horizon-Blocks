(() => {
  const root = document.documentElement;

  if (root) {
    root.classList.add("hb-enhanced");
  }

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
    document.addEventListener("DOMContentLoaded", enhanceExternalLinks, { once: true });
  } else {
    enhanceExternalLinks();
  }
})();
