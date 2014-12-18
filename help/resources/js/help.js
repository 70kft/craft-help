(function ($) {
  'use strict';

  $('#sidebar').on('click', '.toggle', function (e){
    $(this).parents('li').toggleClass('expanded');
  });

  $('#sidebar').find('a.sel').each(function () {
    $(this).parents('li').addClass('expanded');
  });

})(jQuery);

