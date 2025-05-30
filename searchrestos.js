let currentPage = 1;
let currentAddress = '';
let currentType = 'all';

document.getElementById("addressForm").addEventListener("submit", function (e) {
  e.preventDefault();
  currentPage = 1;
  currentAddress = document.getElementById("address").value.trim();
  currentType = document.getElementById("type").value;

  if (!currentAddress) {
    document.getElementById("results").innerHTML = "<p>Veuillez saisir une adresse.</p>";
    return;
  }

  loadResults(currentAddress, currentPage, currentType);
});

function loadResults(address, page, type) {
  const resDiv = document.getElementById("results");
  resDiv.innerHTML = "Chargement...";

  fetch("get_places.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "address=" + encodeURIComponent(address) +
          "&page=" + page +
          "&type=" + encodeURIComponent(type),
  })
    .then(response => response.json())
    .then(data => {
      console.log("Réponse JSON :", data);

      if (data.erreur) {
        resDiv.innerHTML = `<p>Erreur : ${data.erreur}</p>`;
        return;
      }

      if (!Array.isArray(data.results) || data.results.length === 0) {
        resDiv.innerHTML = "<p>Aucun restaurant ou fast-food trouvé à proximité.</p>";
        return;
      }

      let html = "<ul>" + data.results.map(place => `<li>${place}</li>`).join('') + "</ul>";

      const totalPages = Math.ceil(data.total / data.limit);

      if (totalPages > 1) {
        html += '<div style="margin-top:15px;">';

        if (data.page > 1) {
          html += `<button id="prevBtn">← Précédent</button> `;
        }

        html += ` Page ${data.page} / ${totalPages} `;

        if (data.page < totalPages) {
          html += `<button id="nextBtn">Suivant →</button>`;
        }

        html += '</div>';
      }

      resDiv.innerHTML = html;

      if (document.getElementById("prevBtn")) {
        document.getElementById("prevBtn").addEventListener("click", () => {
          if (currentPage > 1) {
            currentPage--;
            loadResults(currentAddress, currentPage, currentType);
          }
        });
      }

      if (document.getElementById("nextBtn")) {
        document.getElementById("nextBtn").addEventListener("click", () => {
          if (currentPage < totalPages) {
            currentPage++;
            loadResults(currentAddress, currentPage, currentType);
          }
        });
      }
    })
    .catch(err => {
      resDiv.innerHTML = `<p>Erreur de chargement : ${err.message}</p>`;
    });
}
