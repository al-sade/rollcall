
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

var x_image = 0; // for changing the x axis of the small pictuers
var y_image = 0; // for changing the y axis of the small pictuers
var pic_id = 0; //picture id

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

    document.getElementById("snap").addEventListener("click", function() {
       if(pic_id < 8) {
          if (pic_id == 4) {
            x_image = 0;
            y_image = 70;
          }
          context.drawImage(video, 0, 0, 320, 150); // draw the taken image - you want to create this draw 8 TIMES!
          var image = document.getElementById("canvas"); // get the canvas - you dont care about it
          var pngUrl = canvas.toDataURL('image/png'); // same
          var id = document.getElementById('user_id').value;// here you get user id from the form
          pic_id++;
        }
        
        if(IsNumeric(id)) { //check if id provided - if yes than save the picture
          saveImage(pngUrl, id, pic_id);// here we save the image - we want to call this function 8 TIMES
        } else { // if not than ask for id - We must get id for the file NAME
          alert("please provide id number before taking a picture");
        }
        context.clearRect(0, 0, 320, 240);
        context.drawImage(video, x_image, y_image, 70, 60);
        x_image = 78 + x_image;
    });
  }, false);

  function IsNumeric(input){return (input - 0) == input && (''+input).trim().length > 0;}
