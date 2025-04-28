/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        colors: {
          'custom-blue': {
            DEFAULT: ' #0655a8',
            
          },
          'custom-gran': {
            DEFAULT: ' #4f5062',
            
          },
        },
        fontFamily: {
          lato: ['Lato', 'sans-serif'],
        },
      },
    },
    plugins: [],
  }
  