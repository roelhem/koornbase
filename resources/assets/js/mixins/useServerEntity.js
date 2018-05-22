import useServerData from './useServerData';

export default {

    mixins:[useServerData],

    props: {

        allowLoad: {
            type:Boolean,
            default:false,
        },

        val: null

    },

    computed: {

        _config:function() {
            return {
                endpoint:null,
                params:null,
            };
        },

        _valIsRef:function() {
            const val = this.val;
            if(typeof val === 'string' || typeof val === 'number') {
                return true;
            }
            return false;
        },

        _valIsData:function() {
            const val = this.val;
            if(val && typeof val === 'object') {
                return true;
            }
            return false;
        },

        entity:function() {
            if(this._valIsData) {
                return this.val;
            } else {
                return this.serverData;
            }
        },

        isLoaded:function() {
            return this.entity !== null;
        },

        __endpoint:function() {
            let res = [];
            if(this._config.endpoint) {
                const endpoint = this._config.endpoint;
                if(Array.isArray(endpoint)) {
                    res = res.concat(endpoint);
                } else {
                    res.push(endpoint);
                }
            }
            if(this._valIsRef) {
                const val = this.val;
                res.push(val);
            }
            return res;
        },

        serverData_config:function() {
            return {
                endpoint: this.__endpoint,
                params: this._config.params,
            };
        }
    },

    mounted() {
        if(!this.isLoaded && this.allowLoad) {
            this.loadServerData();
        }
    }

};