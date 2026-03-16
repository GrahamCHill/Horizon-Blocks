const enhanceExternalLinks = (): void => {
  const links = document.querySelectorAll<HTMLAnchorElement>('a[target="_blank"]');

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
