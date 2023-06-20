export class Modal {

  constructor() {
    this.$trigger = document.querySelectorAll('[data-js-action="open-modal"]');
    this.$modalId = document.getElementById('modal');

    if (!this.$modalId) return;

    this.$modals = this.$modalId.querySelectorAll('[data-modal]');
    this.$close = this.$modalId.querySelectorAll('[data-role="close-modal"]');
    this.activeModalClass = "modal__content_selected";


    this.init();
  }


  init() {

    document.addEventListener("click", e => {
        const target = e.target;

        if (target && target.dataset.jsAction === 'open-modal' && (target.tagName.toLowerCase() === 'button' || target.tagName.toLowerCase() === 'a')) {
            this.hadleClick(e);
        }
    })

    this.$close.forEach(close => {
      close.addEventListener("click", this.hadleClose.bind(this));
    });

    document.addEventListener("keydown", event => {
      if (event.key === "Escape" && this.$modalId.classList.contains("modal_active")) {
        this.closeModal();
      }
    })

  }

  hadleClick(event) {
    event.preventDefault();
    const target = event.target;

    if (!target) return;

    const selected = target.dataset.name,
          offsetBody = this.getScrollBarWith() + "px";

    this.closeAllModals();

    if (this.openModal(selected)) {
      document.body.classList.add('open-modal');
      document.body.style.paddingRight = offsetBody;
    }

  }

  hadleClose(event) {
    const target = event.target;
    if (target.dataset.role === "close-modal") {
      this.closeModal();
    }
  }

  getScrollBarWith() {
    const documentWidth = parseInt(document.documentElement.clientWidth);
    const windowsWidth = parseInt(window.innerWidth);
    return windowsWidth - documentWidth;
  }

  openModal(name, text = '') {
    const modal = this.$modalId.querySelector(`[data-modal="${name}"]`);

    if (!modal) {
      return false;
    }

    if (text) {
        modal.querySelector('[data-modal="title"]').innerHTML = text;
    }

    this.$modalId.classList.add('modal_active');
    modal.classList.add(this.activeModalClass);
    return true;
  }

  closeModal() {
    this.$modalId.classList.remove('modal_active');
    this.closeAllModals();
    document.body.classList.remove('open-modal');
    document.body.style.paddingRight = '';
  }

  closeAllModals() {
    this.$modals.forEach(modal => modal.classList.remove(this.activeModalClass));
  }

  destroy() {

  }
}
