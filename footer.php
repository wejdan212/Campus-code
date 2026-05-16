</div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-social">
                <a href="#" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
                <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="footer-divider"></div>
            <p><i class="fas fa-laptop-code"></i> &copy; 2026 جميع الحقوق محفوظة | مشروع مادة Web Systems</p>
        </div>
    </footer>

    <script>
        // Dark Mode Toggle
        (function() {
            var btn = document.getElementById('theme-toggle-btn');
            if (!btn) return;

            btn.addEventListener('click', function() {
                var html = document.documentElement;
                var current = html.getAttribute('data-theme');
                var next = (current === 'light') ? 'dark' : 'light';

                html.setAttribute('data-theme', next);
                localStorage.setItem('campus-theme', next);
            });
        })();
    </script>

</body>
</html>