import './bootstrap';

import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';

// Plugins
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

// Plugins registrieren
FilePond.registerPlugin(
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview
);

document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const ponds = document.querySelectorAll('.filepond');

    ponds.forEach(p => {
        console.log('Initialisiere FilePond für', p);

        const pond = FilePond.create(p, {
            server: {
                process: {
                    url: '/upload',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                },
                revert: {
                    url: '/upload/revert',
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                }
            },
            allowMultiple: false,
            maxFileSize: '5MB',
            acceptedFileTypes: ['image/*', 'application/pdf']
        });

        // 🔎 Debug: Upload-Ergebnisse loggen
        pond.on('processfile', (error, file) => {
            if (error) {
                console.error('❌ Upload-Fehler:', error);
            } else {
                console.log('✅ Upload erfolgreich:', file.serverId);
            }
        });
    });
});



