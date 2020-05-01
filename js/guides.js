jQuery(document).ready(function() {
  jQuery('body').scrollspy({ target: '.guidenav' });
});

jQuery(document).ready(function() {
  setTimeout(function() { 
    jQuery('body').each(function () {
      var $spy = jQuery(this).scrollspy('refresh')
      })
  }, 3000);
})