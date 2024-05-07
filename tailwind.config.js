/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./assets/react/controllers/*.jsx"
  ],
  theme: {
    extend: {
      fontFamily: {
        darkerGrotesque: ['"Darker Grotesque"', "sans-serif"],
      },
    },
  },
  plugins: [],
}
