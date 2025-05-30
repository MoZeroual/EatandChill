function validerFormulaire() {
  const participants = parseInt(document.getElementById('participants').value, 10);
  if (isNaN(participants) || participants < 1 || participants > 10) {
    alert("Veuillez entrer un nombre de participants entre 1 et 10.");
    return false;
  }
  return true;
}