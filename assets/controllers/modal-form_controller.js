import { Controller } from 'stimulus';
import { Modal } from 'bootstrap';
import $ from 'jquery';

export default class extends Controller {
    static targets = ['modal', 'modalBody'];
    static values = {
        formUrl: String,
    }

    async openModal(event) {
        const modal = new Modal(this.modalTarget);
        modal.show();

        this.modalBodyTarget.innerHTML = await $.ajax(this.formUrlValue);
    }
}
