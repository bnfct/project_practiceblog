function addCheck() {
    var title = document.getElementById("title").value.length;
    document.getElementById("title_counter").innerHTML = title;
    document.getElementById("title_max").innerHTML = document.getElementById("title").getAttribute("maxlength");

    var summary = document.getElementById("summary").value.length;
    document.getElementById("summary_counter").innerHTML = summary;
    document.getElementById("summary_max").innerHTML = document.getElementById("summary").getAttribute("maxlength");

    var link = document.getElementById("link").value.length;
    document.getElementById("link_counter").innerHTML = link;
    document.getElementById("link_max").innerHTML = document.getElementById("link").getAttribute("maxlength");
    
    if (title >= 3 && summary >= 3 && link >= 3) {
        document.getElementById("submit_button").disabled = false;
    } else {
        document.getElementById("submit_button").disabled = true;
    }
}
function linkGenerator() {
    var originalText = document.getElementById("title").value;
    var result = originalText.normalize('NFD').replace(/[\u0300-\u036f]/g, "").replace(/\./g,' ').replace(/[^a-zA-Z0-9 ]/g, '').replace(/\s+/g, '-').toLowerCase();
    document.getElementById("link").value = result;
}