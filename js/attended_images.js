$(document).ready(function(){

  var table = '<div id="added" >';
  for (var i = 0; i < 10; i++) {
    table += '<div id="' + i + '" class="appeal-img" onclick="markPick(this)" style="background:url(https://s-media-cache-ak0.pinimg.com/avatars/al3069_1399274732_140.jpg);"></div>'
  }
  $( "#cause-dd" ).change(function() {
    if($(this).find('option:selected').val() == 1){
      if(!$(this).find("#added").length){
        console.log("ll");
        table += "</div><br>";
        $(table).insertAfter( "#cause-dd" );

      }

    }
  });
});

  // Download a file form a url.
  function markPick(url) {
    url = "https://s-media-cache-ak0.pinimg.com/avatars/al3069_1399274732_140.jpg";
    jQuery.ajax({
         url: '../save-attended-img.php',
         type: 'POST',
         data: { URL: url },
         complete: function(data, status)
         {
             if(status=='success')
             {
                console.log(url);
             }
         }
     });
  }
