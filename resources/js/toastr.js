import toastr from 'toastr';
import 'toastr/build/toastr.min.css';

export function useToastr() {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 15000, // Aquí estableces el tiempo en milisegundos (5000ms = 5 segundos)
        // Otras opciones que desees configurar
    };
    
    return toastr;
}
