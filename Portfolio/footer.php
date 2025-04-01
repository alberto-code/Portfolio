
</div>
</body>
<script src="/Portfolio/scripts/script.js"></script>
<script src="https://unpkg.com/jsbarcode@latest/dist/JsBarcode.all.min.js"></script>
<script src="/Portfolio/scripts/barras.js"></script>
<script src="/Portfolio/scripts/barras-entrada.js"></script>
<script src="/Portfolio/scripts/FPS.js"></script>
<script src="/Portfolio/scripts/delete-element.js"></script>
<script src="/Portfolio/scripts/add-elements.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2/dist/quagga.min.js"></script>
<script>
    function procesado(){
    const quaggaConf = {
        inputStream: {
            target: document.querySelector("#camera"),
            type: "LiveStream",
            constraints: {
                width: { min: 640 },
                height: { min: 480 },
                facingMode: "environment",
                aspectRatio: { min: 1, max: 2 }
            }
        },
        decoder: {
            readers: ['code_128_reader']
        },
    }

    Quagga.init(quaggaConf, function (err) {
        if (err) {
            return console.log(err);
        }
        Quagga.start();
    });

    Quagga.onDetected(function(result) {
    var code = result.codeResult.code;
    document.querySelector("#lectura").value = code;
    document.querySelector("#myFormDiv").style="display:visible"
    document.querySelector("#camera").style="display:none"
    beep1()
    setTimeout(() => {
        submitform()
    }, 1000);
    Quagga.stop(result); //Paro Quagga
    });
}

    // Quagga.onDetected(function (result) {
    //     // alert("Detected barcode: " + result.codeResult.code);
    //     $("#lectura").val(result.codeResult.code);
    //     $("#camera").hide(); //Oculto el div de la camara
    //     beep();
    //     Quagga.stop(result); //Paro Quagga
    // });
</script>
<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js">
    </script>  
</html>