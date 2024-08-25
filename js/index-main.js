document.addEventListener("DOMContentLoaded", function () {
  const contentDiv = document.getElementById("content");

  const routes = {
    "/hybrid_rendering/home": "pages/home.php",
    "/hybrid_rendering/about": "pages/about.php",
    "/hybrid_rendering/logout": "pages/logout.php",
    "/hybrid_rendering/dashboard": "pages/dashboard.php",
  };

  const cache = {}; // Object to store cached pages

  function loadPage(url) {
    const page = routes[url] || routes["/hybrid_rendering/dashboard"];

    if (page.includes("logout")) {
      fetch(routes[window.location.pathname]).then((data) => {
        window.location.href = "/hybrid_rendering/index.php";
      });
    }

    if (cache[page]) {
      // If the page is in cache, use it
      contentDiv.innerHTML = cache[page];
      attachLinkHandlers(); // Re-attach event handlers
    } else {
      // If the page is not in cache, fetch it
      fetch(page)
        .then((response) => response.text())
        .then((data) => {
          cache[page] = data; // Cache the page
          contentDiv.innerHTML = data;
          attachLinkHandlers(); // Re-attach event handlers
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

  window.addEventListener("popstate", () => {
    loadPage(window.location.pathname);
  });

  attachLinkHandlers(); // Attach event handlers when the page initially loads
  loadPage(window.location.pathname);
});
