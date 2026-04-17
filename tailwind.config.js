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
          'primary': '#2d6a4f',
          'secondary': '#52b788',
          'accent': '#ffb703',
          'dark': '#1b4332',
          'light': '#f8f9fa',
        },
        fontFamily: {
          'inter': ['Inter', 'sans-serif'],
        },
      },
    },
    plugins: [
      require('@tailwindcss/forms'),
      require('@tailwindcss/typography'),
    ],
  }