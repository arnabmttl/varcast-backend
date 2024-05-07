$(document).ready(function () {
  $('.header-btn .usr>span').click(function(){
    $('.user-dropdown').slideToggle(300);
  })
  var readURL = function (input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('.profile-pic').attr('src', e.target.result);
      }

      reader.readAsDataURL(input.files[0]);
    }
  } 
  $(".file-upload").on('change', function () {
    readURL(this);
  });

  $(".upload-button").on('click', function () {
    $(".file-upload").click();
  });

  new WOW().init();

  var scrollTop = $(".go-top");
  $(window).scroll(function () {
    var topPos = $(this).scrollTop();
    if (topPos > 300) {
      $(scrollTop).css("opacity", "1");

    } else {
      $(scrollTop).css("opacity", "0");
    }
  });
  $(scrollTop).click(function () {
    $('html, body').animate({
      scrollTop: 0
    }, 800);
    return false;

  });
  // ====custom sticky header javascript start======
  document.querySelector('body').onscroll = function () { headerScroll() };
  let secFive = document.querySelector('.headerMain');
  let headerHeight = secFive.offsetHeight;

  let bodyPart = document.querySelector('body');
  function headerScroll() {
    if (window.scrollY >= secFive.offsetTop + headerHeight + 100) {
      bodyPart.classList.add('sticky');
      secFive.style.height = headerHeight + 'px';

    } else {
      bodyPart.classList.remove('sticky');
    }
  }

  $('.home-slider').owlCarousel({
    items: 1,
    loop: true,
    margin: 20,
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    autoplayTimeout: 4500,
    smartSpeed: 4000,
    nav: false,
    dots: true,
    navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
    ],
    autoplay: true,
    autoplayHoverPause: false,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 1
      },
      1000: {
        items: 1
      }
    }
  })

  $('.fslider').owlCarousel({
    items: 4,
    loop: false,
    margin: 30,
    animateOut: 'fadeOut',
    animateIn: 'fadeIn',
    autoplayTimeout: 3500,
    smartSpeed: 3000,
    nav: true,
    dots: false,
    navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
    ],
    autoplay: false,
    autoplayHoverPause: false,
    responsive: {
      0: {
        items: 1
      },
      480: {
        items: 2
      },
      992: {
        items: 3
      },
      1000: {
        items: 4
      }
    }
  })
  $('.tslider').owlCarousel({
    items: 1,
    loop: true,
    margin: 30,
    animateOut: 'fadeOut',
    animateIn: 'fadeInUp',
    autoplayTimeout: 3500,
    smartSpeed: 3000,
    nav: false,
    dots: true,
    navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
    ],
    autoplay: true,
    autoplayHoverPause: false,
    responsive: {
      0: {
        items: 1
      },
      600: {
        items: 1
      },
      992: {
        items: 1
      },
      1000: {
        items: 1
      }
    }
  })
   
})




document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
  const dropZoneElement = inputElement.closest(".drop-zone");

  dropZoneElement.addEventListener("click", (e) => {
    inputElement.click();
  });

  inputElement.addEventListener("change", (e) => {
    if (inputElement.files.length) {
      updateThumbnail(dropZoneElement, inputElement.files[0]);
    }
  });

  dropZoneElement.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZoneElement.classList.add("drop-zone--over");
  });

  ["dragleave", "dragend"].forEach((type) => {
    dropZoneElement.addEventListener(type, (e) => {
      dropZoneElement.classList.remove("drop-zone--over");
    });
  });

  dropZoneElement.addEventListener("drop", (e) => {
    e.preventDefault();

    if (e.dataTransfer.files.length) {
      inputElement.files = e.dataTransfer.files;
      updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
    }

    dropZoneElement.classList.remove("drop-zone--over");
  });
});

/**
 * Updates the thumbnail on a drop zone element.
 *
 * @param {HTMLElement} dropZoneElement
 * @param {File} file
 */
function updateThumbnail(dropZoneElement, file) {
  let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");

  // First time - remove the prompt
  if (dropZoneElement.querySelector(".drop-zone__prompt")) {
    dropZoneElement.querySelector(".drop-zone__prompt").remove();
  }

  // First time - there is no thumbnail element, so lets create it
  if (!thumbnailElement) {
    thumbnailElement = document.createElement("div");
    thumbnailElement.classList.add("drop-zone__thumb");
    dropZoneElement.appendChild(thumbnailElement);
  }

  thumbnailElement.dataset.label = file.name;

  // Show thumbnail for image files
  if (file.type.startsWith("image/")) {
    const reader = new FileReader();

    reader.readAsDataURL(file);
    reader.onload = () => {
      thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
    };
  } else {
    thumbnailElement.style.backgroundImage = null;
  }
}
