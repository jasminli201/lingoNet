// document ready event is fired when DOM has been loaded 
$(document).ready(function () {
  // do DOM manipulation, set header's height
  $('.header').height($(window).height() / 2.5);
})

$("#header").load("../layout/welcome_header.html");
$("#footer").load("../layout/footer.html");