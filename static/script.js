// Extracted JS from index.php - moved to static/ to simulate cloud storage
document.addEventListener('DOMContentLoaded', function() {
    // Hide loader after page load
    setTimeout(function() {
        var loader = document.getElementById('loader');
        if (loader) loader.classList.add('hidden');
    }, 1500);

    // Mobile menu toggle
    var hamburger = document.getElementById('hamburger');
    var navMenu = document.getElementById('navMenu');
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            hamburger.innerHTML = navMenu.classList.contains('active') ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        });

        // Close mobile menu when clicking on a link
        var navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                hamburger.innerHTML = '<i class="fas fa-bars"></i>';
            });
        });
    }

    // Sticky navigation
    var navbar = document.getElementById('navbar');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            if (navbar) navbar.classList.add('scrolled');
        } else {
            if (navbar) navbar.classList.remove('scrolled');
        }
    });

    // Back to top button
    var backToTop = document.getElementById('backToTop');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            if (backToTop) backToTop.classList.add('visible');
        } else {
            if (backToTop) backToTop.classList.remove('visible');
        }
    });

    if (backToTop) {
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Testimonial carousel
    var slides = document.querySelectorAll('.testimonial-slide');
    var dots = document.querySelectorAll('.carousel-dot');
    var currentSlide = 0;

    function showSlide(n) {
        slides.forEach(function(slide) { slide.classList.remove('active'); });
        dots.forEach(function(dot) { dot.classList.remove('active'); });
        currentSlide = (n + slides.length) % slides.length;
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }

    dots.forEach(function(dot, index) {
        dot.addEventListener('click', function() { showSlide(index); });
    });

    setInterval(function() { showSlide(currentSlide + 1); }, 5000);

    // Animated statistics
    var statItems = document.querySelectorAll('.stat-item');
    var statsAnimated = false;

    function animateStats() {
        if (statsAnimated) return;
        var statsSection = document.querySelector('.statistics');
        if (!statsSection) return;
        var sectionTop = statsSection.offsetTop;
        var sectionHeight = statsSection.offsetHeight;
        var scrollPosition = window.scrollY + window.innerHeight;
        if (scrollPosition > sectionTop + sectionHeight / 2) {
            statItems.forEach(function(item) {
                var numberElement = item.querySelector('.stat-number');
                var target = parseInt(numberElement.getAttribute('data-count'));
                var current = 0;
                var increment = target / 100;
                var timer = setInterval(function() {
                    current += increment;
                    if (current >= target) { current = target; clearInterval(timer); }
                    numberElement.textContent = Math.floor(current);
                }, 20);
            });
            statsAnimated = true;
        }
    }

    // Scroll animations for sections
    function checkScroll() {
        var sections = document.querySelectorAll('.section-title, .section-subtitle, .about-text, .about-image, .service-card, .stat-item, .form-container');
        sections.forEach(function(section) {
            var sectionTop = section.getBoundingClientRect().top;
            var windowHeight = window.innerHeight;
            if (sectionTop < windowHeight - 100) {
                section.classList.add('visible');
            }
        });
        animateStats();
    }

    window.addEventListener('scroll', checkScroll);
    checkScroll();

    // Form validation enhancement
    var form = document.querySelector('form');
    if (form) {
        var inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(function(input) {
            input.addEventListener('blur', function() {
                if (this.value.trim() !== '') this.classList.add('filled'); else this.classList.remove('filled');
            });
        });
    }

    // Parallax effect for header
    window.addEventListener('scroll', function() {
        var scrolled = window.pageYOffset;
        var parallaxElements = document.querySelectorAll('header.parallax');
        parallaxElements.forEach(function(element) {
            var speed = 0.5;
            element.style.transform = 'translateY(' + (scrolled * speed) + 'px)';
        });
    });
});

// End of extracted JS
