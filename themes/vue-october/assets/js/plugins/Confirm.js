export class Confirm {
    constructor(trigger) {
      this.triggers = document.querySelectorAll(trigger);
      this.init()
    }

    init() {
      this.triggers.forEach(trigger => {
        trigger.addEventListener("change", () => this.handleTrigger(trigger))
        trigger.click();
      })
    }

    handleTrigger(trigger) {
      const form = trigger.closest('form');
      const submit = form.querySelectorAll('button[type="submit"]');

      submit.forEach(s => {
        trigger.checked
        ? s.disabled = false
        : s.disabled = true;
      })
    }
}
