const yaml = require('js-yaml');
const toSource = require('tosource');





module.exports = function(source) {
    this.cacheable && this.cacheable();

    try {
        const res = yaml.load(source);
        return 'module.exports = '+toSource(res);
    } catch (err) {
        this.emitError(err);
        return null;
    }

};