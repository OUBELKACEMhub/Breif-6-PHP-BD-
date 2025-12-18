document.addEventListener('DOMContentLoaded', function() {
    
    const blogToggle = document.getElementById('blog-toggle');
    const blogMenu = document.getElementById('blog-menu');
    const blogArrow = document.getElementById('blog-arrow');

  
    if (blogToggle && blogMenu && blogArrow) {

        if (document.querySelector('.active11')) {
            blogMenu.classList.remove('hidden');
            blogArrow.classList.add('rotate-180');
        }

        blogToggle.addEventListener('click', () => {
            blogMenu.classList.toggle('hidden');
            blogArrow.classList.toggle('rotate-180');
        });
    }
});

