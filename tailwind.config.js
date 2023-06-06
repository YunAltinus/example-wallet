/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/src/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        fontFamily: {
            poppins: ["Poppins", "sans-serif"],
          },
        extend: {
            colors: {
                "theme-main": "#ff5e3a",
                "hover-theme-main": "#545a64",
                "theme-primary": "#3f4257",
                "body-bg": "#EDF2F6",
                "body-color": "#757f8f",
                "black-rgb": "rgb(0,0,0,0.7)",
              },
        },
        container: {
            center: true,
            padding: "1rem",
      
            screens: {
              sm: "600px",
              md: "728px",
              lg: "984px",
              xl: "1200px",
              "2xl": "1300px",
            },
          },
    },
    plugins: [],
};
