/*
 * Back-to-top button untuk guest frontpages.
 * Mematikan scroll-to-top tema asal dan menambah butang go-to-top tersuai.
 */
(function() {
    'use strict';

    // Matikan butang scroll-to-top asal tema supaya tidak bertindih
    if (typeof theme !== 'undefined' && typeof theme.PluginScrollToTop !== 'undefined') {
        theme.PluginScrollToTop.initialize = function() {
            return this;
        };
    }

    function buildButton() {
        var btn = document.createElement('button');
        btn.className = 'mv-backtotop';
        btn.setAttribute('type', 'button');
        btn.setAttribute('aria-label', 'Kembali ke atas');
        btn.setAttribute('title', 'Kembali ke atas');
        btn.innerHTML = '<i class="fas fa-arrow-up"></i>';

        btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        return btn;
    }

    function init() {
        // Buang butang lama jika ada
        var existing = document.querySelectorAll('.scroll-to-top, .mv-backtotop, #back-to-top, .go-top');
        existing.forEach(function(el) {
            el.remove();
        });

        // Tambah butang baharu
        var btn = buildButton();
        document.body.appendChild(btn);

        var toggle = function() {
            if (window.scrollY > 300) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
        };

        window.addEventListener('scroll', toggle, { passive: true });
        window.addEventListener('load', toggle);
        toggle();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
