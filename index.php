<?php
// الاتصال بقاعدة البيانات لسحب البيانات الحقيقية
include 'connection.php';

// سحب عدد الدورات
$courses_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM courses");
$courses_count = mysqli_fetch_assoc($courses_count_query)['total'];

// سحب عدد الطلاب
$users_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
$users_count = mysqli_fetch_assoc($users_count_query)['total'];

// سحب عدد المسارات
$tracks_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM tracks");
$tracks_count = mysqli_fetch_assoc($tracks_count_query)['total'];

// سحب المسارات مع عدد الدورات لكل مسار
$tracks_query = mysqli_query($conn, "SELECT t.*, COUNT(c.id) as course_count FROM tracks t LEFT JOIN courses c ON t.id = c.track_id GROUP BY t.id");
?>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Code | منصة التعليم التقني الرائدة</title>
    <meta name="description" content="Campus Code - منصة تعليمية متخصصة في البرمجة والتقنية للطلاب الجامعيين. انضم الآن وابدأ رحلة التعلم!">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="landing.css">
    <script>
        (function() {
            var saved = localStorage.getItem('campus-theme');
            if (saved) document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
</head>
<body class="landing-body">

    <!-- Navbar -->
    <nav class="landing-navbar" id="landing-navbar">
        <div class="landing-navbar-inner">
            <div class="navbar-brand">
                <h2><i class="fas fa-graduation-cap"></i> Campus Code</h2>
            </div>
            <div class="landing-nav-links">
                <a href="#hero" class="landing-nav-link"><i class="fas fa-home"></i> الرئيسية</a>
                <a href="#about" class="landing-nav-link"><i class="fas fa-circle-info"></i> عن المنصة</a>
                <a href="#courses" class="landing-nav-link"><i class="fas fa-book"></i> الدورات</a>
                <a href="#register-section" class="landing-nav-link"><i class="fas fa-user-plus"></i> التسجيل</a>
                <button id="theme-toggle-btn" class="theme-toggle" title="تبديل الوضع" aria-label="تبديل المظهر">
                    <i class="fas fa-sun icon-sun"></i>
                    <i class="fas fa-moon icon-moon"></i>
                </button>
                <a href="login.php" class="btn btn-primary btn-sm landing-nav-cta" id="nav-login-btn">
                    <i class="fas fa-right-to-bracket"></i> دخول
                </a>
            </div>
            <button class="landing-menu-toggle" id="landing-menu-toggle" aria-label="فتح القائمة">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="hero-bg">
            <img src="uploads/hero_bg.png" alt="خلفية" id="hero-bg-img">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-particles" id="hero-particles"></div>
        <div class="hero-content">
            <div class="hero-badge">
                <i class="fas fa-rocket"></i> منصة تعليمية متطورة
            </div>
            <h1 class="hero-title">
                طوّر مهاراتك مع
                <span class="hero-gradient-text">Campus Code</span>
            </h1>
            <p class="hero-subtitle">
                منصة تعليمية متخصصة في البرمجة والتقنية مصممة للطلاب الجامعيين. 
                اكتشف دورات احترافية، تعلّم بأسلوب عصري، وابنِ مستقبلك التقني.
            </p>
            <div class="hero-buttons">
                <a href="login.php" class="btn btn-primary btn-lg hero-btn-primary" id="hero-start-btn">
                    <i class="fas fa-play"></i> ابدأ الآن
                </a>
                <a href="#about" class="btn btn-outline btn-lg hero-btn-secondary" id="hero-learn-btn">
                    <i class="fas fa-arrow-down"></i> اكتشف المزيد
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <span class="hero-stat-number" data-target="<?php echo $courses_count; ?>">0</span>+
                    <span class="hero-stat-label">دورة تعليمية</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <span class="hero-stat-number" data-target="<?php echo $users_count; ?>">0</span>+
                    <span class="hero-stat-label">طالب مسجل</span>
                </div>
                <div class="hero-stat-divider"></div>
                <div class="hero-stat">
                    <span class="hero-stat-number" data-target="<?php echo $tracks_count; ?>">0</span>+
                    <span class="hero-stat-label">مسار تعليمي</span>
                </div>
            </div>
        </div>
        <div class="hero-scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- About Section -->
    <section class="landing-section" id="about">
        <div class="landing-container">
            <div class="section-header">
                <span class="section-badge"><i class="fas fa-star"></i> لماذا نحن؟</span>
                <h2 class="section-title">منصة تعليمية <span>بمعايير عالية</span></h2>
                <p class="section-subtitle">نقدم لك أفضل تجربة تعليمية مع محتوى محدّث وأدوات تفاعلية</p>
            </div>
            <div class="features-grid">
                <div class="feature-card" id="feature-1">
                    <div class="feature-icon">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h3>محتوى تقني متقدم</h3>
                    <p>دورات في البرمجة، الذكاء الاصطناعي، تطوير الويب، وأكثر. محتوى مُعد بواسطة متخصصين محترفين.</p>
                </div>
                <div class="feature-card" id="feature-2">
                    <div class="feature-icon icon-secondary">
                        <i class="fas fa-route"></i>
                    </div>
                    <h3>مسارات تعليمية منظمة</h3>
                    <p>اختر المسار الذي يناسب طموحاتك وتدرّج من المبتدئ إلى المحترف بخطوات واضحة.</p>
                </div>
                <div class="feature-card" id="feature-3">
                    <div class="feature-icon icon-warning">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <h3>شهادات معتمدة</h3>
                    <p>احصل على شهادة إتمام لكل دورة تنهيها. عزّز سيرتك الذاتية وأظهر مهاراتك.</p>
                </div>
                <div class="feature-card" id="feature-4">
                    <div class="feature-icon icon-danger">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>مجتمع تعليمي تفاعلي</h3>
                    <p>تواصل مع زملائك والمدربين. اسأل، ناقش، وتعلّم بأسلوب تعاوني.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Courses Preview Section -->
    <section class="landing-section section-alt" id="courses">
        <div class="landing-container">
            <div class="section-header">
                <span class="section-badge"><i class="fas fa-book-open"></i> استكشف</span>
                <h2 class="section-title">المسارات <span>التعليمية</span></h2>
                <p class="section-subtitle">اختر المسار المناسب لك وابدأ رحلة التعلم الآن</p>
            </div>
            <div class="courses-preview-grid">
                <?php 
                // أيقونات وألوان مختلفة لكل مسار
                $icons = ['fas fa-code', 'fas fa-mobile-screen', 'fas fa-brain', 'fas fa-shield-halved', 'fas fa-database', 'fas fa-cloud'];
                $icon_classes = ['', 'icon-secondary', 'icon-warning', 'icon-danger', '', 'icon-secondary'];
                $i = 0;
                
                if (mysqli_num_rows($tracks_query) > 0) {
                    while ($track = mysqli_fetch_assoc($tracks_query)) {
                        $icon = $icons[$i % count($icons)];
                        $icon_class = $icon_classes[$i % count($icon_classes)];
                ?>
                <div class="course-preview-card" id="preview-course-<?php echo $i + 1; ?>">
                    <div class="course-preview-icon <?php echo $icon_class; ?>">
                        <i class="<?php echo $icon; ?>"></i>
                    </div>
                    <h3><?php echo htmlspecialchars($track['name']); ?></h3>
                    <p><?php echo htmlspecialchars($track['description']); ?></p>
                    <div class="course-preview-meta">
                        <span><i class="fas fa-book"></i> <?php echo $track['course_count']; ?> دورة</span>
                        <span><i class="fas fa-signal"></i> جميع المستويات</span>
                    </div>
                </div>
                <?php 
                        $i++;
                    }
                } else {
                    // في حال ما فيه مسارات
                ?>
                <div class="course-preview-card">
                    <div class="course-preview-icon">
                        <i class="fas fa-code"></i>
                    </div>
                    <h3>تطوير الويب</h3>
                    <p>تعلّم HTML, CSS, JavaScript, PHP وأطر العمل الحديثة لبناء مواقع احترافية.</p>
                    <div class="course-preview-meta">
                        <span><i class="fas fa-clock"></i> +20 ساعة</span>
                        <span><i class="fas fa-signal"></i> جميع المستويات</span>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="courses-preview-cta">
                <a href="login.php" class="btn btn-primary btn-lg" id="courses-explore-btn">
                    <i class="fas fa-arrow-left"></i> استعرض جميع الدورات
                </a>
            </div>
        </div>
    </section>

    <!-- Register CTA Section -->
    <section class="landing-section register-cta-section" id="register-section">
        <div class="landing-container">
            <div class="register-cta-wrapper">
                <div class="register-cta-content">
                    <span class="section-badge"><i class="fas fa-user-plus"></i> انضم الآن</span>
                    <h2>جاهز تبدأ رحلتك التعليمية؟</h2>
                    <p>سجّل حسابك الجامعي مجاناً وابدأ التعلّم الآن. كل اللي تحتاجه إيميلك الجامعي!</p>
                    <ul class="register-benefits">
                        <li><i class="fas fa-check-circle"></i> تسجيل مجاني بالكامل</li>
                        <li><i class="fas fa-check-circle"></i> وصول فوري لجميع المسارات</li>
                        <li><i class="fas fa-check-circle"></i> محتوى حصري للطلاب الجامعيين</li>
                        <li><i class="fas fa-check-circle"></i> دعم فني على مدار الساعة</li>
                    </ul>
                    <div class="register-cta-buttons">
                        <a href="register.php" class="btn btn-primary btn-lg" id="register-cta-btn">
                            <i class="fas fa-user-plus"></i> إنشاء حساب جديد
                        </a>
                        <a href="login.php" class="btn btn-outline btn-lg" id="login-cta-btn">
                            <i class="fas fa-right-to-bracket"></i> عندي حساب
                        </a>
                    </div>
                </div>
                <div class="register-cta-visual">
                    <div class="floating-cards">
                        <div class="floating-card fc-1">
                            <i class="fas fa-graduation-cap"></i>
                            <span>+500 طالب</span>
                        </div>
                        <div class="floating-card fc-2">
                            <i class="fas fa-star"></i>
                            <span>تقييم 4.9</span>
                        </div>
                        <div class="floating-card fc-3">
                            <i class="fas fa-trophy"></i>
                            <span>شهادات معتمدة</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="landing-container">
            <div class="landing-footer-grid">
                <div class="landing-footer-brand">
                    <h3><i class="fas fa-graduation-cap"></i> Campus Code</h3>
                    <p>منصة تعليمية متخصصة في البرمجة والتقنية مصممة للطلاب الجامعيين.</p>
                    <div class="footer-social">
                        <a href="#" aria-label="Twitter"><i class="fab fa-x-twitter"></i></a>
                        <a href="#" aria-label="GitHub"><i class="fab fa-github"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="landing-footer-links">
                    <h4>روابط سريعة</h4>
                    <a href="#about">عن المنصة</a>
                    <a href="#courses">الدورات</a>
                    <a href="register.php">تسجيل حساب</a>
                    <a href="login.php">تسجيل الدخول</a>
                </div>
                <div class="landing-footer-links">
                    <h4>تواصل معنا</h4>
                    <a href="#"><i class="fas fa-envelope"></i> support@campuscode.sa</a>
                    <a href="#"><i class="fas fa-phone"></i> 966+ XXXXXXXXX</a>
                    <a href="#"><i class="fas fa-location-dot"></i> المملكة العربية السعودية</a>
                </div>
            </div>
            <div class="landing-footer-bottom">
                <div class="footer-divider"></div>
                <p><i class="fas fa-laptop-code"></i> &copy; 2026 جميع الحقوق محفوظة | مشروع مادة Web Systems</p>
            </div>
        </div>
    </footer>

    <script>
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                // close mobile menu
                document.querySelector('.landing-nav-links').classList.remove('active');
            });
        });

        // Mobile menu toggle
        var menuBtn = document.getElementById('landing-menu-toggle');
        if (menuBtn) {
            menuBtn.addEventListener('click', function() {
                document.querySelector('.landing-nav-links').classList.toggle('active');
            });
        }

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('landing-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Counter animation
        function animateCounters() {
            var counters = document.querySelectorAll('.hero-stat-number');
            counters.forEach(function(counter) {
                var target = parseInt(counter.getAttribute('data-target'));
                var duration = 2000;
                var step = target / (duration / 16);
                var current = 0;
                var timer = setInterval(function() {
                    current += step;
                    if (current >= target) {
                        counter.textContent = target;
                        clearInterval(timer);
                    } else {
                        counter.textContent = Math.floor(current);
                    }
                }, 16);
            });
        }

        // Intersection Observer for animations
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                    if (entry.target.classList.contains('hero-stats')) {
                        animateCounters();
                    }
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.feature-card, .course-preview-card, .register-cta-wrapper, .hero-stats, .section-header').forEach(function(el) {
            observer.observe(el);
        });

        // Create floating particles in hero
        (function createParticles() {
            var container = document.getElementById('hero-particles');
            if (!container) return;
            for (var i = 0; i < 30; i++) {
                var particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.width = (Math.random() * 4 + 1) + 'px';
                particle.style.height = particle.style.width;
                particle.style.animationDelay = (Math.random() * 6) + 's';
                particle.style.animationDuration = (Math.random() * 4 + 3) + 's';
                container.appendChild(particle);
            }
        })();

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
