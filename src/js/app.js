function initializeFormEvents(){
  var form         = $('#contact-form');
  var formContainer = $('#form-container');

  $("input[name='addPrice']").click(function (evt) {
    evt.preventDefault();

    var button = $(evt.target);                 
    var formData = button.parents('form').serialize() 
        + '&' 
        + encodeURI(button.attr('name'))
        + '='
        + encodeURI(button.attr('value'))
    ;
    $.ajax({
      type: 'POST',
      url: 'mail.php',
      data: formData
    }).done( function(response) {
      $(formContainer).empty().html(response);
    }).fail( function(data) {
      alert('There was an error in processing the request.');
    });
  });

  $(form).submit( function(e) {
    e.preventDefault();
    var formData = $(form).serialize() + '&submitForm=submitForm';
    
    $.ajax({
      type: 'POST',
      url: 'mail.php',
      data: formData
    }).done( function(response) {
      $(formContainer).empty().html(response);
      initializeFormEvents();
    }).fail( function(data) {
      alert('There was an error in processing the request.');
    });
  });

  $("button[name='removePrice']").click(function (evt) {
    evt.preventDefault();

    var button = $(evt.target);                 
    var formData = button.parents('form').serialize() 
        + '&' 
        + encodeURI(button.attr('name'))
        + '='
        + encodeURI(button.attr('value'))
    ;

    $.ajax({
      type: 'POST',
      url: 'mail.php',
      data: formData
    }).done( function(response) {
      $(formContainer).empty().html(response);
    }).fail( function(data) {
      alert('There was an error in processing the request.');
    });
  }); 
} 
initializeFormEvents();