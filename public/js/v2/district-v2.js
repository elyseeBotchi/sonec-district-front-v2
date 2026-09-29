/**
 * District — V2 shell behaviour.
 * Sidebar (.left-sidebar) et menu avatar (.v2-user-menu) sont des
 * composants maison, sans dépendance à Bootstrap/jQuery : ouverture au
 * clic, fermeture au clic extérieur ou à Échap.
 */
(function () {
    "use strict";

    document.addEventListener("DOMContentLoaded", function () {
        var shell = document.getElementById("main-wrapper");
        if (!shell) {
            return;
        }

        function closeSidebar() {
            shell.classList.remove("show-sidebar");
        }

        // Clic sur le backdrop (::after de .v2-shell.show-sidebar) = clic hors sidebar.
        document.addEventListener("click", function (event) {
            if (!shell.classList.contains("show-sidebar")) {
                return;
            }
            var withinSidebar = event.target.closest(".left-sidebar");
            var isToggler = event.target.closest("[data-v2-toggle-sidebar]");
            if (!withinSidebar && !isToggler) {
                closeSidebar();
            }
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                closeSidebar();
            }
        });

        // Bouton "Menu" de la bottom nav mobile : réutilise le même toggle.
        document.querySelectorAll("[data-v2-toggle-sidebar]").forEach(function (btn) {
            btn.addEventListener("click", function (event) {
                event.preventDefault();
                shell.classList.toggle("show-sidebar");
            });
        });

        /**
         * Menu avatar (voir admins.partials.header) : composant maison,
         * indépendant du dropdown Bootstrap — ouverture/fermeture au clic,
         * fermeture au clic extérieur ou à Échap. Positionnement entièrement
         * géré par district-v2.css (.v2-user-menu), aucune dépendance à
         * Popper ni aux règles responsive de .navbar-nav.
         */
        var userMenuToggle = document.getElementById("v2-user-menu-toggle");
        var userMenu = document.getElementById("v2-user-menu");

        if (userMenuToggle && userMenu) {
            function closeUserMenu() {
                userMenu.classList.remove("is-open");
                userMenuToggle.setAttribute("aria-expanded", "false");
            }

            userMenuToggle.addEventListener("click", function (event) {
                event.preventDefault();
                event.stopPropagation();
                var isOpen = userMenu.classList.toggle("is-open");
                userMenuToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
            });

            document.addEventListener("click", function (event) {
                if (!userMenu.classList.contains("is-open")) {
                    return;
                }
                if (userMenu.contains(event.target) || userMenuToggle.contains(event.target)) {
                    return;
                }
                closeUserMenu();
            });

            document.addEventListener("keydown", function (event) {
                if (event.key === "Escape") {
                    closeUserMenu();
                }
            });
        }

        /**
         * Sous-menus du sidebar (Chèque, Activité du jour, STATISTIQUE
         * MOBILE, etc.). Remplace template/dist/js/sidebarmenu.js, qui
         * pilote la classe "in" (convention Bootstrap 3) alors que le
         * Bootstrap 4 chargé ici n'affiche que ".collapse.show" — un
         * conflit jamais résolu dans le template d'origine, cause du
         * bug "les menus déroulants ne fonctionnent pas". Ici, .in est
         * géré entièrement par nous (affichage défini dans
         * district-v2.css), sans dépendre de style.css.
         */
        var sidebarNav = document.getElementById("sidebarnav");

        if (sidebarNav) {
            // 1. Ouvre/ferme le sous-menu au clic sur son toggle.
            sidebarNav.addEventListener("click", function (event) {
                var toggle = event.target.closest(".sidebar-link.has-arrow");
                if (!toggle || !sidebarNav.contains(toggle)) {
                    return;
                }
                event.preventDefault();
                var submenu = toggle.nextElementSibling;
                if (!submenu || submenu.tagName !== "UL") {
                    return;
                }
                var isOpen = submenu.classList.toggle("in");
                toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
            });

            // 2. Au chargement, ouvre automatiquement le/les sous-menus qui
            //    contiennent la page actuelle et surligne le lien courant
            //    (reprend le comportement d'auto-détection de
            //    sidebarmenu.js, en le rendant fiable).
            var currentLink = null;
            sidebarNav.querySelectorAll("a[href]").forEach(function (a) {
                if (a.href === window.location.href) {
                    currentLink = a;
                }
            });

            if (currentLink) {
                currentLink.classList.add("active");
                var li = currentLink.closest("li");

                while (li) {
                    var parentUl = li.parentElement;
                    if (!parentUl || parentUl === sidebarNav) {
                        break;
                    }
                    if (parentUl.tagName === "UL") {
                        parentUl.classList.add("in");
                    }
                    var parentLi = parentUl.closest("li");
                    if (!parentLi) {
                        break;
                    }
                    var parentLink = parentLi.querySelector(":scope > a");
                    if (parentLink) {
                        parentLink.classList.add("active");
                        parentLink.setAttribute("aria-expanded", "true");
                    }
                    li = parentLi;
                }
            }
        }

        /**
         * Bouton afficher/masquer un mot de passe (icône œil), utilisable
         * sur n'importe quel champ : <button data-toggle-password> juste
         * après un <input type="password">. Socle réutilisable pour tous
         * les formulaires V2.
         */
        document.querySelectorAll("[data-toggle-password]").forEach(function (btn) {
            btn.addEventListener("click", function () {
                var field = btn.closest(".v2-form-field");
                var input = field ? field.querySelector("input") : null;
                if (!input) {
                    return;
                }
                input.type = input.type === "password" ? "text" : "password";
                btn.classList.toggle("is-visible", input.type === "text");
            });
        });

        /**
         * Barre de progression de navigation (#v2-page-progress, voir
         * layout.adminApp). Cette app recharge une page HTML complète à
         * chaque clic de menu (pas de SPA) : sans indicateur, l'écran
         * reste figé pendant tout l'appel serveur. On affiche la barre
         * dès le clic — avant même que le navigateur ne commence à
         * charger la page suivante — pour un retour immédiat.
         */
        var pageProgress = document.getElementById("v2-page-progress");

        if (pageProgress) {
            var startProgress = function () {
                pageProgress.classList.add("is-loading");
            };

            document.addEventListener("click", function (event) {
                if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
                    return;
                }
                var link = event.target.closest("a[href]");
                if (!link) {
                    return;
                }
                var href = link.getAttribute("href");
                if (!href || href.indexOf("javascript:") === 0 || href.indexOf("#") === 0) {
                    return;
                }
                if (link.target === "_blank" || link.hasAttribute("download")) {
                    return;
                }
                // Boutons/liens qui ne naviguent pas réellement (toggles UI).
                if (link.hasAttribute("data-v2-toggle-sidebar") || link.id === "v2-user-menu-toggle") {
                    return;
                }
                startProgress();
            });

            document.addEventListener("submit", function (event) {
                var form = event.target;
                // Les formulaires .sendForm restent sur place (AJAX, voir
                // app_script.js) : leur propre overlay suffit, pas besoin
                // de la barre de progression en plus.
                if (form.classList && form.classList.contains("sendForm")) {
                    return;
                }
                startProgress();
            });

            // Si la page revient depuis le cache navigateur (retour
            // arrière), on s'assure que la barre ne reste pas bloquée
            // affichée depuis la navigation précédente.
            window.addEventListener("pageshow", function () {
                pageProgress.classList.remove("is-loading");
            });
        }
    });
})();
