/*
Hier werden alle JavaScript anpassungen des Plugins für die generierten Website sowie dem Admin Menü in Wordpress.

Die einzelnen Abschnitte sind je nach Inhalt strukturiert.
 */

/*
* Website Tab
*/
function toggleNav() {
    var nav = document.getElementById("secondaryNav");
    if (nav.classList.contains("active")) {
        nav.classList.remove("active");
    } else {
        nav.classList.add("active");
    }
}
/*
* Design Tab
*/
/*
* Job Tab
*/
/*
* Ausbildung Tab
*/
/*
* Kontakt Tab
*/
function makeBold() {
    formatText("<strong>", "</strong>");
}

function makeItalic() {
    formatText("<em>", "</em>");
}

function makeThin() {
    formatText("<span style='font-weight: normal;'>", "</span>");
}

function makeHeader(level) {
    formatText("<h" + level + ">", "</h" + level + ">");
}

function alignText(align) {
    formatText("<div style='text-align:" + align + ";'>", "</div>");
}

function makeList(type) {
    var prefix = (type === 'ordered') ? "<ol>" : "<ul>";
    var suffix = (type === 'ordered') ? "</ol>" : "</ul>";
    formatText(prefix + "<li>", "</li>" + suffix);
}

function makeLink() {
    var url = prompt("Bitte geben Sie die URL ein:");
    if (url) {
        formatText("<a href='" + url + "'>", "</a>");
    }
}

/*
* Lizenz Tab
*/
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.nav-tab');
    const tabContents = document.querySelectorAll('.krp-tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(event) {
            if(tab.classList.contains('disabled')) {
                event.preventDefault();
                return;
            }
            event.preventDefault();

            tabs.forEach(t => t.classList.remove('nav-tab-active'));
            tab.classList.add('nav-tab-active');

            tabContents.forEach(tc => tc.style.display = 'none');
            document.querySelector(tab.getAttribute('href')).style.display = 'block';
        });
    });
});