class QrReader {
  element;
  requestId;
  video;
  globalStream;

  /**
   *
   * @param element Modal Class/Id
   */
  constructor(element) {
    this.element = element;
    $(element).modal('show');
    let self = this
    $(element).on('hide.bs.modal', function () {
      self.onHide()
    })
  }

  self = this;
  
  initQr = function() {
    return new Promise(resolve => {
      self.video = document.getElementById("video");
      let canvasElement = document.getElementById("canvas");
      let canvas = canvasElement.getContext("2d");

// Use facingMode: environment to attemt to get the front camera on phones
      navigator.mediaDevices.getUserMedia({video: {facingMode: 'environment'}}).then(function (stream) {
        self.video.srcObject = stream;
        self.globalStream = stream;
        self.video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
        self.video.play();
        tick();
      });

      /** Ticking */
      function tick() {
        self.requestId = undefined;
        if (self.video.readyState == self.video.HAVE_ENOUGH_DATA) {
          if (screen.height * 70 / 100 < self.video.videoHeight) {
            canvasElement.height = screen.height * 70 / 100;
          } else {
            canvasElement.height = self.video.videoHeight;
          }

          if (screen.width * 80 / 100 < self.video.videoWidth) {
            canvasElement.width = screen.width * 80 / 100;
          } else {
            canvasElement.width = self.video.videoWidth;
          }

          canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
          let imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
          let code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: "dontInvert",
          });

          if (code) {
            resolve(code.data);
            return;
          }
        }
        start();
      }

      function start() {
        if (!self.requestId) {
          self.requestId = window.requestAnimationFrame(tick);
        }
      }
    })
  };

  onHide = function () {
    cancelAnimationFrame(requestId);
    self.requestId = null;
    if(self.globalStream) {
      self.globalStream.getTracks().forEach(function(track) {
        track.stop();
      });
    }
    video.pause();
  }
}
