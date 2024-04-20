async function handlerAudio(idBoton, file_id) {
  const recordButton = document.getElementById(idBoton);
  const recordingTimeDisplay = document.getElementById("recordingTime");
  let mediaRecorder;
  let audioChunks = [];
  let startTime;
  let timerInterval;
  async function startRecording() {
    $("#" + idBoton).addClass('pushed')
    try {
      const stream = await navigator.mediaDevices.getUserMedia({
        audio: true,
      });
      mediaRecorder = new MediaRecorder(stream, {
        mimeType: "audio/webm",
      });

      mediaRecorder.ondataavailable = (event) => {
        if (event.data.size > 0) {
          audioChunks.push(event.data);
        }
      };

      mediaRecorder.onstop = () => {
        $("#" + idBoton).removeClass('pushed')
        $.toast({
          heading: '<b>Audio Grabado</b>',
          icon: 'success',
          position: 'top-right',
          hideAfter: 1300
        });
        clearInterval(timerInterval);
        const audioBlob = new Blob(audioChunks, {
          type: "audio/webm",
        });

        recordingTimeDisplay.textContent = "Tiempo de grabación: 00:00";
        $(`#${idBoton}`).removeClass("text-danger", "fa-microphone-slash");
        $(`#${idBoton}`).addClass('fa-microphone')

        // Envía el audio grabado al servidor
        audio = audioBlob;
        $(`#${file_id}`).val('audio');
        buttonsDisabled();
      };

      mediaRecorder.start();
      startTime = Date.now();
      timerInterval = setInterval(updateRecordingTime, 1000);
      $(`#${idBoton}`).removeClass('fa-microphone')
      // recordButton.classList.remove("fa-microphone");
      $(`#${idBoton}`).addClass('fa-microphone-slash')
      $(`#${idBoton}`).addClass('text-danger')
      // recordButton.classList.add("text-danger", "fa-microphone-slash");
      recordButton.classList.add("recording"); // Agregar la clase "recording"
    } catch (error) {
      $.toast({
        heading: '<b>Error al acceder al micrófono</b>',
        icon: 'error',
        position: 'top-center',
        hideAfter: 1300,
      })
      console.warn("Error al acceder al micrófono:", error);
    }
  }

  function stopRecording() {
    if (mediaRecorder && mediaRecorder.state === "recording") {
      clearInterval(timerInterval);
      mediaRecorder.stop();
      recordButton.classList.remove("recording");
    }
  }

  function updateRecordingTime() {
    const currentTimeMillis = Date.now() - startTime;
    const minutes = Math.floor(currentTimeMillis / 60000);
    const seconds = Math.floor((currentTimeMillis % 60000) / 1000);
    const formattedTime = `${minutes.toString().padStart(2, "0")}:${seconds
      .toString()
      .padStart(2, "0")}`;
    recordingTimeDisplay.textContent = `Tiempo de grabación: ${formattedTime}`;
  }

  $(document).on("click", "#" + idBoton, () => {
    console.log('Se hizo clic en el boton de microfono')
    if (mediaRecorder && mediaRecorder.state === "recording") {
      stopRecording();
    } else {
      startRecording();
    }
  });
}

function handlerImage(idContImg, idFileInput, file_id) {
  $(`#${idFileInput}`).change(function () {
    var reader = new FileReader();
    reader.onload = function (event) {
      var blob = event.target.result;
      var img = $(`#${idContImg}`);
      img.attr("src", blob);
      $(`#${file_id}`).val('imagen');
      imagen = blob
      buttonsDisabled()
    };
    reader.readAsDataURL(this.files[0]);
  });
}

function buttonsDisabled() {
  $('.type-comp').each((_, e) => {
    $(e).attr('disabled', 'true')
  })
}