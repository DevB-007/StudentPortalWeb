document.addEventListener('DOMContentLoaded', () => {
    const banner = document.getElementById('notification-banner');
    const closeBannerBtn = document.getElementById('close-banner');
    
    if (closeBannerBtn && banner) {
        closeBannerBtn.addEventListener('click', () => {
            banner.style.display = 'none';
        });
    }

    const hamburgerBtn = document.getElementById('hamburger-menu');
    const navMenu = document.querySelector('nav ul');
    
    if (hamburgerBtn && navMenu) {
        hamburgerBtn.addEventListener('click', () => {
            navMenu.classList.toggle('show');
        });
    }
    
    const themeBtn = document.createElement('button');
    themeBtn.textContent = 'Toggle Theme';
    themeBtn.className = 'btn';
    themeBtn.style.marginTop = '1rem';
    document.querySelector('nav').appendChild(themeBtn);

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }

    themeBtn.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });

    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;
            if (answer.style.display === 'block') {
                answer.style.display = 'none';
            } else {
                answer.style.display = 'block';
            }
        });
    });
});