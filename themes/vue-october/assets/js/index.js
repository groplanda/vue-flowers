import { ContactForm } from "./plugins/ContactForm";
import { Modal } from "./plugins/Modal";
import Inputmask from "inputmask";

document.addEventListener('DOMContentLoaded', () => {
    const modal = new Modal();
    new ContactForm('[data-js-action="send-form"]', modal);

    const phones = document.querySelectorAll('[data-mask="phone"]');
    const im = new Inputmask('+7 (999) 999-99-99', {
        showMaskOnHover: false,
        showMaskOnFocus: true
    });
    phones.forEach(phone => im.mask(phone));
});
