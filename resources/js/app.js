import './bootstrap';

import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';

FilePond.registerPlugin(
    FilePondPluginFileValidateSize,
    FilePondPluginFileValidateType,
    FilePondPluginImagePreview
);

document.addEventListener('DOMContentLoaded', () => {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    document.querySelectorAll('.filepond').forEach(p => {
        FilePond.create(p, {
            server: {
                process: { url: '/upload', method: 'POST', headers: { 'X-CSRF-TOKEN': token } },
                revert: { url: '/upload/revert', method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } }
            },
            allowMultiple: false,
            maxFileSize: '5MB',
            acceptedFileTypes: ['image/*', 'application/pdf']
        });
    });
});
