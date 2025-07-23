$(document).ready(function() {
  function updatePreview() {
    var lines = [];
    $('.stamp-line').each(function() {
      var val = $(this).val();
      if (val.trim() !== '') {
        lines.push(val);
      }
    });
    $('#stamp-preview').html(lines.join('<br/>'));
    var price = lines.length * parseFloat(stamp_price_per_line);
    $('#stamp-price').text(price.toFixed(2));
  }

  $('.stamp-line').on('input', updatePreview);
  $('#stamp-submit').on('click', updatePreview);
});
