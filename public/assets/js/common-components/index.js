import GitRepositoryHeader from './GitRepositoryHeader/GitRepositoryHeader.js'
import Header from './Header/Header.js'
import Footer from './Footer/Footer.js'
import Modal from './Modals/response/modal.js'
import EditModal from './Modals/edit/edit_task.modal.js'

document.querySelector('.git-header-section').innerHTML = GitRepositoryHeader;
document.querySelector('.main-header').innerHTML = Header;
document.querySelector('.modal-container').innerHTML = Modal;
document.querySelector('.footer-section').innerHTML = Footer;

if (document.cookie.split("; ").find(element => {
  return element.includes("jwtRefresh")
}) || sessionStorage.getItem('access_token')) {
  document.getElementById('left-side-header').style.display = 'none';
} else {
  document.getElementById('left-side-header').style.display = 'block';
}
