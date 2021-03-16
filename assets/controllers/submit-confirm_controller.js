import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {
    static values = {
        title: String,
        text: String,
        icon: String,
        confirmButtonText: String,
    }

    onSubmit(event) {
        event.preventDefault();

        Swal.fire({
            title: this.titleValue || null,
            text: this.textValue || null,
            icon: this.iconValue || null,
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: this.confirmButtonTextValue || 'Yes',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                this.submitForm();
            }
        });
    }

    submitForm() {
        console.log('submitting form!');
    }
}
