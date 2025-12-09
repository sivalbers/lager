import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/Livewire/**/*.php",
        "./app/View/Components/**/*.php",
        "./vendor/livewire/**/*.blade.php",
        "./vendor/robsontenorio/mary/**/*.blade.php",

        // besonders wichtig unter Plesk!
        "./storage/framework/views/*.php",

        // DaisyUI braucht dynamische Klassen
        "./node_modules/daisyui/dist/**/*.js",
        "./node_modules/daisyui/**/*.js",
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
        require("daisyui")
    ],
    daisyui: {
        themes: ["light", "dark", "cupcake"],
        base: true,
        utils: true,
        logs: false,
    },
}
