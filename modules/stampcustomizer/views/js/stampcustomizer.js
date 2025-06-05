$(document).ready(function() {
  $('#stamp-submit').on('click', function() {
    var text = $('#stamp-text').val();
    $('#stamp-preview').text(text);
  });
});
