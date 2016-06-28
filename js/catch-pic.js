var picNum = 1;

//passes base64 image
function saveImage(image , user_id, pic_id){

  jQuery.ajax({
       url: '../save.php',
       type: 'POST',
       data: { pngUrl: image, id: user_id, pic_id: pic_id },
       complete: function(data, status)
       {
           if(status=='success')
           {
            if(picNum < 9){
              if(picNum > 0 && picNum < 8){
              jQuery('#takePicStat').text("Processing...");
              jQuery('#picNum').text(picNum);
              picNum++;
            }else if (picNum ==8) {
              jQuery('#picNum').text(picNum);
              jQuery('#takePicStat').text("Complete :)");
            }
           }
       }
   }
});
}

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

  var pic_id = 0; //picture id

  document.getElementById("snap").addEventListener("click", function() {
        var id = document.getElementById('user_id').value;// get user id

        if(IsNumeric(id)) {

          if(pic_id <= 8) {

            context.drawImage(video, 0, 0, 640, 480);

            var image = document.getElementById("canvas"); // get the canvas
            var pngUrl = canvas.toDataURL('image/png');

            saveImage(pngUrl, id, pic_id);
            pic_id++;

          } else if (pic_id ==8){
            document.getElementById('form-submit').style.pointerEvents = "all";
          }
      }else {
        alert("please fill up the form before taking pictures");
      }

  });
}, false);

function IsNumeric(input){return (input - 0) == input && (''+input).trim().length > 0;}
