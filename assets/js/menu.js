jQuery(function($) {
    $('.menu-toggle').on('click', function() {
        $(this).toggleClass('open');
        $('.main-nav').toggleClass('active');
        $('.mobile-menu-overlay').toggleClass('active');
    });

    $('.mobile-menu-overlay').on('click', function() {
        $(this).removeClass('active');
        $('.main-nav').removeClass('active');
        $('.menu-toggle').removeClass('open');
    });
});


document.addEventListener("scroll", function () {
  const header = document.querySelector(".site-header");
  if (window.scrollY > 10) {
    header.classList.add("scrolled");
  } else {
    header.classList.remove("scrolled");
  }
});