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

document.querySelectorAll('.menu-item-has-children > a').forEach(link => {
    link.addEventListener('click', function(e) {
        const parent = this.parentElement;

        if(window.innerWidth < 768){ 
            if(!parent.classList.contains('open')) {
                e.preventDefault();
                parent.classList.add('open');
            }
        }
    });
});

