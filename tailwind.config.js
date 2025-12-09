import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Livewire/**/*.php',               // ← WICHTIG!
        './app/View/Components/**/*.php',
        './vendor/livewire/**/*.blade.php',
        './vendor/robsontenorio/mary/**/*.blade.php',
        './storage/framework/views/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [
        forms,
        require('daisyui'),  // ← DaisyUI als Plugin
    ],
    daisyui: {
        themes: ["light", "dark", "cupcake"],  // ← Themes aktivieren (für Primary/Success etc.)
        base: true,  // ← Basis-Styles aktivieren
        utils: true,  // ← Utility-Klassen (btn, dropdown etc.)
        logs: false,  // ← Logs deaktivieren
    },
}
