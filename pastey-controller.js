/**
 * Controller for interacting with the PHP Slim REST services
 */

function addPaste() {

    var editor = ace.edit("editor");

    request = $.ajax({
        url: '/pastey/pastes',
        type: 'post',
        dataType: 'json',
        data: {
            "sourcecode": ace.edit("editor").getValue(),
            "language": ractive.get("paste.language"),
            "theme": ractive.get("paste.theme"),
            "title": ractive.get("paste.title"),
            "name": ractive.get("paste.name")
        }
    });

    request.done(function(data) {
        window.location.href = data;
    });

    request.fail(function(jqXHR, textStatus) {
        alert("Failed to add paste");  
    });
}

function loadPaste() {

    request = $.ajax({
        url: "/pastey/pastedetail/" + pastebinkey,
        type: "get"
    });

    request.done(function(msg) {
         console.log("Done");
         if (msg.hasOwnProperty('error')) {
            console.log(msg.error);                    
            $("#error").html("Error: " + msg.error);
         } else {

            ractive.set("paste", eval(msg));                            
            setupEditor(ace.edit("editor"));
         }             
    });

    request.fail(function(jqXHR, textStatus) {
         alert("Failed to load paste");
    });
}

function updatePaste() {

    var editor = ace.edit("editor");
    var theme = $("theme").val();
    var sourcecode = editor.getValue();

    $.ajax({
        url: '/pastey/pastes/' + pastebinkey,
        type: 'put',
        dataType: 'json',
        data: {
            "sourcecode": ace.edit("editor").getValue(),
            "theme": ractive.get("paste.theme")
        }
    });

    $("#updated").html("<b>Updated paste sucessfully</b>");
}

function setupPaste() {

    request = $.ajax({
        url: "/pastey/languages",
        type: "get"
    });

    request.done(function(msg) {

         console.log("Done loading languages");

         if (msg.hasOwnProperty('error')) {
            console.log(msg.error);                    
            $("#error").html("Error: " + msg.error);
         } else {

            ractive.set("languages", eval(msg));

            setupEditor(ace.edit("editor"));
         }

    });

    request.fail(function(jqXHR, textStatus) {
         console.log("Failed to get languages " + textStatus);
    });
}