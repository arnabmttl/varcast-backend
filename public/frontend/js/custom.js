// Menu fixed 
// Set position from top to add class
$(window).on('scroll', function () {
    if ($(this).scrollTop() > 85) { 
        $('#myHeader').addClass('sticky');
    }
    else {
        $('#myHeader').removeClass('sticky');
    }
});



// wow js mobile disable function
$(document).ready(function () {
    // new WOW().init();
    wow = new WOW(
        {
            boxClass: 'wow',      // default
            animateClass: 'animated', // default
            offset: 0,          // default
            mobile: false,       // default
            live: true        // default
        }
    )
    wow.init();

    $('#creator').owlCarousel({
      loop:true,
      margin:20,
      nav:true,
      navText: ["<img src='frontend/images/arowb.png'>","<img src='frontend/images/arowb.png'>"],
      responsive:{
          0:{
              items:1
          },
          600:{
              items:2
          },
          1000:{
              items:3
          }
      }
    })

    $('#filte').owlCarousel({
      loop:true,
      margin:20,
      nav:true,
      items:1,
      navText: ["<img src='frontend/images/arowb.png'>","<img src='frontend/images/arowb.png'>"],
    })

})

// loder js
// window.onload = function(){ 
//     document.querySelector('.loaderWrap').classList.add('loader-off'); 
// };


 // backto-top btn script
 var btn = $('#backto-top');
 $(window).scroll(function() {
   if ($(window).scrollTop() > 300) {
     btn.addClass('show');
   } else {
     btn.removeClass('show');
   }
 });

 btn.on('click', function(e) {
   e.preventDefault();
   $('html, body').animate({scrollTop:0}, '1000');
 });
// backto-top btn script end


