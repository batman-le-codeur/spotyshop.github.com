 // Gestion du menu burger / sidebar
        const menuToggle = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");
        const closeBtn = document.getElementById("close-btn");

        menuToggle.addEventListener("click", () => {
            sidebar.classList.add("active");
        });

        closeBtn.addEventListener("click", () => {
            sidebar.classList.remove("active");
        });

        // Fermer quand on clique sur un lien
        document.querySelectorAll(".sidebar a").forEach(link => {
            link.addEventListener("click", () => {
                sidebar.classList.remove("active");
            });
        });