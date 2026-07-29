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

        btn.onclick = function() {
            var total = window.scrollY;
            if (total === 0) return;
            var steps = 30;
            var step = Math.ceil(total / steps);
            var timer = setInterval(function() {
                var cur = window.scrollY;
                if (cur <= 0) { clearInterval(timer); return; }
                window.scrollTo(0, Math.max(0, cur - step));
            }, 16);
        };

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

        var lastScrollY = window.scrollY;
        var ticking = false;

        var toggle = function() {
            if (window.scrollY > 300) {
                btn.classList.add('visible');
            } else {
                btn.classList.remove('visible');
            }
            ticking = false;
        };

        window.addEventListener('scroll', function() {
            lastScrollY = window.scrollY;
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    toggle();
                });
                ticking = true;
            }
        }, { passive: true });

        window.addEventListener('load', toggle);
        toggle();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
