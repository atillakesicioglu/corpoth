const navToggle = document.getElementById("nav-toggle");
const mobileMenu = document.getElementById("mobile-menu");
const footerYear = document.getElementById("footer-year");

if (footerYear) {
  footerYear.textContent = String(new Date().getFullYear());
}

if (navToggle && mobileMenu) {
  const setOpen = (open) => {
    mobileMenu.classList.toggle("hidden", !open);
    navToggle.setAttribute("aria-expanded", open ? "true" : "false");
  };

  navToggle.addEventListener("click", () => {
    setOpen(mobileMenu.classList.contains("hidden"));
  });

  mobileMenu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => setOpen(false));
  });
}
