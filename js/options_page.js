!function($) {

  var $superpreGeSHi = $('#wp_hatena_notation_superpre_geshi').hide(),
      $superpreHTML = $('#wp_hatena_notation_superpre_html').hide();

  $('#wp_hatena_notation_superpre_method').on('change', function() {
    var value = $(this).find(':selected').val();
    $superpreGeSHi.toggle(value === 'geshi');
    $superpreHTML.toggle(value === 'html');
  }).trigger('change');


  var $perPost = $('#wp_hatena_notation_per_post'),
      $perPostDefaultWrap = $('#wp_hatena_notation_per_post_default_wrap');

  $perPostDefaultWrap.toggle($perPost.prop('checked'));
  $perPost.on('click', function click() {
    $perPostDefaultWrap.toggle(this.checked);
  });

}(jQuery);