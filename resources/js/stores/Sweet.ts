import Swal, { SweetAlertOptions, SweetAlertResult } from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';

/**
 * Función para configurar y devolver una instancia de SweetAlert2.
 */
export function useSweetAlert() {
  const swal = Swal.mixin({});
  return swal;
}

/**
 * Muestra un mensaje de éxito con SweetAlert2.
 * @param message - Mensaje a mostrar.
 */
export function showSuccessMessage(message: string): Promise<SweetAlertResult> {
  const swal = useSweetAlert();
  return swal.fire({
    icon: 'success',
    title: message
  });
}

/**
 * Muestra un mensaje de error con SweetAlert2.
 * @param message - Mensaje a mostrar.
 */
export function showErrorMessage(message: string): Promise<SweetAlertResult> {
  const swal = useSweetAlert();
  return swal.fire({
    icon: 'error',
    title: message
  });
}

/**
 * Muestra un mensaje de error con múltiples mensajes en una lista.
 * @param messages - Un objeto donde las claves son los campos y los valores son arrays de mensajes.
 */
export function showErrorGroupMessages(messages: Record<string, string[]>): Promise<SweetAlertResult> {
  const swal = useSweetAlert();

  const errorList = Object.entries(messages)
    .map(([_, errors]) => errors.map(message => `<li>${message}</li>`).join(''))
    .join('</br>');

  const formattedMessages = `<ul>${errorList}</ul>`;

  return swal.fire({
    icon: 'error',
    title: 'Error',
    html: formattedMessages,
    showConfirmButton: true,
    backdrop: `rgba(0,0,0,0.5)`
  });
}