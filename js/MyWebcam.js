			// MyWebcam.js - start // 
			var localStream;
			var counter;
			
			// user has clicked on button "take photo"
			function showPhotoContainer() {
				console.log("show photo container");
				// show video/webcam container
				var videoContainer = document.getElementById('videoContainer');
				videoContainer.setAttribute('class', 'visible');
				// update video element
				var videoElement = document.getElementById('videoElement');
				navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;
				if (navigator.getUserMedia) {      
					navigator.getUserMedia({video: true}, handleVideo, videoError);
				}
				// videoElement.play();
			}
			
			// ensure that the videoElement is showing the stream from the webcam
			function handleVideo(stream) {
				console.log("handleVideo!");
				var videoElement = document.getElementById('videoElement');
				localStream = stream;
				videoElement.src = window.URL.createObjectURL(stream);
			}
			
			// if the user does not allow access to webcam we get here
			function videoError(e) {
				// do something
				console.log("An error occured! " + err);
			}
			
			function myPlay() {
				console.log("myPlay!");
				// delay taking a photo so that webcam can be initialized
				//window.setTimeout("takePicture()", 3000);
				counter = 8000;
				countDown();
			}
			
			function countDown() {
				console.log("countdown with counter=" + counter);
				var counterP = document.getElementById('counterP');
				var delay = counter / 1000;
				console.log("Take photo in..." + delay + ' s');
				counterP.innerHTML = 'Take photo in...' + delay + ' s';
				if (delay > 0) {
					counter = counter - 1000;
					window.setTimeout(function(){countDown()}, 1000);
				}
				else {
					takePicture();
				}
			}
			
			// get image from webcam, copy it to canvas and show it in image
			function takePicture() {
				var width = 250;
				var height = 250;
				var canvasElement = document.getElementById('canvasElement');
				var photoElement = document.getElementById('photoElement');
				var videoElement = document.getElementById('videoElement');
				console.log("takePicture!");
				canvasElement.width = width;
				canvasElement.height = height;
				canvasElement.getContext('2d').drawImage(videoElement, 0, 0, width, height);
				var data = canvasElement.toDataURL('image/png');
				photoElement.setAttribute('value', data);
				// stop playing
				localStream.stop();
				var videoContainer = document.getElementById('videoContainer');
				videoContainer.setAttribute('class', 'hidden');
				canvasElement.setAttribute('class', 'visible');
				videoElement.setAttribute('src', '');
				// show "delete photo" button and hide "take a photo" button
				setButtons(false);
				counterP.innerHTML = '&nbsp;';
			}
			
			// show the "take a photo" and hide the "delete photo" button
			// or vice versa
			function setButtons(value) {
				var val1 = value ? "visible" : "hidden";
				var newButton = document.getElementById('startbutton');
				newButton.setAttribute('class', val1);
				var val2 = value ? "hidden" : "visible";
				var delButton = document.getElementById('removebutton');
				delButton.setAttribute('class', val2);
			}
			
			function removePhoto() {
				console.log("removePhoto!");
				var canvasElement = document.getElementById('canvasElement');
				var photoElement = document.getElementById('photoElement');
				canvasElement.getContext('2d').clearRect(0, 0, 
					canvasElement.width, 
					canvasElement.height);
				canvasElement.setAttribute('class', 'hidden');
				canvasElement.width = 0;
				canvasElement.height = 0;
				photoElement.setAttribute('value', '');
				setButtons(true);
			}
			// MyWebcam.js - end // 
