const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                lexend: "'Lexend', sans-serif",
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                "theme-primary": "#DC3545",
                "theme-secondary": "#122C4F",
                "theme-text": "#5C5C5C",
                "theme-pages": "#F5F5F9",
                "theme-btn": "#696CFF",
                disabled: "#FAFAFA",
            },
        },
    },

    // plugins: [require("@tailwindcss/forms")],
};
