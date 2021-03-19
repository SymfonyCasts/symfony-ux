import { Controller } from 'stimulus';
import { Modal } from 'bootstrap';

export default class extends Controller {
    static targets = ['modal'];

    openModal(event) {
        const modal = new Modal(this.modalTarget, {});
        modal.show();
    }
}
