document.addEventListener("DOMContentLoaded", function () {
  const contentDiv = document.getElementById("content");
  const routes = {
    "/hybrid_rendering/home": "pages/home.php",
    "/hybrid_rendering/about": "pages/about.php",
    "/hybrid_rendering/signup": "pages/signup.php",
    "/hybrid_rendering/login": "pages/login.php",
    "/hybrid_rendering/forgot": "pages/forgot.php",
    "/hybrid_rendering/OTP": "pages/OTP.php",
    "/hybrid_rendering/changepw": "pages/changepw.php",
  };

  const cache = {}; // Object to store cached pages

  function loadPage(url) {
    const page = routes[url] || routes["/hybrid_rendering/home"];

    if (cache[page]) {
      contentDiv.innerHTML = cache[page];
      attachLinkHandlers();
      attachFormHandler();
      const previousPage = sessionStorage.getItem("previousPage");

      if (
        url === "/hybrid_rendering/login" &&
        previousPage === "/hybrid_rendering/changepw"
      ) {
        const popup = document.querySelector(".popup");
        popup.classList.add("active");
      }

      sessionStorage.setItem("previousPage", url); // Update the previous page
    } else {
      fetch(page)
        .then((response) => response.text())
        .then((data) => {
          cache[page] = data;
          contentDiv.innerHTML = data;
          attachLinkHandlers();
          attachFormHandler();
          const previousPage = sessionStorage.getItem("previousPage");

          if (
            url === "/hybrid_rendering/login" &&
            previousPage === "/hybrid_rendering/changepw"
          ) {
            const popup = document.querySelector(".popup");
            popup.classList.add("active");
          }

          sessionStorage.setItem("previousPage", url); // Update the previous page
        })
        .catch((error) => console.error("Error:", error));
    }
  }

  function handleNavigation(event) {
    event.preventDefault();
    const url = event.target.getAttribute("href");
    window.history.pushState({}, "", url);

    loadPage(url);
  }

  function attachLinkHandlers() {
    document.querySelectorAll("a[data-route]").forEach((link) => {
      link.addEventListener("click", handleNavigation);
    });
  }

  function attachFormHandler() {
    const form = document.querySelector("form");
    if (form) {
      form.addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch(routes[window.location.pathname], {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.redirect && data.redirect.forgot) {
              const parent = document.querySelector(".parent");
              parent.classList.remove("active");
              loadPage(data.redirect.forgot);
              const url = data.redirect.forgot;
              window.history.pushState({}, "", url);
            } else if (data.status === "success") {
              const parent = document.querySelector(".parent");
              if (parent) {
                parent.classList.remove("active");
              }
              window.location.href = `${data.redirect}`;
              loadPage(data.redirect);
            } else {
              const parent = document.querySelector(".parent");
              if (parent) {
                parent.classList.remove("active");
              }
              const errorElem = document.querySelector(".error");
              if (errorElem) {
                errorElem.textContent = data.message;
              }
            }
          })
          .catch((error) => console.error("Error:", error));
      });
    }
  }

  window.addEventListener("popstate", () => {
    loadPage(window.location.pathname);
  });

  attachLinkHandlers();
  loadPage(window.location.pathname);
});
