import { Controller } from 'stimulus';
import { Modal } from 'bootstrap';
import $ from 'jquery';

export default class extends Controller {
    static targets = ['modal', 'modalBody'];
    static values = {
        formUrl: String,
    }

    async openModal(event) {
        this.modalBodyTarget.innerHTML = 'Loading...';
        const modal = new Modal(this.modalTarget, {});
        modal.show();

        this.modalBodyTarget.innerHTML = await $.ajax(this.formUrlValue);
    }

    submitForm() {
        const $form = $(this.modalBodyTarget).find('form');
        console.log($form.serialize());
    }
}
