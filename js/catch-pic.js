
//passes base64 image
function saveImage(image , user_id){

  jQuery.ajax({
       url: '../rollcall/save.php',
       type: 'POST',
       data: { pngUrl: image, id: user_id },
       complete: function(data, status)
       {
           if(status=='success')
           {
              alert('saved!');
           }
       }
   });
};

  // Put event listeners into place

  window.addEventListener("DOMContentLoaded", function() {
    // Grab elements, create settings, etc.
    var canvas = document.getElementById("canvas"),
      context = canvas.getContext("2d"),
      video = document.getElementById("video"),
      videoObj = { "video": true },
      errBack = function(error) {
        console.log("Video capture error: ", error.code);
      };

    // Put video listeners into place
    if(navigator.getUserMedia) { // Standard
      navigator.getUserMedia(videoObj, function(stream) {
        video.src = stream;
        video.play();
      }, errBack);
    } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
      navigator.webkitGetUserMedia(videoObj, function(stream){
        video.src = window.URL.createObjectURL(stream);
        video.play();
      }, errBack);
    } else if(navigator.mozGetUserMedia) { // WebKit-prefixed
      navigator.mozGetUserMedia(videoObj, function(stream){
        video.src = window.URL.createObjectURL(stream);
        video.play();
      }, errBack);
    }

    // Trigger photo take
    document.getElementById("snap").addEventListener("click", function() {
      context.drawImage(video, 0, 0, 320, 240);
			var image = document.getElementById("canvas");
	 		var pngUrl = canvas.toDataURL('image/png');
      var id = document.getElementById('user_id').value;
      if(IsNumeric(id)){
      saveImage(pngUrl, id);
    } else {
      alert("please provide id number before taking a picture");
    }
    });
  }, false);

  function IsNumeric(input){return (input - 0) == input && (''+input).trim().length > 0;}
