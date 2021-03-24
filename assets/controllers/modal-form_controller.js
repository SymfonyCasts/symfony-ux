import { Controller } from 'stimulus';
import { Modal } from 'bootstrap';
import $ from 'jquery';
import { useDispatch } from 'stimulus-use';

export default class extends Controller {
    static targets = ['modal', 'modalBody'];
    static values = {
        formUrl: String,
    }
    modal = null;

    connect() {
        useDispatch(this);
    }

    async openModal(event) {
        this.modalBodyTarget.innerHTML = 'Loading...';
        this.modal = new Modal(this.modalTarget);
        this.modal.show();

        this.modalBodyTarget.innerHTML = await $.ajax(this.formUrlValue);
    }

    async submitForm(event) {
        event.preventDefault();
        const $form = $(this.modalBodyTarget).find('form');

        try {
            await $.ajax({
                url: this.formUrlValue,
                method: $form.prop('method'),
                data: $form.serialize(),
            });
            this.modal.hide();
            this.dispatch('success');
        } catch (e) {
            this.modalBodyTarget.innerHTML = e.responseText;
        }
    }

    modalHidden() {
        console.log('it was hidden!');
    }
}
