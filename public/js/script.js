document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.querySelector('.search-bar input');
    var searchBar = document.querySelector('.search-bar');

    searchInput.addEventListener('focus', function() {
        searchBar.style.boxShadow = '0 0 0 2px rgba(0, 45, 114, 0.2)';
        searchBar.style.background = '#ffffff';
    });

    searchInput.addEventListener('blur', function() {
        searchBar.style.boxShadow = 'none';
        searchBar.style.background = '#F8F9FA';
    });

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(entry) {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    var animatedElements = document.querySelectorAll('.category-card, .product-card, .feature-item');
    animatedElements.forEach(function(el) {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });
});
