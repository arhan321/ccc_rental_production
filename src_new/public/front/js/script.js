document.addEventListener("DOMContentLoaded", function () {
  // Navbar logic
  const navbarToggler = document.querySelector(".navbar-toggler");
  const navbarCollapse = document.querySelector("#navbarNav");

  // Check if the elements exist before applying logic
  if (navbarCollapse && navbarToggler) {
    document.addEventListener('click', function(event) {
      const isClickInsideNavbar = navbarCollapse.contains(event.target) || navbarToggler.contains(event.target);
      const isNavbarShown = navbarCollapse.classList.contains("show");

      if (!isClickInsideNavbar && isNavbarShown) {
        const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
          toggle: true
        });
      }
    });
  }

  // Offcanvas Cart logic
  const offcanvasCart = document.getElementById("offcanvasCart");
  const overlayDark = document.getElementById("overlay-dark");

  // Check if offcanvasCart exists before initializing the Offcanvas instance
  if (offcanvasCart && overlayDark) {
    const offcanvasInstance = bootstrap.Offcanvas.getInstance(offcanvasCart);

    document.addEventListener('click', function(event) {
      if (offcanvasInstance && offcanvasCart.classList.contains("show") && !offcanvasCart.contains(event.target)) {
        offcanvasInstance.hide();
      }
    });

    // Klik overlay = tutup offcanvas
    overlayDark.addEventListener("click", function () {
      if (offcanvasInstance) {
        offcanvasInstance.hide();
      }
    });

    // Overlay logic ketika offcanvas dibuka / ditutup
    offcanvasCart.addEventListener('show.bs.offcanvas', () => {
      overlayDark.classList.add('show');
    });

    offcanvasCart.addEventListener('hidden.bs.offcanvas', () => {
      overlayDark.classList.remove('show');
    });
  }

  // Password toggle logic
  const togglePassword = document.getElementById('togglePassword');
  const passwordInput = document.getElementById('password');
  const eyeIcon = document.getElementById('eyeIcon');

  if (togglePassword && passwordInput && eyeIcon) {
    togglePassword.addEventListener('click', () => {
      const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordInput.setAttribute('type', type);
      eyeIcon.classList.toggle('bi-eye');
      eyeIcon.classList.toggle('bi-eye-slash');
    });
  }

  // Collapse element logic (for alurPenyewaan)
  const collapseElement = document.getElementById('alurPenyewaan');
  const arrowIcon = document.getElementById('arrowIcon');

  if (collapseElement && arrowIcon) {
    collapseElement.addEventListener('show.bs.collapse', function () {
      arrowIcon.classList.add('rotate-180');
    });

    collapseElement.addEventListener('hide.bs.collapse', function () {
      arrowIcon.classList.remove('rotate-180');
    });
  }
});
