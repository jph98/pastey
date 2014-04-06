/**
 * Custom Ace Editor for Pastey
 */

function addThemes() {

    var themes = ["tomorrow", "ambiance", "chaos", "clouds", "chrome", "cobalt", 
                  "dawn", "eclipse", "github", "monokai", "twilight", "xcode"];

    _.map(themes, function(themeName) {
        $("#theme").append($('<option></option>').val("ace/theme/" + themeName).html(themeName));
    });

}

function changeTheme(editor, theme) {

    editor.setTheme(theme);
    $("#theme").val(theme);
}

function setupEditor(editor) {

    var defaultTheme = "eclipse";
    editor.setTheme("ace/theme/" + defaultTheme);
    $("#editor").css("fontSize", "16px");

    $("#editorTheme").filter(function() {
        return $(this).text() == defaultTheme; 
    }).prop('selected', true);

    var language = ractive.get("paste.language");
    if (language != undefined) {    
        editor.getSession().setMode("ace/mode/" + language);
    } else {
        editor.getSession().setMode("ace/mode/java");
    }
    
    editor.getSession().setTabSize(4);
    editor.getSession().setUseSoftTabs(true);
    editor.setValue(ractive.get("paste.sourcecode"));
    editor.gotoLine(1);

    $("#editor").width("800px");
    $("#editor").height("300px");

    editor.focus();
}

function changeLanguage(editor, language) {
    editor.getSession().setMode("ace/mode/" + language);
}