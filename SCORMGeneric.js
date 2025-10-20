var nFindAPITries = 0;
var objAPI = null;
var bFinishDone = false;

function FindAPI(win) {
    while ((win.API == null) && (win.parent != null) && (win.parent != win)) {
        nFindAPITries++;
        if (nFindAPITries > 500) {
            alert("Error finding LMS API.");
            return null;
        }
        win = win.parent;
    }
    return win.API;
}

function APIOK() {
    return ((typeof(objAPI) != "undefined") && (objAPI != null));
}

function SCOInitialize() {
    if ((window.parent) && (window.parent != window)) {
        objAPI = FindAPI(window.parent);
    }
    if ((objAPI == null) && (window.opener != null)) {
        objAPI = FindAPI(window.opener);
    }
    if (!APIOK()) {
        alert("LMS interface not found.");
        return "false";
    } else {
        return objAPI.LMSInitialize("");
    }
}

function SCOFinish() {
    if (APIOK() && !bFinishDone) {
        bFinishDone = (objAPI.LMSFinish("") === "true");
    }
    return bFinishDone.toString();
}

