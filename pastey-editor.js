/**
 * Custom Ace Editor for Pastey
 */

function addThemes() {

    var themeDiv = $("#theme");
    var themes = ["tomorrow", "ambiance", "chaos", "clouds", "chrome", "cobalt", 
                  "dawn", "eclipse", "github", "monokai", "twilight", "xcode"];

    _.map(themes, function(themeName) {
        themeDiv.append($('<option></option>').val("ace/theme/" + themeName).html(themeName));
    });
}

function updateTheme(theme) {
    
    var editor = ace.edit("editor");
    ractive.set("paste.theme", theme);         
    editor.setTheme(theme);
    $("#theme").val(theme);
    ractive.set("paste.theme", theme)
}

function setupFieldValues(editor) {

    if (ractive.get("paste.name") != undefined) {
        $("#name").val(ractive.get("paste.name"));
    } else {
        var defaultName = "Anonymous"
        $("#name").val(defaultName);
        ractive.set("paste.name", defaultName);
    }

    if (ractive.get("paste.title") != undefined) {
        $("#title").val(ractive.get("paste.title"));
    } else {
        var defaultTitle = "Untitled";
        $("#title").val(defaultTitle);
        ractive.set("paste.title", defaultTitle);
    }

    var theme = ractive.get("paste.theme");
    if (theme != undefined) {

        editor.setTheme(theme);
        $("#theme").val(theme);

    } else {

        var defaultTheme = "ace/theme/eclipse";
        editor.setTheme(defaultTheme);
        $("#theme").val(defaultTheme);
    }

    var language = ractive.get("paste.language");
    if (language != undefined) {    
        editor.getSession().setMode("ace/mode/" + language);
    } else {
        var DEFAULT_LANGUAGE = "ruby";
        $("#language").val(DEFAULT_LANGUAGE);
        editor.getSession().setMode("ace/mode/" + DEFAULT_LANGUAGE);
        ractive.set("paste.language", DEFAULT_LANGUAGE);
    }
}

function setupEditor(editor) {

    var editorDiv = $("#editor")
    editorDiv.css("fontSize", "16px");

    editor.getSession().setTabSize(4);
    editor.getSession().setUseSoftTabs(true);
    editor.setValue(ractive.get("paste.sourcecode"));
    editor.gotoLine(1);

    // Quick submit form
    editor.commands.addCommand({
        name: 'submit',
        bindKey: {win: 'Ctrl-ENTER', mac: 'Command-ENTER'},
        exec: function(editor) {
            addPaste();
        },
        readOnly: true // false if this command should not apply in readOnly mode
    });

    editorDiv.width("900px");
    editorDiv.height("300px");

    addThemes();
    
    setupFieldValues(editor);

    editor.focus();
}