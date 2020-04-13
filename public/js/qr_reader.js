class QrReader {

  /**
   *
   * @param element Modal Class/Id
   */
  constructor(element, url, doctype) {
    this.modalElement = element;
    this.url = url;
    this.doctype = doctype;
    this.previousQr = null;

    $(element).modal('show');
    self = this
    $(element).on('hide.bs.modal', function () {
      self.onHide()
    })
    //this.checkPermission()
  }

  checkPermission() {
    navigator.permissions.query({name: 'camera'})
      .then(permissionObj => {
        console.log(permissionObj)
      })
      .catch(error => {
        console.log('Got error :', error);
      })
  }


  initQr() {
      this.video = document.getElementById("video");
      let canvasElement = document.getElementById("canvas");
      let canvas = canvasElement.getContext("2d");

      self = this
      // Use facingMode: environment to attemt to get the front camera on phones
      navigator.mediaDevices.getUserMedia({video: {facingMode: 'environment'}})
        .then(function (stream) {
          self.video.srcObject = stream;
          self.globalStream = stream;
          self.video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
          self.video.play();
          tick();
        });

      /** Ticking */
      function tick() {;
        self.requestId = undefined;
        if (self.video.readyState == self.video.HAVE_ENOUGH_DATA) {
          if (screen.height  < video.videoHeight || screen.width < video.videoWidth) {
            canvasElement.height = video.videoHeight * 50/100;
            canvasElement.width = video.videoWidth * 50/100;
          } else {
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;
          }

          canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
          let imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
          let code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: "dontInvert",
          });

          if (code) {
            //$(self.modalElement).modal('hide');
            checkQr(code.data);
          }
        }
        start();
      }

      function checkQr(qr) {
        if(self.previousQr == qr) {
          return;
        }
        self.previousQr = qr;

        try {
          var json = JSON.parse(qr);
        }
        catch {
          alert("QR not valid.");
          return;
        }
        if(json.doctype != self.doctype) {
          alert("QR not valid.");
        }
        $.post(self.url, json, function (result) {
          if(result == 'success') {
            alert('Scan QR success')
            window.location.href = window.location.href
          } else {
            alert('There is an error in the scan process')
          }
        })
      }

      function start() {
        if (!self.requestId) {
          self.requestId = window.requestAnimationFrame(tick);
        }
      }
  };

  onHide(){
    cancelAnimationFrame(this.requestId);
    this.requestId = null;
    if(this.globalStream) {
      this.globalStream.getTracks().forEach(function(track) {
        track.stop();
      });
    }
    video.pause();
  }
}
