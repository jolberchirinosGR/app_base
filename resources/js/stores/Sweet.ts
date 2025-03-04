import Swal, { SweetAlertOptions, SweetAlertResult } from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';
import { Colors } from './ColorsCustoms';  // Importa los colores desde el archivo ColorCustoms.ts

/**
 * Función para configurar y devolver una instancia de SweetAlert2.
 */
export function useSweetAlert() {
  const swal = Swal.mixin({});
  return swal;
}

export function useSweet() {
  const swal = Swal.mixin({
    customClass: {
      confirmButton: 'custom-confirm-button',  // Clase personalizada para el botón de confirmación
      cancelButton: 'custom-cancel-button',    // Clase personalizada para el botón de cancelación
      denyButton: 'custom-deny-button',        // Clase personalizada para el botón de denegación
    },
    didOpen: () => {
      const confirmButton = document.querySelector('.swal2-confirm') as HTMLElement;
      const cancelButton = document.querySelector('.swal2-cancel') as HTMLElement;
      const denyButton = document.querySelector('.swal2-deny') as HTMLElement;

      // Personalizamos el botón "Confirmar" con el verde suave desde ColorCustoms
      if (confirmButton) {
        confirmButton.style.backgroundColor = Colors.successGreen;
      }

      // Personalizamos el botón "Cancelar" con el gris oscuro desde ColorCustoms
      if (cancelButton) {
        cancelButton.style.backgroundColor = Colors.cancelGray;
      }

      // Personalizamos el botón "Denegar" con el rojo suave desde ColorCustoms
      if (denyButton) {
        denyButton.style.backgroundColor = Colors.denyRed;
      }
    }
  });
  return swal;
}

/**
 * Muestra un mensaje de éxito con SweetAlert2.
 * @param message - Mensaje a mostrar.
 */
export function showSuccessMessage(message: string): Promise<SweetAlertResult> {
  const swal = useSweet();
  return swal.fire({
    icon: 'success',
    title: message,
  });
}

/**
 * Muestra un mensaje de error con SweetAlert2.
 * @param message - Mensaje a mostrar.
 */
export function showErrorMessage(message: string): Promise<SweetAlertResult> {
  const swal = useSweet();
  return swal.fire({
    icon: 'error',
    title: message,
  });
}

/**
 * Muestra un mensaje de error con múltiples mensajes en una lista.
 * @param messages - Un objeto donde las claves son los campos y los valores son arrays de mensajes.
 */
export function showErrorGroupMessages(messages: Record<string, string[]>): Promise<SweetAlertResult> {
  const swal = useSweet();

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