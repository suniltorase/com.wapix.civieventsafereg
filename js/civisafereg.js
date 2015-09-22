CRM.$(function($) {
  function getQueryStringValue( name ){
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]" + name + "=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null ) {
      return "";
    } else {
      return results[1];
    }
  }
  
  $('.event_register_link').click(function(e) {
    var self_sel = $('<input type="radio" name="self_radio" id="radio_f"/>Yourself<br>');
    var other_sel = $('<input type="radio" name="self_radio" id="radi_s"/>Someone Else<br>');
    var content = self_sel.add( other_sel );

    $("#dialog-confirm").html(content);
    $("#dialog-confirm").dialog({
      resizable: false,
      modal: true,
      title: "Who are you registering?",
      buttons: {
        "Save": function () {
          e.preventDefault();
          if ($('#radio_f').is(':checked')) {
            var dataUrl = "register?reset=1&id=" + getQueryStringValue('id');
          } else {
            var dataUrl = "register?cid=0&reset=1&id=" + getQueryStringValue('id');
          }
          window.location = dataUrl;
          $(this).dialog('close')
          $("#dialog-confirm").html("");
        },
        "Cancel": function () {
          $(this).dialog('close');
          $(this).dialog("destroy"); 
          $("#dialog-confirm").html("");
        }
      }
    });
    
    $('.ui-dialog-titlebar').addClass('popup-head');
    $('.ui-button').addClass('popup-btn');
    return false; 
  });
  
});
