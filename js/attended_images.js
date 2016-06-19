window.onload=function() {
  document.getElementById("cause-dd").onchange=function() {

  }
}
$(document).ready(function(){
  var table = '<div id="added" >';
  table += "<p>Hello Worlddd</p></div>";
  $( "#cause-dd" ).change(function() {
    $(table).insertAfter( "#cause-dd" );
  });
});
