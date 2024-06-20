import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';

export function useSweetAlert() {
  // Configuración de SweetAlert2
  const swal = Swal.mixin({
  });

  return swal;
}

export function showSuccessMessage(message) {
    const swal = useSweetAlert();
    swal.fire({
      icon: 'success',
      title: message
    });
}

export function showErrorMessage(message) {
    const swal = useSweetAlert();
    swal.fire({
      icon: 'error',
      title: message
    });
}
  
export function showErrorGroupMessages(messages) {
    const swal = useSweetAlert();

    const errorList = Object.keys(messages).map(field => {
        return messages[field].map(message => `<li>${message}</li>`).join('');
    }).join('</br>');

    const formattedMessages = `<ul>${errorList}</ul>`;

    swal.fire({
        icon: 'error',
        title: 'Error',
        html: formattedMessages,
        showConfirmButton: true,
        backdrop: `rgba(0,0,0,0.5)`
    });
}