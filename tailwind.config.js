import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans:    ['DM Sans', ...defaultTheme.fontFamily.sans],
                display: ['Syne', ...defaultTheme.fontFamily.sans],
            },

            colors: {
                brand: {
                    DEFAULT: '#F97316',
                    dark:    '#C2410C',
                    light:   '#FB923C',
                },
                surface: {
                    DEFAULT: '#111113',
                    2:       '#1A1A1E',
                    3:       '#222228',
                },
            },

            borderColor: {
                DEFAULT: 'rgba(255,255,255,0.08)',
            },

            backgroundOpacity: {
                2:  '0.02',
                3:  '0.03',
                8:  '0.08',
                15: '0.15',
            },

            animation: {
                'slide-down': 'slideDown 0.3s ease',
                'fade-in':    'fadeIn 0.25s ease',
            },

            keyframes: {
                slideDown: {
                    '0%':   { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%':   { opacity: '0' },
                    '100%': { opacity: '1' },
                },
            },

            boxShadow: {
                'brand':    '0 0 20px rgba(249,115,22,0.3)',
                'card':     '0 4px 24px rgba(0,0,0,0.4)',
                'card-lg':  '0 12px 48px rgba(0,0,0,0.5)',
            },
        },
    },

    plugins: [],
};
