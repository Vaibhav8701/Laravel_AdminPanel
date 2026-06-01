  function toggleSubmenu(id, element) {
        let menu = document.getElementById(id);
        let isOpen = menu.classList.contains("open");

        // close all submenus
        document.querySelectorAll(".submenu").forEach(sm => sm.classList.remove("open"));

        // open the clicked one
        if (!isOpen) menu.classList.add("open");

        // rotate arrow icon
        document.querySelectorAll(".sidebar-link i.bi-chevron-down").forEach(i => i.style.transform = "rotate(0deg)");
        element.querySelector("i.bi-chevron-down").style.transform = isOpen ? "rotate(0deg)" : "rotate(180deg)";
    }