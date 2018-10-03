

export const ASC = 'ASC';
export const DESC = 'DESC';


class OrderBy {

    constructor(value, dir) {
        if(typeof value === 'string') {
            this.string = value;
        } else {
            this.field = value;
        }

        if(dir !== undefined) {
            this.dir = dir;
        }
    }

    static parse(value) {
        if(value instanceof OrderBy) {
            return value;
        }
        if(!value) {
            return null;
        }
        return new OrderBy(value);
    }

    get field() {
        return this._field;
    }

    set field(val) {
        if(typeof val === 'string') {
            this._field = val;
        } else if(val && typeof val === 'object') {
            this._field = val.field || val.sortField || val.key;
        } else {
            this._field = undefined;
        }

    }

    get dir() {
        if(this._dir === DESC || this._dir === false) {
            return DESC;
        } else {
            return ASC;
        }
    }

    // noinspection JSAnnotator
    set dir(val) {
        this._dir = val;
    }

    get asc() { return this.dir === ASC; }
    get desc() { return this.dir === DESC; }

    /**
     * Getter for the string property.
     */
    get string() {
        if(this.field) {
            return this.field + '_' + this.dir;
        } else {
            return null;
        }
    }

    // noinspection JSAnnotator
    /**
     * Getter for the string property.
     */
    set string(val) {
        if(typeof val === 'string') {
            const found = val.match(/^\s*([a-zA-Z0-9]+)(?:_(ASC|DESC))?\s*$/);
            if(found) {
                this.field = found[1];
                this.dir = found[2];
            }
        }
    }

    inverted() {
        return new OrderBy(this, this.dir === ASC ? DESC : ASC);
    }

}

OrderBy.ASC = ASC;
OrderBy.DESC = DESC;


export default OrderBy;