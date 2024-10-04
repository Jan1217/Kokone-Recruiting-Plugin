/*
Hier werden alle JavaScript anpassungen des Plugins für die generierten Website sowie dem Admin Menü in Wordpress.

Die einzelnen Abschnitte sind je nach Inhalt strukturiert.
 */

/*
* Plugin Seite
*/
function validateForm() {
    let isValid = true;

    // Fehlernachrichten zurücksetzen
    document.querySelectorAll('.error-message').forEach(function(el) {
        el.innerHTML = '';
    });

    // Vorname validieren
    const vorname = document.getElementById('job_bewerbung_vorname').value;
    if (!vorname) {
        document.getElementById('error-vorname').innerHTML = 'Vorname ist erforderlich.';
        isValid = false;
    }

    // Nachname validieren
    const nachname = document.getElementById('job_bewerbung_nachname').value;
    if (!nachname) {
        document.getElementById('error-nachname').innerHTML = 'Nachname ist erforderlich.';
        isValid = false;
    }

    // Straße validieren
    const strasse = document.getElementById('job_bewerbung_strasse').value;
    if (!strasse) {
        document.getElementById('error-strasse').innerHTML = 'Straße ist erforderlich.';
        isValid = false;
    }

    // Ort validieren
    const ort = document.getElementById('job_bewerbung_ort').value;
    if (!ort) {
        document.getElementById('error-ort').innerHTML = 'PLZ und Wohnort sind erforderlich.';
        isValid = false;
    }

    // Telefonnummer validieren
    const telefon = document.getElementById('job_bewerbung_telefon').value;
    if (!telefon) {
        document.getElementById('error-telefon').innerHTML = 'Telefonnummer ist erforderlich.';
        isValid = false;
    }

    // E-Mail validieren
    const email = document.getElementById('job_bewerbung_email').value;
    if (!email || !validateEmail(email)) {
        document.getElementById('error-email').innerHTML = 'Ungültige E-Mail-Adresse.';
        isValid = false;
    }

    return isValid;
}

function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}


/*
* Text Eingabe Felder
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