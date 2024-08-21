/*
Hier werden alle JavaScript anpassungen des Plugins für die generierten Website sowie dem Admin Menü in Wordpress.

Die einzelnen Abschnitte sind je nach Inhalt strukturiert.
 */

/*
* Website Tab
*/
document.getElementById("filter-button").addEventListener("click", function() {
    // Hole die Suchanfrage und den ausgewählten Standort
    var searchQuery = document.getElementById("job-ausbildung-search").value.toLowerCase();
    var selectedLocation = document.getElementById("job-ausbildung-location-filter").value.toLowerCase();

    // Filter Jobs
    var jobTiles = document.querySelectorAll(".ort-restrict-job-tiles-container .job-tile-main");
    jobTiles.forEach(function(tile) {
        var jobTitle = tile.querySelector(".job-title").textContent.toLowerCase();
        var jobLocation = tile.querySelector(".job-tile").getAttribute("data-location").toLowerCase();

        var titleMatch = jobTitle.includes(searchQuery);
        var locationMatch = selectedLocation === "" || jobLocation === selectedLocation;

        if (titleMatch && locationMatch) {
            tile.style.display = "block";
        } else {
            tile.style.display = "none";
        }
    });

    // Filter Ausbildungen
    var ausbildungTiles = document.querySelectorAll(".ort-restrict-ausbildung-tiles-container .ausbildung-tile-main");
    ausbildungTiles.forEach(function(tile) {
        var ausbildungTitle = tile.querySelector(".ausbildung-title").textContent.toLowerCase();
        var ausbildungLocation = tile.querySelector(".ausbildung-tile").getAttribute("data-location").toLowerCase();

        var titleMatch = ausbildungTitle.includes(searchQuery);
        var locationMatch = selectedLocation === "" || ausbildungLocation === selectedLocation;

        if (titleMatch && locationMatch) {
            tile.style.display = "block";
        } else {
            tile.style.display = "none";
        }
    });
});
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