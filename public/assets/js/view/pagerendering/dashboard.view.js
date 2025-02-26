export function  renderProject() {
  document.querySelector('.main-section').innerHTML = '';
  const newHtml = history.state;
  document.querySelector('.main-section').innerHTML = newHtml;
} 