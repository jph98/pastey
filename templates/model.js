 <script>

    var ractive = new Ractive({
        el: 'container',
        template: '#tmpl'
    });

    request = $.ajax({
        url: "/pastey/index.php/languages",
        type: "get"
    });

    request.done(function(msg) {
         console.log("Done");
         if (msg.hasOwnProperty('error')) {
            console.log(msg.error);                    
            $("#error").html("Error: " + msg.error);
         } else {
            console.log("Success: languages");
            
            console.log(eval(msg));

            // Set the msg object on the ractive.data object
            ractive.set("languages", eval(msg))
         }

    });

    request.fail(function(jqXHR, textStatus) {
         console.log("Fail ");
         console.log(JSON.stringify(jqXHR));
         console.log(textStatus);
    });

    ractive.on( 'addpaste', function ( event ) {

        // TODO: Submit the form
        alert( 'Adding paste!' );
        $("#pasteForm").submit();
    });
    
</script>