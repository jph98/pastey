/**
 * Event handling for pastey
 */   

/** Update events */
ractive.on('updateLanguage', function (event) {
    var editor = ace.edit("editor");
    var language = event.node.value;
    editor.getSession().setMode("ace/mode/" + language);
    ractive.set("paste.language", language);
});

ractive.on('updateName', function (event) {
    ractive.set("paste.name", event.node.value);
});

ractive.on('updateTitle', function (event) {
    ractive.set("paste.title", event.node.value);
});

ractive.on('updateCode', function (event) {
    var editor = ace.edit("editor");
    var sourceCode = editor.getValue();
    ractive.set("paste.sourceCode", event.node.value);
});

ractive.on("updateTheme", function (event) { 
    updateTheme(event.node.value);
});

/** Controller events */
ractive.on('addPaste', function ( event ) {            
    addPaste();
});

ractive.on("updatePaste", function (event) {                        
    updatePaste();
});