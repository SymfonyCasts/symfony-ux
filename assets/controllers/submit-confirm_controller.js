import { Controller } from 'stimulus';
import Swal from 'sweetalert2';

export default class extends Controller {
    onSubmit(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                this.removeFromCart();
            }
        });
    }

    removeFromCart() {
        console.log('removing!');
    }
}
