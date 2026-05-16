        </div> <!-- /admin-content -->
    </main>

    <!-- Mobile Sidebar Toggle -->
    <button class="sidebar-toggle" id="sidebar-toggle" aria-label="تبديل القائمة">
        <i class="fas fa-bars"></i>
    </button>

    <script>
        var toggleBtn = document.getElementById('sidebar-toggle');
        var sidebar = document.getElementById('admin-sidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('open');
            });
        }
    </script>
</body>
</html>
