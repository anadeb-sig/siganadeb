window.onscroll = function() {
    scrollFunction();
    };

    function scrollFunction() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    // Show the button when the user scrolls down 20px from the top of the document
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        scrollToTopBtn.style.display = 'block';
    } else {
        scrollToTopBtn.style.display = 'none';
    }
    }

    function scrollToTop() {
    // Scroll to the top of the document
    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE, and Opera
}