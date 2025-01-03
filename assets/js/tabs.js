document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".nav-tab");
    const tabContents = document.querySelectorAll(".tab-content");

    tabs.forEach((tab) => {
        tab.addEventListener("click", function (e) {
            e.preventDefault();

            // Remove active class from all tabs and hide all tab contents
            tabs.forEach((t) => t.classList.remove("nav-tab-active"));
            tabContents.forEach((content) => (content.style.display = "none"));

            // Add active class to clicked tab and show its content
            this.classList.add("nav-tab-active");
            const target = this.getAttribute("href");
            document.querySelector(target).style.display = "block";
        });
    });
});