const fs = require('fs');
const getStdin = require('get-stdin');

const postcss = require('postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');

const name = process.argv[2];

getStdin().then(css => {
    postcss([autoprefixer]).process(css, {from: undefined}).then(result1 => {
        postcss([cssnano]).process(result1.css, {from: undefined}).then(result2 => {
            fs.writeFileSync(`assets/css/${name}.css`, result1.css);
            fs.writeFileSync(`assets/css/${name}.min.css`, result2.css)
        });
    });
});
