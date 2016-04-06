
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

    var x_image = 0; // for changing the x axis of the small pictuers
    var y_image = 0; // for changing the y axis of the small pictuers
    var pic_id = 0; //picture id

    document.getElementById("snap").addEventListener("click", function() {
          var id = document.getElementById('user_id').value;// get user id

          if(IsNumeric(id)) {
          if(pic_id < 8) {
          if (pic_id == 4) {
            x_image = 0;
            y_image = 70;
          }
          context.drawImage(video, 0, 0, 320, 150);

          var image = document.getElementById("canvas"); // get the canvas
          var pngUrl = canvas.toDataURL('image/png');

          pic_id++;

            saveImage(pngUrl, id, pic_id);
            console.log("kairos call");

          } else if (pic_id ==8){
            document.getElementById('form-submit').style.pointerEvents = "all";
          }
        }else {
          console.log("please fill up the form before taking pictures");
        }
        context.clearRect(0, 0, 320, 240);
        context.drawImage(video, x_image, y_image, 70, 60);
        x_image = 78 + x_image;
    });
  }, false);

  function IsNumeric(input){return (input - 0) == input && (''+input).trim().length > 0;}
