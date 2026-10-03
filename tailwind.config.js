export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f0f5ff',
                    100: '#e0ebff',
                    200: '#b8d4fe',
                    300: '#7cb4fc',
                    400: '#4a94f8',
                    500: '#1a6ff5',
                    600: '#0a5ed4',
                    700: '#0b4aab',
                    800: '#0f3f8c',
                    900: '#123672',
                    950: '#0c2345',
                },
            },
            fontFamily: {
                sans: ['Cairo', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ],
};
