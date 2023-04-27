export class ContactForm {
    constructor(formEl, modal = () => {}) {
      this.forms = document.querySelectorAll(formEl);
      this.modal = modal;

      this.init();
    }

    init() {
        this.forms.forEach(form => {
            form.addEventListener("submit", (e) => this.send(e))
        })
    }

    send(e) {
        e.preventDefault();
        const formEl = e.target;
        const formData = this.prepareFormData(new FormData(formEl));
        const path = formEl.dataset.path ? formEl.dataset.path : 'send-message';
        this.resetErrors(formData, formEl);

        fetch(`/api/${path}`, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {

        if (data.status === "error") {
            const errors = this.onValidate(data, formData);
            this.handleErrors(errors, formEl);
            return;
        }

        formEl.reset();

            const typeModal = data.type === 'order' ? 'order' : 'success';
            this.modal.closeAllModals();
            this.modal.openModal(typeModal, data.message);

            // eslint-disable-next-line no-undef
            ym(93154317,'reachGoal','zaivka');
        })
        .catch(e => {
            console.log(e.message);
        })
    }

    onValidate(arrayErrors, form) {
      let errors = [];

      Object.keys(arrayErrors).forEach(key => {
        if (form.has(key)) {
          arrayErrors[key].forEach(err => {
            errors.push({ key: key, message: err })
          })
        }
      })

      return errors;
    }

    handleErrors(errors, formEl) {
      errors.forEach(error => {
        this.displayError(formEl, error.key, error.message);
      })
    }

    displayError(formEl, key, error) {
      const errInput = formEl.querySelectorAll(`[name="${key}"]`),
            errEl = formEl.querySelectorAll(`[data-error-for="${key}"]`);
      if (errEl) {
        errEl.forEach(err => err.textContent = error);
        errInput.forEach(input => input.parentElement.classList.add("error"))
      }
    }

    resetErrors(formData, formEl) {
      for (let [key] of formData) {
        const errInput = formEl.querySelectorAll(`[name="${key}"]`),
              errEl = formEl.querySelectorAll(`[data-error-for="${key}"]`);
        if (errEl) {
          errEl.forEach(err => err.textContent = "");
          errInput.forEach(input => input.parentElement.classList.remove("error"))
        }
      }
    }

    prepareFormData(formData) {
      let newFormData = new FormData();
      for (let pair of formData.entries()) {
        let key = pair[0];
        let value = pair[1];
        if (key === 'user_phone') {
          let phone = value.replace(/[- )(_]/g,'');
          newFormData.append(key, phone);
        } else {
          newFormData.append(key, value);
        }
      }
      return newFormData;
    }
  }
