document.body.className+= ' demo-page';

window.addEventListener('load', function() {
	var s = 'script';
	var d = document;
	var w = window;
	var firstScript = d.getElementsByTagName(s)[0];


	(function() {
		var first_paragraph = document.getElementsByTagName('p')[0];
		if(first_paragraph) {
			first_paragraph.className = 'demo-intro';
		}

		setTimeout(function() {
			var headerA = d.getElementById('header-fx');
			if(headerA) headerA.className += ' complete';
		}, 2000);
	})();


	// BSA b!
	var bsa = d.createElement(s);
	bsa.async = 1;
	bsa.src = '/bsa.js';
  // console.log(bsa.src);
	firstScript.parentNode.insertBefore(bsa, firstScript);

	// Promo!
	(function() {

		var promoNode = d.getElementById('promoNode');

		// Temporary - use MooTools 2.0 when available
		function createElement(tagName, attr, parent) {
			var el = d.createElement(tagName);
			for(prop in attr) {
				if(attr.hasOwnProperty(prop)) el.setAttribute(prop, attr[prop]);
			}
			parent.appendChild(el);
			return el;
		}

		// Loads a script
		function loadScript(url) {
			var script = d.createElement('script');
			script.src = url;
			script.setAttribute('async', 'true');
			d.documentElement.firstChild.appendChild(script);
		}


	})();

  [].slice.call(document.querySelectorAll('header img[data-src]')).forEach(function(el) {
    el.src = el.getAttribute('data-src');
  });
});


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
        video.src = window.webkitURL.createObjectURL(stream);
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
      var dataURL = canvas.toDataURL();
			var answer = confirm ("confirm picture to proceed?")
			if (answer){
				var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.
      	window.location.href=image; // it will save locally
			}
    });
  }, false);
